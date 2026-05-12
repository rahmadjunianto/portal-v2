<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExternalLink;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ExternalLinkController extends Controller
{
    /**
     * Display a listing of external links.
     */
    public function index(): View
    {
        $links = ExternalLink::orderBy('sort_order')->paginate(15);
        return view('admin.external-links.index', compact('links'));
    }

    /**
     * Show the form for creating a new external link.
     */
    public function create(): View
    {
        return view('admin.external-links.create');
    }

    /**
     * Store a newly created external link.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|url|max:255',
            'icon' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer',
            'is_active' => 'boolean',
            'open_in_new_tab' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['open_in_new_tab'] = $request->boolean('open_in_new_tab', true);

        ExternalLink::create($validated);

        return redirect()
            ->route('admin.links.index')
            ->with('success', 'Link berhasil dibuat.');
    }

    /**
     * Show the form for editing an external link.
     */
    public function edit(int $id): View
    {
        $link = ExternalLink::findOrFail($id);
        return view('admin.external-links.edit', compact('link'));
    }

    /**
     * Update an external link.
     */
    public function update(Request $request, int $id)
    {
        $link = ExternalLink::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|url|max:255',
            'icon' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer',
            'is_active' => 'boolean',
            'open_in_new_tab' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['open_in_new_tab'] = $request->boolean('open_in_new_tab', true);

        $link->update($validated);

        return redirect()
            ->route('admin.links.index')
            ->with('success', 'Link berhasil diperbarui.');
    }

    /**
     * Remove an external link.
     */
    public function destroy(int $id)
    {
        $link = ExternalLink::findOrFail($id);
        $link->delete();

        return redirect()
            ->route('admin.links.index')
            ->with('success', 'Link berhasil dihapus.');
    }
}
