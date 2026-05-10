<?php

namespace App\ViewComposers;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class SettingsViewComposer
{
    protected const CACHE_KEY = 'site_settings_data';

    /**
     * Bind settings ke semua view
     */
    public function compose(View $view): void
    {
        $data = Cache::remember(self::CACHE_KEY, now()->addDay(), function () {
            $setting = Setting::first();
            return $setting ? $setting->toArray() : null;
        });

        $settings = $data ? new Setting($data) : $this->getDefaultSettings();

        $view->with('settings', $settings);
    }

    /**
     * Default settings jika belum ada data
     */
    protected function getDefaultSettings(): Setting
    {
        return new Setting([
            'site_name' => 'Kementerian Agama Kabupaten Nganjuk',
            'site_url' => url('/'),
            'meta_description' => 'Portal Resmi Kantor Kementerian Agama Kabupaten Nganjuk',
            'meta_keywords' => 'kemenag, nganjuk, agama, islam, hindu, buddha, kristen, katolik',
            'footer_description' => 'Portal resmi Kantor Kementerian Agama Kabupaten Nganjuk. Menyajikan informasi seputar kegiatan, layanan, dan berita keagamaan di Kabupaten Nganjuk.',
        ]);
    }
}
