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

        // Get or create setting without relying on cached instance
        $setting = Setting::first();
        
        if (!$setting) {
            // Create new setting with defaults
            $setting = Setting::create([
                'site_name' => $validated['site_name'] ?? 'Portal Kemenag Nganjuk',
                'site_url' => $validated['site_url'] ?? url('/'),
                'email' => $validated['email'] ?? '',
                'phone' => $validated['phone'] ?? '',
                'logo' => $validated['logo'] ?? '',
                'favicon' => $validated['favicon'] ?? '',
                'meta_description' => $validated['meta_description'] ?? '',
                'meta_keywords' => $validated['meta_keywords'] ?? '',
                'maps_embed' => $validated['maps_embed'] ?? '',
                'footer_description' => $validated['footer_description'] ?? '',
                'facebook_url' => $validated['facebook_url'] ?? '',
                'instagram_url' => $validated['instagram_url'] ?? '',
                'youtube_url' => $validated['youtube_url'] ?? '',
                'twitter_url' => $validated['twitter_url'] ?? '',
            ]);
        } else {
            // Update existing setting
            $setting->fill($validated);
            $setting->save();
        }

        return redirect()
            ->route('admin.settings.index')
            ->with('success', 'Pengaturan berhasil disimpan.');
    }
}
