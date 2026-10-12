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
    public $userNameHash;
    public $cityHash;
    public $fbp;
    public $fbc;
    public $eventId;
    public $eventTime;

    /**
     * Create a new job instance.
     *
     * @param string $eventName
     * @param array $customData
     * @param array $userData Optional customer-specific data (for delayed/offline events)
     * @param string|null $eventId
     * @param int|null $eventTime
     */
    public function __construct(string $eventName, array $customData = [], array $userData = [], ?string $eventId = null, ?int $eventTime = null)
    {
        $this->eventName = $eventName;
        $this->customData = $customData;
        $this->eventId = $eventId ?? ($customData['event_id'] ?? ($customData['order_id'] ?? null));
        $this->eventTime = $eventTime ?? time();

        // If explicit customer data is passed (e.g. from Order model upon Admin Confirm)
        if (!empty($userData)) {
            $this->clientIp = $userData['ip_address'] ?? (request() ? request()->ip() : null);
            $this->userAgent = $userData['user_agent'] ?? (request() ? request()->userAgent() : null);
            $this->eventSourceUrl = $userData['event_source_url'] ?? (request() ? request()->url() : config('app.url'));

            if (!empty($userData['email'])) {
                $this->userEmailHash = hash('sha256', strtolower(trim($userData['email'])));
            }
            if (!empty($userData['phone'])) {
                $rawPhone = preg_replace('/[^0-9]/', '', $userData['phone']);
                if (str_starts_with($rawPhone, '01') && strlen($rawPhone) === 11) {
                    $rawPhone = '88' . $rawPhone;
                }
                $this->userPhoneHash = hash('sha256', $rawPhone);
            }
            if (!empty($userData['name'])) {
                $parts = explode(' ', trim($userData['name']), 2);
                $this->userNameHash = hash('sha256', strtolower($parts[0]));
            }
            if (!empty($userData['city'])) {
                $this->cityHash = hash('sha256', strtolower(str_replace(' ', '', trim($userData['city']))));
            }
            $this->fbp = $this->resolveFbp($userData['fbp'] ?? null);
            $this->fbc = $this->resolveFbc($userData['fbc'] ?? null);
        } else {
            // Live web visitor request fallback
            $this->eventSourceUrl = request() ? request()->url() : config('app.url');
            $this->clientIp = request() ? request()->ip() : null;
            $this->userAgent = request() ? request()->userAgent() : null;

            if (auth()->check()) {
                $user = auth()->user();
                if ($user->email) {
                    $this->userEmailHash = hash('sha256', strtolower(trim($user->email)));
                }
                if ($user->phone) {
                    $rawPhone = preg_replace('/[^0-9]/', '', $user->phone);
                    if (str_starts_with($rawPhone, '01') && strlen($rawPhone) === 11) {
                        $rawPhone = '88' . $rawPhone;
                    }
                    $this->userPhoneHash = hash('sha256', $rawPhone);
                }
                if ($user->name) {
                    $parts = explode(' ', trim($user->name), 2);
                    $this->userNameHash = hash('sha256', strtolower($parts[0]));
                }
            }
            $this->fbp = $this->resolveFbp();
            $this->fbc = $this->resolveFbc();
        }
    }

    /**
     * Resolve valid Meta Click ID (_fbc).
     * Format: fb.1.{creation_time_ms}.{fbclid}
     * Must be unhashed.
     */
    protected function resolveFbc(?string $explicit = null): ?string
    {
        if (!empty($explicit)) {
            $trimmed = trim($explicit);
            if (str_starts_with($trimmed, 'fb.')) {
                return $trimmed;
            }
            return 'fb.1.' . ((int) round(microtime(true) * 1000)) . '.' . $trimmed;
        }

        if (request()) {
            $cookieFbc = request()->cookie('_fbc');
            if (!empty($cookieFbc) && str_starts_with($cookieFbc, 'fb.')) {
                return $cookieFbc;
            }
            $inputFbc = request()->input('fbc') ?? request()->header('X-FBC');
            if (!empty($inputFbc) && str_starts_with($inputFbc, 'fb.')) {
                return $inputFbc;
            }
        }

        if (session() && session()->has('fbc')) {
            $sessionFbc = session('fbc');
            if (!empty($sessionFbc) && str_starts_with($sessionFbc, 'fb.')) {
                return $sessionFbc;
            }
        }

        $fbclid = null;
        if (request()) {
            $fbclid = request()->input('fbclid') ?? request()->query('fbclid');
        }
        if (empty($fbclid) && session()) {
            $fbclid = session('fbclid');
        }

        if (!empty($fbclid)) {
            $formattedFbc = 'fb.1.' . ((int) round(microtime(true) * 1000)) . '.' . trim($fbclid);
            if (session()) {
                session(['fbc' => $formattedFbc]);
            }
            return $formattedFbc;
        }

        return null;
    }

    /**
     * Resolve valid Meta Browser ID (_fbp).
     * Must be unhashed.
     */
    protected function resolveFbp(?string $explicit = null): ?string
    {
        if (!empty($explicit)) {
            return trim($explicit);
        }

        if (request()) {
            $cookieFbp = request()->cookie('_fbp');
            if (!empty($cookieFbp)) {
                return $cookieFbp;
            }
            $inputFbp = request()->input('fbp') ?? request()->header('X-FBP');
            if (!empty($inputFbp)) {
                return $inputFbp;
            }
        }

        if (session() && session()->has('fbp')) {
            return session('fbp');
        }

        return null;
    }

    /**
     * Send event synchronously with exception safety.
     */
    public static function send(string $eventName, array $customData = [], array $userData = [], ?string $eventId = null, ?int $eventTime = null)
    {
        try {
            return self::dispatchSync($eventName, $customData, $userData, $eventId, $eventTime);
        } catch (\Throwable $e) {
            Log::error('SendFacebookCapiEvent error: ' . $e->getMessage());
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
                Log::warning('Facebook CAPI Skipped: fb_pixel_id or capi_token is missing in site settings.');
                return;
            }

            $userDataPayload = [];
            if ($this->clientIp) {
                $userDataPayload['client_ip_address'] = $this->clientIp;
            }
            if ($this->userAgent) {
                $userDataPayload['client_user_agent'] = $this->userAgent;
            }
            if ($this->userEmailHash) {
                $userDataPayload['em'] = [$this->userEmailHash];
            }
            if ($this->userPhoneHash) {
                $userDataPayload['ph'] = [$this->userPhoneHash];
            }
            if ($this->userNameHash) {
                $userDataPayload['fn'] = [$this->userNameHash];
            }
            if ($this->cityHash) {
                $userDataPayload['ct'] = [$this->cityHash];
            }
            $userDataPayload['country'] = [hash('sha256', 'bd')];

            if ($this->fbp) {
                $userDataPayload['fbp'] = $this->fbp;
            }
            if ($this->fbc) {
                $userDataPayload['fbc'] = $this->fbc;
            }

            $eventData = [
                'event_name' => $this->eventName,
                'event_time' => $this->eventTime,
                'action_source' => 'website',
                'user_data' => $userDataPayload,
            ];

            if ($this->eventId) {
                $eventData['event_id'] = $this->eventId;
            }

            if (!empty($this->customData)) {
                $customPayload = $this->customData;
                unset($customPayload['event_id']);
                if (!empty($customPayload)) {
                    $eventData['custom_data'] = $customPayload;
                }
            }

            if ($this->eventSourceUrl) {
                $eventData['event_source_url'] = $this->eventSourceUrl;
            }

            $postPayload = [
                'data' => [$eventData],
                'access_token' => $accessToken,
            ];

            if (!empty($settings['capi_test_code'])) {
                $postPayload['test_event_code'] = trim($settings['capi_test_code']);
            }

            $response = Http::timeout(5)->post("https://graph.facebook.com/v19.0/{$pixelId}/events", $postPayload);

            if ($response->failed()) {
                Log::error('Facebook CAPI Error', [
                    'response' => $response->json(),
                    'event' => $this->eventName,
                    'event_id' => $this->eventId
                ]);
            } else {
                Log::info('Facebook CAPI Event Sent Successfully', [
                    'event' => $this->eventName,
                    'event_id' => $this->eventId,
                    'response' => $response->json()
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Facebook CAPI Exception: ' . $e->getMessage());
        }
    }
}
