<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\VisitorLog;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TrackVisitorLog
{
    use \App\Traits\VisitorDetectionTrait;

    public function handle(Request $request, Closure $next): Response
    {
        if ($request->filled('fbclid')) {
            $fbclid = trim($request->query('fbclid'));
            session(['fbclid' => $fbclid]);
            $creationTimeMs = (int) round(microtime(true) * 1000);
            $fbc = 'fb.1.' . $creationTimeMs . '.' . $fbclid;
            session(['fbc' => $fbc]);
            // Set 90 days first-party cookie, accessible to JavaScript (httpOnly: false)
            cookie()->queue(cookie('_fbc', $fbc, 90 * 24 * 60, '/', null, null, false, false, 'Lax'));
        } elseif ($request->cookie('_fbc') && !session('fbc')) {
            session(['fbc' => $request->cookie('_fbc')]);
        }

        if ($request->cookie('_fbp')) {
            session(['fbp' => $request->cookie('_fbp')]);
        }

        $response = $next($request);

        // Skip static asset requests or internal API counts
        if ($request->ajax() && ($request->is('cart/*') || $request->is('checkout/*') || $request->is('api/products/search'))) {
            return $response;
        }

        try {
            $ip = $request->ip();
            $url = $request->fullUrl();

            // Session throttling: avoid logging identical path visit from same IP within 2 minutes
            $cacheKey = 'visitor_log_' . md5($ip . '_' . $request->path());
            if (cache()->has($cacheKey)) {
                return $response;
            }
            cache()->put($cacheKey, true, now()->addMinutes(2));

            $userAgent = $request->header('User-Agent', '');
            $deviceType = $this->detectDevice($userAgent);
            $browser = $this->detectBrowser($userAgent);
            $platform = $this->detectPlatform($userAgent);

            // Timezone detection from cookie/header or fallback
            $timezone = $request->header('X-Timezone') ?? $request->cookie('visitor_tz') ?? 'Asia/Dhaka';

            // Country detection
            $country = $this->detectCountry($request);

            $user = Auth::user();

            VisitorLog::create([
                'ip_address' => $ip,
                'user_id' => $user ? $user->id : null,
                'user_type' => $user ? ($user->isAdmin() ? 'Admin' : 'Logged-in User') : 'Guest',
                'user_name' => $user ? $user->name : null,
                'user_email' => $user ? $user->email : null,
                'user_agent' => substr($userAgent, 0, 500),
                'device_type' => $deviceType,
                'browser' => $browser,
                'platform' => $platform,
                'timezone' => $timezone,
                'country' => $country,
                'url' => substr($url, 0, 255),
                'referer' => substr($request->header('referer', ''), 0, 255),
                'visited_at' => now(),
            ]);
        } catch (\Exception $e) {
            logger()->error('Visitor tracking error: ' . $e->getMessage());
        }

        return $response;
    }
}
