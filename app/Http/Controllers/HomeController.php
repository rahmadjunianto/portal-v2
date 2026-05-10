<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use App\Models\Download;
use App\Models\ExternalLink;
use App\Models\MenuItem;
use App\Models\Page;
use App\Models\Post;
use App\Models\Setting;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Display the homepage.
     */
    public function index(): View
    {
        // Get singleton settings
        $settings = Setting::getInstance();

        // Get header menu items (active only, root level only)
        $headerMenuItems = MenuItem::query()
            ->where('is_active', true)
            ->whereNull('parent_id')
            ->orderBy('sort_order', 'asc')
            ->with(['children' => function ($query) {
                $query->where('is_active', true)->orderBy('sort_order', 'asc');
            }])
            ->get();

        // Footer uses static links (no menu items)
        $footerMenuItems = collect();

        // Get headline/featured posts (for hero slider) with eager loading
        $headlinePosts = Post::published()
            ->where(function ($query) {
                $query->where('is_headline', true)
                    ->orWhere('is_featured', true);
            })
            ->with(['category', 'author'])
            ->orderBy('published_at', 'desc')
            ->limit(5)
            ->get();

        // Get latest posts (recent news) with eager loading
        $latestPosts = Post::published()
            ->with(['category', 'author'])
            ->orderBy('published_at', 'desc')
            ->limit(8)
            ->get();

        // Get upcoming agendas
        $upcomingAgendas = Agenda::upcoming()
            ->orderBy('start_date', 'asc')
            ->limit(4)
            ->get();

        // Get latest downloads
        $latestDownloads = Download::orderBy('published_at', 'desc')
            ->limit(6)
            ->get();

        // Get active external links (public services)
        $externalLinks = ExternalLink::active()
            ->ordered()
            ->limit(12)
            ->get();

        // Get profile page for about section
        $profilePage = Page::where('page_type', 'profil')
            ->orderBy('published_at', 'desc')
            ->first();

        return view('home', [
            'settings' => $settings,
            'headerMenuItems' => $headerMenuItems,
            'footerMenuItems' => $footerMenuItems,
            'headlinePosts' => $headlinePosts,
            'latestPosts' => $latestPosts,
            'upcomingAgendas' => $upcomingAgendas,
            'latestDownloads' => $latestDownloads,
            'externalLinks' => $externalLinks,
            'profilePage' => $profilePage,
        ]);
    }
}
