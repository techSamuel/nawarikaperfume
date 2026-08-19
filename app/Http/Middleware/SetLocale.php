<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use App\Models\Setting;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $locale = session('app_locale');

        if (!$locale) {
            $locale = $request->cookie('app_locale');
        }

        if (!$locale) {
            try {
                $settings = Cache::remember('site_settings', 60*24, function () {
                    return Setting::pluck('value', 'key')->toArray();
                });
                $locale = $settings['default_language'] ?? 'en';
            } catch (\Exception $e) {
                $locale = 'en';
            }
        }

        if (in_array($locale, ['en', 'bn'])) {
            App::setLocale($locale);
        } else {
            App::setLocale('en');
        }

        return $next($request);
    }
}
