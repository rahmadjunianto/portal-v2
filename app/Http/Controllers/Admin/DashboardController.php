<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agenda;
use App\Models\Download;
use App\Models\ExternalLink;
use App\Models\KnowledgeBank;
use App\Models\Page;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\Service;
use App\Models\UnknownQuestion;
use App\Models\User;
use Carbon\Carbon;
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
                
            // AI Stats
            $knowledgeCount = KnowledgeBank::active()->count();
            $serviceCount = Service::active()->count();
            $unknownCount = UnknownQuestion::unprocessed()->count();
            
            // Questions today
            $todayStart = Carbon::today();
            $questionsToday = UnknownQuestion::where('created_at', '>=', $todayStart)->count();
            
            // Accuracy calculation (based on resolved vs unresolved)
            $totalUnknown = UnknownQuestion::count();
            $resolvedUnknown = UnknownQuestion::where('status', 'resolved')->count();
            $accuracy = $totalUnknown > 0 ? round(($resolvedUnknown / $totalUnknown) * 100) : 100;
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
            
            // AI Stats - hide for non-admin
            $knowledgeCount = 0;
            $serviceCount = 0;
            $unknownCount = 0;
            $questionsToday = 0;
            $accuracy = 0;
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
            'recentPosts', 'recentAgendas', 'isAdmin',
            'knowledgeCount', 'serviceCount', 'unknownCount', 'questionsToday', 'accuracy'
        ));
    }
}
