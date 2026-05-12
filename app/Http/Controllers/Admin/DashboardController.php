<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agenda;
use App\Models\Download;
use App\Models\ExternalLink;
use App\Models\Page;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display admin dashboard.
     */
    public function index(): View
    {
        $stats = [
            'posts' => Post::count(),
            'categories' => PostCategory::count(),
            'pages' => Page::count(),
            'agendas' => Agenda::count(),
            'downloads' => Download::count(),
            'external_links' => ExternalLink::count(),
            'users' => User::count(),
        ];

        // Recent posts
        $recentPosts = Post::with(['category', 'author'])
            ->latest()
            ->take(5)
            ->get();

        // Recent agendas
        $recentAgendas = Agenda::latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentPosts', 'recentAgendas'));
    }
}
