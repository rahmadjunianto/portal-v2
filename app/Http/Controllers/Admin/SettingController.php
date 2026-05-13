<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class SettingController extends Controller
{
    /**
     * Display a listing of settings.
     */
    public function index(): View
    {
        $setting = Setting::getOrCreate();
        return view('admin.settings.index', compact('setting'));
    }

    /**
     * Update the specified settings in storage.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'site_name' => 'nullable|string|max:150',
            'site_url' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:100',
            'phone' => 'nullable|string|max:20',
            'logo' => 'nullable|string',
            'favicon' => 'nullable|string',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string|max:255',
            'facebook_url' => 'nullable|string',
            'instagram_url' => 'nullable|string|max:255',
            'youtube_url' => 'nullable|string|max:255',
            'twitter_url' => 'nullable|string|max:255',
            'maps_embed' => 'nullable|string',
            'footer_description' => 'nullable|string',
        ]);

        $setting = Setting::getOrCreate();
        $setting->fill($validated);
        $setting->save();

        return redirect()
            ->route('admin.settings.index')
            ->with('success', 'Pengaturan berhasil disimpan.');
    }
}
