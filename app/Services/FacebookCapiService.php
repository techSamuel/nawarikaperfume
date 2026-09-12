<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Models\Setting;

class FacebookCapiService
{
    /**
     * Send an event to Facebook Conversions API
     *
     * @param string $eventName Name of the event (e.g. ViewContent, Purchase)
     * @param array $customData Event specific custom data (e.g. value, currency)
     * @param string|null $eventId Optional deduplication ID
     * @param string|null $eventSourceUrl URL where the event happened
     * @return void
     */
    public function sendEvent(string $eventName, array $customData = [], ?string $eventId = null, ?string $eventSourceUrl = null)
    {
        try {
            $settings = Cache::remember('site_settings', 60*24, function () {
                return Setting::pluck('value', 'key')->toArray();
            });

            $pixelId = $settings['fb_pixel_id'] ?? null;
            $accessToken = $settings['capi_token'] ?? null;

            if (empty($pixelId) || empty($accessToken)) {
                return; // CAPI is not configured
            }

            // Client info
            $clientIp = request()->ip();
            $userAgent = request()->userAgent();

            $eventData = [
                'event_name' => $eventName,
                'event_time' => time(),
                'action_source' => 'website',
                'user_data' => [
                    'client_ip_address' => $clientIp,
                    'client_user_agent' => $userAgent,
                ]
            ];

            // Include user info if authenticated
            if (auth()->check()) {
                $user = auth()->user();
                if ($user->email) {
                    $eventData['user_data']['em'] = hash('sha256', strtolower(trim($user->email)));
                }
                if ($user->phone) {
                    $eventData['user_data']['ph'] = hash('sha256', ltrim(preg_replace('/[^0-9]/', '', $user->phone), '0'));
                }
            }

            if (!empty($customData)) {
                $eventData['custom_data'] = $customData;
            }

            if ($eventSourceUrl) {
                $eventData['event_source_url'] = $eventSourceUrl;
            } elseif (request()->url()) {
                $eventData['event_source_url'] = request()->url();
            }

            if ($eventId) {
                $eventData['event_id'] = $eventId;
            }

            $response = Http::post("https://graph.facebook.com/v19.0/{$pixelId}/events", [
                'data' => [$eventData],
                'access_token' => $accessToken,
            ]);

            if ($response->failed()) {
                Log::error('Facebook CAPI Error', [
                    'response' => $response->json(),
                    'event' => $eventName
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Facebook CAPI Exception: ' . $e->getMessage());
        }
    }
}
