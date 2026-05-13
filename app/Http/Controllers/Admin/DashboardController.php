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
        $user = auth()->user();
        $isAdmin = $user->role_name === 'admin';
        
        // Get stats based on role
        if ($isAdmin) {
            // Admin sees all stats
            $postCount = Post::count();
            $categoryCount = PostCategory::count();
            $pageCount = Page::count();
            $agendaCount = Agenda::count();
            $downloadCount = Download::count();
            $externalLinkCount = ExternalLink::count();
            $userCount = User::count();
            
            // Recent posts - all
            $recentPosts = Post::with(['category', 'author'])
                ->latest()
                ->take(5)
                ->get();
            
            // Recent agendas - all
            $recentAgendas = Agenda::latest()
                ->take(5)
                ->get();
        } else {
            // Non-admin sees only their posts
            $postCount = Post::where('author_id', $user->id)->count();
            $categoryCount = PostCategory::where('is_active', true)->count();
            $pageCount = 0;
            $agendaCount = 0;
            $downloadCount = 0;
            $externalLinkCount = 0;
            $userCount = 0;
            
            // Recent posts - only theirs
            $recentPosts = Post::with(['category', 'author'])
                ->where('author_id', $user->id)
                ->latest()
                ->take(5)
                ->get();
            
            $recentAgendas = collect();
        }
        
        // Counts for dashboard boxes
        $publishedCount = Post::where('status', 'published')
            ->when(!$isAdmin, fn($q) => $q->where('author_id', $user->id))
            ->count();
        
        $draftCount = Post::where('status', 'draft')
            ->when(!$isAdmin, fn($q) => $q->where('author_id', $user->id))
            ->count();

        return view('admin.dashboard', compact(
            'postCount', 'categoryCount', 'pageCount', 'agendaCount', 
            'downloadCount', 'externalLinkCount', 'userCount',
            'publishedCount', 'draftCount',
            'recentPosts', 'recentAgendas', 'isAdmin'
        ));
    }
}
