<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Display the specified page.
     */
    public function show($slug)
    {
        $page = Page::where('slug', $slug)
            ->whereNotNull('published_at')
            ->firstOrFail();

        return view('page-detail', compact('page'));
    }
}
