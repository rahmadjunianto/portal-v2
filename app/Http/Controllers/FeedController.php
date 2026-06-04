<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Setting;
use Illuminate\Http\Response;
use Illuminate\View\View;

class FeedController extends Controller
{
    /**
     * Number of items to include in the feed.
     */
    protected int $itemsPerPage = 50;

    /**
     * Display RSS 2.0 feed.
     */
    public function rss(): Response
    {
        $settings = Setting::getInstance();
        
        $posts = Post::published()
            ->with(['category', 'author'])
            ->orderBy('published_at', 'desc')
            ->take($this->itemsPerPage)
            ->get();

        $content = view('feed.rss', [
            'settings' => $settings,
            'posts' => $posts,
        ])->render();

        return response($content, 200, [
            'Content-Type' => 'application/rss+xml; charset=UTF-8',
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }

    /**
     * Display Atom 1.0 feed.
     */
    public function atom(): Response
    {
        $settings = Setting::getInstance();
        
        $posts = Post::published()
            ->with(['category', 'author'])
            ->orderBy('published_at', 'desc')
            ->take($this->itemsPerPage)
            ->get();

        $content = view('feed.atom', [
            'settings' => $settings,
            'posts' => $posts,
        ])->render();

        return response($content, 200, [
            'Content-Type' => 'application/atom+xml; charset=UTF-8',
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }
}
