<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;

class AccessibilityController extends Controller
{
    /**
     * Display the accessibility statement page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $settings = Setting::first();
        
        return view('accessibility', [
            'settings' => $settings,
        ]);
    }
}
