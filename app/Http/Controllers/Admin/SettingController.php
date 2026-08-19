<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

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
}
