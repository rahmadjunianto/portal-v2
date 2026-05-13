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
        $settings = Setting::getOrCreate();

        $view->with('settings', $settings);
    }
}
