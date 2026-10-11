<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

use App\Services\GeminiService;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->pluck('value', 'key')->toArray();
        return view('admin.settings.index', compact('settings'));
    }

    public function store(Request $request)
    {
        $currentTab = $request->input('current_tab', 'general');
        $data = $request->except(['_token', 'current_tab']);

        // Handle all file uploads dynamically
        foreach ($request->allFiles() as $key => $file) {
            $path = $file->store('settings', 'public');
            $data[$key] = $path;
        }

        foreach ($data as $key => $value) {
            if ($value instanceof \Illuminate\Http\UploadedFile) {
                continue;
            }
            
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        \Illuminate\Support\Facades\Cache::forget('site_settings');

        return redirect()->route('admin.settings.index', ['tab' => $currentTab])->with('success', 'Settings updated successfully.');
    }

    public function testGemini(Request $request, GeminiService $gemini)
    {
        $apiKey = $request->input('gemini_api_key');
        $model = $request->input('gemini_model');

        $result = $gemini->testConnection($apiKey, $model);

        return response()->json($result);
    }

    public function testCapi(Request $request)
    {
        $pixelId = trim($request->input('fb_pixel_id') ?: (Setting::where('key', 'fb_pixel_id')->value('value') ?? ''));
        $accessToken = trim($request->input('capi_token') ?: (Setting::where('key', 'capi_token')->value('value') ?? ''));
        $testEventCode = trim($request->input('capi_test_code') ?: (Setting::where('key', 'capi_test_code')->value('value') ?? ''));

        if (empty($pixelId) || empty($accessToken)) {
            return response()->json([
                'success' => false,
                'message' => 'Both Facebook Pixel ID and Conversions API (CAPI) Token are required to test.'
            ]);
        }

        $eventData = [
            'event_name' => 'TestEvent',
            'event_time' => time(),
            'action_source' => 'website',
            'event_source_url' => config('app.url'),
            'user_data' => [
                'client_ip_address' => $request->ip(),
                'client_user_agent' => $request->userAgent(),
                'country' => [hash('sha256', 'bd')],
                'em' => [hash('sha256', 'admin-test@example.com')],
            ],
            'custom_data' => [
                'status' => 'ok',
                'description' => 'Admin panel CAPI diagnostic ping',
            ]
        ];

        $payload = [
            'data' => [$eventData],
            'access_token' => $accessToken,
        ];

        if (!empty($testEventCode)) {
            $payload['test_event_code'] = $testEventCode;
        }

        try {
            $response = \Illuminate\Support\Facades\Http::timeout(6)
                ->post("https://graph.facebook.com/v19.0/{$pixelId}/events", $payload);

            $data = $response->json();

            if ($response->successful()) {
                $received = $data['events_received'] ?? 1;
                return response()->json([
                    'success' => true,
                    'message' => "Success! Meta Graph API accepted the event ({$received} event received).",
                    'events_received' => $received,
                    'fbtrace_id' => $data['fbtrace_id'] ?? null,
                    'details' => $data,
                ]);
            } else {
                $errorMessage = $data['error']['message'] ?? 'Meta API error';
                return response()->json([
                    'success' => false,
                    'message' => 'Meta API Error: ' . $errorMessage,
                    'details' => $data,
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Network/Connection error: ' . $e->getMessage()
            ]);
        }
    }
}
