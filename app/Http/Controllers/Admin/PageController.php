<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class PageController extends Controller
{
    /**
     * Display a listing of pages.
     */
    public function index(Request $request): View
    {
        $query = Page::query()
            ->orderBy('created_at', 'desc');

        // Filter by page_type
        if ($request->has('page_type') && $request->page_type) {
            $query->where('page_type', $request->page_type);
        }

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $pages = $query->paginate(15)->withQueryString();

        return view('admin.pages.index', compact('pages'));
    }

    /**
     * Show the form for creating a new page.
     */
    public function create(): View
    {
        return view('admin.pages.create');
    }

    /**
     * Store a newly created page.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:pages,slug',
            'content' => 'required|string',
            'excerpt' => 'nullable|string',
            'page_type' => 'nullable|string|max:50',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:160',
            'meta_keywords' => 'nullable|string|max:255',
            'published_at' => 'nullable|date',
        ]);

        // Generate slug if empty
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        // Ensure slug is unique
        $originalSlug = $validated['slug'];
        $counter = 1;
        while (Page::where('slug', $validated['slug'])->exists()) {
            $validated['slug'] = $originalSlug . '-' . $counter;
            $counter++;
        }

        if (empty($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        // Create page
        Page::create($validated);

        return redirect()
            ->route('admin.pages.index')
            ->with('success', 'Halaman berhasil dibuat.');
    }

    /**
     * Show the form for editing a page.
     */
    public function edit(int $id): View
    {
        $page = Page::findOrFail($id);

        return view('admin.pages.edit', compact('page'));
    }

    /**
     * Update a page.
     */
    public function update(Request $request, int $id)
    {
        $page = Page::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:pages,slug,' . $id,
            'content' => 'required|string',
            'excerpt' => 'nullable|string',
            'page_type' => 'nullable|string|max:50',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:160',
            'meta_keywords' => 'nullable|string|max:255',
            'published_at' => 'nullable|date',
        ]);

        // Generate slug if empty
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        // Ensure slug is unique (excluding current page)
        $originalSlug = $validated['slug'];
        $counter = 1;
        while (Page::where('slug', $validated['slug'])->where('id', '!=', $id)->exists()) {
            $validated['slug'] = $originalSlug . '-' . $counter;
            $counter++;
        }

        // Update page
        $page->update($validated);

        return redirect()
            ->route('admin.pages.index')
            ->with('success', 'Halaman berhasil diperbarui.');
    }

    /**
     * Remove a page.
     */
    public function destroy(int $id)
    {
        $page = Page::findOrFail($id);
        $page->delete();

        return redirect()
            ->route('admin.pages.index')
            ->with('success', 'Halaman berhasil dihapus.');
    }
}
