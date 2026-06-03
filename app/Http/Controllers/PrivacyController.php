<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class PrivacyController extends Controller
{
    /**
     * Display the privacy policy page.
     */
    public function index()
    {
        $settings = Setting::first();

        return view('privacy', [
            'settings' => $settings,
            'meta_title' => 'Kebijakan Privasi - ' . ($settings->site_name ?? 'Kementerian Agama Kabupaten Nganjuk'),
        ]);
    }
}
