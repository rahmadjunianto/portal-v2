<?php

namespace App\ViewComposers;

use App\Models\Setting;
use Illuminate\View\View;

class SettingsViewComposer
{
    /**
     * Bind settings ke semua view
     */
    public function compose(View $view): void
    {
        // Ambil settings dari database, fallback ke default
        $settings = Setting::first() ?? $this->getDefaultSettings();

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
