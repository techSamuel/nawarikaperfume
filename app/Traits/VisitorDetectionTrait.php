<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait VisitorDetectionTrait
{
    public function detectDevice(string $ua): string
    {
        if (preg_match('/(tablet|ipad|playbook|silk)|(android(?!.*mobi))/i', $ua)) {
            return 'Tablet';
        }
        if (preg_match('/(mobile|iphone|ipod|blackberry|opera mini|iemobile)/i', $ua)) {
            return 'Mobile';
        }
        return 'Desktop';
    }

    public function detectBrowser(string $ua): string
    {
        if (preg_match('/edg/i', $ua)) return 'Edge';
        if (preg_match('/chrome|crios/i', $ua) && !preg_match('/edg/i', $ua)) return 'Chrome';
        if (preg_match('/firefox|fxios/i', $ua)) return 'Firefox';
        if (preg_match('/safari/i', $ua) && !preg_match('/chrome|crios/i', $ua)) return 'Safari';
        if (preg_match('/msie|trident/i', $ua)) return 'Internet Explorer';
        if (preg_match('/opera|opr/i', $ua)) return 'Opera';
        return 'Browser';
    }

    public function detectPlatform(string $ua): string
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

    public function detectCountry(Request $request): string
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
