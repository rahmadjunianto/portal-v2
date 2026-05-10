<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\Setting;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PostController extends Controller
{
    /**
     * Display a listing of posts.
     */
    public function index(Request $request): View
    {
        $settings = Setting::getInstance();

        // Get header menu items
        $headerMenuItems = MenuItem::query()
            ->where('is_active', true)
            ->whereNull('parent_id')
            ->orderBy('sort_order', 'asc')
            ->with(['children' => function ($query) {
                $query->where('is_active', true)->orderBy('sort_order', 'asc');
            }])
            ->get();

        // Get categories for filter
        $categories = PostCategory::orderBy('name', 'asc')->get();

        // Get tags for filter
        $tags = Tag::orderBy('name', 'asc')->get();

        // Build query
        $query = Post::published()
            ->with(['category', 'author']);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('subtitle', 'like', "%{$search}%");
            });
        }

        // Category filter
        if ($request->filled('category')) {
            $categorySlug = $request->category;
            $query->whereHas('category', function ($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            });
        }

        // Tag filter
        if ($request->filled('tag')) {
            $tagSlug = $request->tag;
            $query->whereHas('tags', function ($q) use ($tagSlug) {
                $q->where('slug', $tagSlug);
            });
        }

        // Paginate results
        $posts = $query->orderBy('published_at', 'desc')
            ->paginate(12)
            ->withQueryString();

        return view('berita', [
            'settings' => $settings,
            'headerMenuItems' => $headerMenuItems,
            'categories' => $categories,
            'tags' => $tags,
            'posts' => $posts,
        ]);
    }

    /**
     * Display the specified post.
     */
    public function show(string $slug): View
    {
        $settings = Setting::getInstance();

        // Get header menu items
        $headerMenuItems = MenuItem::query()
            ->where('is_active', true)
            ->whereNull('parent_id')
            ->orderBy('sort_order', 'asc')
            ->with(['children' => function ($query) {
                $query->where('is_active', true)->orderBy('sort_order', 'asc');
            }])
            ->get();

        // Get post by slug with relationships
        $post = Post::with(['category', 'author', 'tags'])
            ->where('slug', $slug)
            ->first();

        if (!$post) {
            abort(404);
        }

        // Increment view count
        $post->incrementViews();

        // Share data with all views via View::share
        view()->share('settings', $settings);
        view()->share('headerMenuItems', $headerMenuItems);

        return view('post-detail', [
            'post' => $post,
        ]);
    }
}
