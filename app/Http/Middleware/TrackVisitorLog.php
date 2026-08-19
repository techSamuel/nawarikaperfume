<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\VisitorLog;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TrackVisitorLog
{
    public function handle(Request $request, Closure $next): Response
    {
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

    private function detectDevice(string $ua): string
    {
        if (preg_match('/(tablet|ipad|playbook|silk)|(android(?!.*mobi))/i', $ua)) {
            return 'Tablet';
        }
        if (preg_match('/(mobile|iphone|ipod|blackberry|opera mini|iemobile)/i', $ua)) {
            return 'Mobile';
        }
        return 'Desktop';
    }

    private function detectBrowser(string $ua): string
    {
        if (preg_match('/edg/i', $ua)) return 'Edge';
        if (preg_match('/chrome|crios/i', $ua) && !preg_match('/edg/i', $ua)) return 'Chrome';
        if (preg_match('/firefox|fxios/i', $ua)) return 'Firefox';
        if (preg_match('/safari/i', $ua) && !preg_match('/chrome|crios/i', $ua)) return 'Safari';
        if (preg_match('/msie|trident/i', $ua)) return 'Internet Explorer';
        if (preg_match('/opera|opr/i', $ua)) return 'Opera';
        return 'Browser';
    }

    private function detectPlatform(string $ua): string
    {
        if (preg_match('/windows nt 10/i', $ua)) return 'Windows 10/11';
        if (preg_match('/windows nt 6.3/i', $ua)) return 'Windows 8.1';
        if (preg_match('/windows/i', $ua)) return 'Windows';
        if (preg_match('/android/i', $ua)) return 'Android';
        if (preg_match('/iphone|ipad|ipod/i', $ua)) return 'iOS';
        if (preg_match('/macintosh|mac os x/i', $ua)) return 'macOS';
        if (preg_match('/linux/i', $ua)) return 'Linux';
        return 'OS';
    }

    private function detectCountry(Request $request): string
    {
        if ($request->header('HTTP_CF_IPCOUNTRY')) {
            return strtoupper($request->header('HTTP_CF_IPCOUNTRY'));
        }
        $ip = $request->ip();
        if ($ip === '127.0.0.1' || $ip === '::1' || str_starts_with($ip, '192.168.') || str_starts_with($ip, '10.')) {
            return 'Localhost / Dev';
        }
        return 'Bangladesh';
    }
}
