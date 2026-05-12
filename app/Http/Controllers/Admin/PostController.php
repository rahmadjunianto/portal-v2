<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
     * Display a listing of posts.
     */
    public function index(Request $request): View
    {
        $query = Post::with(['category', 'author', 'tags'])
            ->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by category
        if ($request->has('category') && $request->category) {
            $query->where('category_id', $request->category);
        }

        // Filter by type
        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $posts = $query->paginate(15)->withQueryString();
        $categories = PostCategory::where('is_active', true)->orderBy('name')->get();

        return view('admin.posts.index', compact('posts', 'categories'));
    }

    /**
     * Show the form for creating a new post.
     */
    public function create(): View
    {
        $categories = PostCategory::where('is_active', true)
            ->orderBy('name')
            ->get();
        
        $tags = Tag::orderBy('name')->get();

        return view('admin.posts.create', compact('categories', 'tags'));
    }

    /**
     * Store a newly created post.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:posts,slug',
            'content' => 'required|string',
            'excerpt' => 'nullable|string',
            'category_id' => 'nullable|exists:post_categories,id',
            'type' => 'required|in:berita,pengumuman,lowongan',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'image_caption' => 'nullable|string|max:255',
            'status' => 'required|in:draft,published',
            'is_headline' => 'boolean',
            'is_featured' => 'boolean',
            'published_at' => 'nullable|date',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:160',
        ]);

        // Generate slug if empty
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        // Ensure slug is unique
        $originalSlug = $validated['slug'];
        $counter = 1;
        while (Post::where('slug', $validated['slug'])->exists()) {
            $validated['slug'] = $originalSlug . '-' . $counter;
            $counter++;
        }

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            $thumbnail = $request->file('thumbnail');
            $filename = 'posts/' . Str::uuid() . '.' . $thumbnail->getClientOriginalExtension();
            Storage::disk('public')->put($filename, file_get_contents($thumbnail));
            $validated['thumbnail'] = $filename;
        }

        // Set defaults
        $validated['author_id'] = auth()->id();
        $validated['status'] = $request->status ?? 'draft';
        $validated['is_headline'] = $request->boolean('is_headline');
        $validated['is_featured'] = $request->boolean('is_featured');
        
        if ($validated['status'] === 'published' && empty($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        // Create post
        $post = Post::create($validated);

        // Attach tags
        if ($request->has('tags')) {
            $post->tags()->attach($request->tags);
        }

        return redirect()
            ->route('admin.posts.index')
            ->with('success', 'Post berhasil dibuat.');
    }

    /**
     * Show the form for editing a post.
     */
    public function edit(int $id): View
    {
        $post = Post::with(['tags'])->findOrFail($id);
        $categories = PostCategory::where('is_active', true)
            ->orderBy('name')
            ->get();
        $tags = Tag::orderBy('name')->get();

        return view('admin.posts.edit', compact('post', 'categories', 'tags'));
    }

    /**
     * Update a post.
     */
    public function update(Request $request, int $id)
    {
        $post = Post::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:posts,slug,' . $id,
            'content' => 'required|string',
            'excerpt' => 'nullable|string',
            'category_id' => 'nullable|exists:post_categories,id',
            'type' => 'required|in:berita,pengumuman,lowongan',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'image_caption' => 'nullable|string|max:255',
            'status' => 'required|in:draft,published',
            'is_headline' => 'boolean',
            'is_featured' => 'boolean',
            'published_at' => 'nullable|date',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:160',
        ]);

        // Generate slug if empty
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        // Ensure slug is unique (excluding current post)
        $originalSlug = $validated['slug'];
        $counter = 1;
        while (Post::where('slug', $validated['slug'])->where('id', '!=', $id)->exists()) {
            $validated['slug'] = $originalSlug . '-' . $counter;
            $counter++;
        }

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            // Delete old thumbnail
            if ($post->thumbnail && Storage::disk('public')->exists($post->thumbnail)) {
                Storage::disk('public')->delete($post->thumbnail);
            }
            
            $thumbnail = $request->file('thumbnail');
            $filename = 'posts/' . Str::uuid() . '.' . $thumbnail->getClientOriginalExtension();
            Storage::disk('public')->put($filename, file_get_contents($thumbnail));
            $validated['thumbnail'] = $filename;
        }

        // Handle thumbnail removal
        if ($request->has('remove_thumbnail') && $request->remove_thumbnail) {
            if ($post->thumbnail && Storage::disk('public')->exists($post->thumbnail)) {
                Storage::disk('public')->delete($post->thumbnail);
            }
            $validated['thumbnail'] = null;
        }

        $validated['status'] = $request->status ?? 'draft';
        $validated['is_headline'] = $request->boolean('is_headline');
        $validated['is_featured'] = $request->boolean('is_featured');
        
        if ($validated['status'] === 'published' && empty($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        // Update post
        $post->update($validated);

        // Sync tags
        if ($request->has('tags')) {
            $post->tags()->sync($request->tags);
        } else {
            $post->tags()->detach();
        }

        return redirect()
            ->route('admin.posts.index')
            ->with('success', 'Post berhasil diperbarui.');
    }

    /**
     * Remove a post.
     */
    public function destroy(int $id)
    {
        $post = Post::findOrFail($id);

        // Delete thumbnail
        if ($post->thumbnail && Storage::disk('public')->exists($post->thumbnail)) {
            Storage::disk('public')->delete($post->thumbnail);
        }

        // Detach tags
        $post->tags()->detach();

        $post->delete();

        return redirect()
            ->route('admin.posts.index')
            ->with('success', 'Post berhasil dihapus.');
    }
}
