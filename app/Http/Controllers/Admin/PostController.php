<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\Tag;
use App\Services\ImageProcessor;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    private ImageProcessor $imageProcessor;

    public function __construct(ImageProcessor $imageProcessor)
    {
        $this->imageProcessor = $imageProcessor;
    }

    /**
     * Display a listing of posts.
     */
    public function index(Request $request): View
    {
        $user = auth()->user();
        $query = Post::with(['category', 'author', 'tags'])
            ->orderBy('published_at', 'desc');

        // Non-admin can only see their own posts
        if ($user->role_name !== 'admin') {
            $query->where('author_id', $user->id);
        }

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

        // Filter by author (admin only)
        if ($user->role_name === 'admin' && $request->has('author') && $request->author) {
            $query->where('author_id', $request->author);
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
        $user = auth()->user();
        $isAdmin = $user->role_name === 'admin';

        $categories = PostCategory::where('is_active', true)
            ->orderBy('name')
            ->get();

        $tags = Tag::orderBy('name')->get();

        return view('admin.posts.create', compact('categories', 'tags', 'isAdmin'));
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
            // Image variants (stored as JSON)
            'thumbnail_webp' => 'nullable|string',
            'thumbnail_avif' => 'nullable|string',
            'thumbnail_width' => 'nullable|integer',
            'thumbnail_height' => 'nullable|integer',
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

        // Handle thumbnail upload with ImageProcessor (WebP + AVIF variants)
        if ($request->hasFile('thumbnail')) {
            try {
                $result = $this->imageProcessor->processAndStore($request->file('thumbnail'), 'posts');
                $validated['thumbnail'] = $result['filename'];
                $validated['thumbnail_webp'] = $result['webp_path'];
                $validated['thumbnail_width'] = $result['original_width'];
                $validated['thumbnail_height'] = $result['original_height'];
            } catch (\Exception $e) {
                return redirect()->back()->withInput()->with('error', 'Gagal memproses gambar: ' . $e->getMessage());
            }
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

        // Non-admin can only edit their own posts
        $user = auth()->user();
        $isAdmin = $user->role_name === 'admin';
        
        if (!$isAdmin && $post->author_id !== $user->id) {
            return redirect()->route('admin.posts.index')->with('error', 'Anda tidak memiliki akses ke post ini.');
        }

        $categories = PostCategory::where('is_active', true)
            ->orderBy('name')
            ->get();
        $tags = Tag::orderBy('name')->get();

        return view('admin.posts.edit', compact('post', 'categories', 'tags', 'isAdmin'));
    }

    /**
     * Update a post.
     */
    public function update(Request $request, int $id)
    {
        $post = Post::findOrFail($id);

        // Non-admin can only update their own posts
        $user = auth()->user();
        if ($user->role_name !== 'admin' && $post->author_id !== $user->id) {
            return redirect()->route('admin.posts.index')->with('error', 'Anda tidak memiliki akses ke post ini.');
        }

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
            // Image variants (stored as JSON)
            'thumbnail_webp' => 'nullable|string',
            'thumbnail_avif' => 'nullable|string',
            'thumbnail_width' => 'nullable|integer',
            'thumbnail_height' => 'nullable|integer',
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

        // Handle thumbnail upload with ImageProcessor (WebP + AVIF variants)
        if ($request->hasFile('thumbnail')) {
            try {
                // Delete old image variants
                if ($post->thumbnail) {
                    $this->imageProcessor->delete($post->thumbnail, 'posts');
                }

                $result = $this->imageProcessor->processAndStore($request->file('thumbnail'), 'posts');
                $validated['thumbnail'] = $result['filename'];
                $validated['thumbnail_webp'] = $result['webp_path'];
                $validated['thumbnail_width'] = $result['original_width'];
                $validated['thumbnail_height'] = $result['original_height'];
            } catch (\Exception $e) {
                dd($e->getMessage());
                return redirect()->back()->withInput()->with('error', 'Gagal memproses gambar: ' . $e->getMessage());
            }
        }

        // Handle thumbnail removal
        if ($request->has('remove_thumbnail') && $request->remove_thumbnail) {
            if ($post->thumbnail) {
                $this->imageProcessor->delete($post->thumbnail, 'posts');
            }
            $validated['thumbnail'] = null;
            $validated['thumbnail_webp'] = null;
            $validated['thumbnail_avif'] = null;
            $validated['thumbnail_width'] = null;
            $validated['thumbnail_height'] = null;
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

        // Non-admin can only delete their own posts
        $user = auth()->user();
        if ($user->role_name !== 'admin' && $post->author_id !== $user->id) {
            return redirect()->route('admin.posts.index')->with('error', 'Anda tidak memiliki akses ke post ini.');
        }

        // Delete image variants
        if ($post->thumbnail) {
            $this->imageProcessor->delete($post->thumbnail, 'posts');
        }

        // Detach tags
        $post->tags()->detach();

        $post->delete();

        return redirect()
            ->route('admin.posts.index')
            ->with('success', 'Post berhasil dihapus.');
    }
}
