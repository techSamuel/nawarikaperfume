<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Models\Setting;

class SendFacebookCapiEvent implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $eventName;
    public $customData;
    public $eventSourceUrl;
    public $clientIp;
    public $userAgent;
    public $userEmailHash;
    public $userPhoneHash;

    /**
     * Create a new job instance.
     */
    public function __construct(string $eventName, array $customData = [])
    {
        $this->eventName = $eventName;
        $this->customData = $customData;
        $this->eventSourceUrl = request()->url();
        $this->clientIp = request()->ip();
        $this->userAgent = request()->userAgent();

        if (auth()->check()) {
            $user = auth()->user();
            if ($user->email) {
                $this->userEmailHash = hash('sha256', strtolower(trim($user->email)));
            }
            if ($user->phone) {
                $this->userPhoneHash = hash('sha256', ltrim(preg_replace('/[^0-9]/', '', $user->phone), '0'));
            }
        }
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $settings = Cache::remember('site_settings', 60*24, function () {
                return Setting::pluck('value', 'key')->toArray();
            });

            $pixelId = $settings['fb_pixel_id'] ?? null;
            $accessToken = $settings['capi_token'] ?? null;

            if (empty($pixelId) || empty($accessToken)) {
                return;
            }

            $eventData = [
                'event_name' => $this->eventName,
                'event_time' => time(),
                'action_source' => 'website',
                'user_data' => [
                    'client_ip_address' => $this->clientIp,
                    'client_user_agent' => $this->userAgent,
                ]
            ];

            if ($this->userEmailHash) {
                $eventData['user_data']['em'] = $this->userEmailHash;
            }
            if ($this->userPhoneHash) {
                $eventData['user_data']['ph'] = $this->userPhoneHash;
            }

            if (!empty($this->customData)) {
                $eventData['custom_data'] = $this->customData;
            }

            if ($this->eventSourceUrl) {
                $eventData['event_source_url'] = $this->eventSourceUrl;
            }

            $response = Http::post("https://graph.facebook.com/v19.0/{$pixelId}/events", [
                'data' => [$eventData],
                'access_token' => $accessToken,
            ]);

            if ($response->failed()) {
                Log::error('Facebook CAPI Error', [
                    'response' => $response->json(),
                    'event' => $this->eventName
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Facebook CAPI Exception: ' . $e->getMessage());
        }
    }
}
