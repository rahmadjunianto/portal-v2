<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KnowledgeBank;
use Illuminate\Http\Request;

class KnowledgeBankController extends Controller
{
    /**
     * Display a listing of knowledge bank entries.
     */
    public function index(Request $request)
    {
        $query = KnowledgeBank::query();

        // Filter by category
        if ($request->category) {
            $query->where('category', $request->category);
        }

        // Search
        if ($request->search) {
            $query->search($request->search);
        }

        // Filter active/inactive
        if ($request->filled('active')) {
            $active = $request->active == '1' ? true : false;
            $query->where('is_active', $active);
        }

        $entries = $query->orderByDesc('priority')->orderByDesc('created_at')->paginate(20);
        $categories = KnowledgeBank::getCategories();

        return view('admin.knowledge-bank.index', compact('entries', 'categories'));
    }

    /**
     * Show the form for creating a new entry.
     */
    public function create()
    {
        $categories = KnowledgeBank::getCategories();
        return view('admin.knowledge-bank.create', compact('categories'));
    }

    /**
     * Store a newly created entry.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'question' => 'required|string|max:500',
            'answer' => 'required|string',
            'category' => 'nullable|string',
            'tags' => 'nullable|string|max:255',
            'priority' => 'nullable|integer|min:0|max:100',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['priority'] = $validated['priority'] ?? 0;

        KnowledgeBank::create($validated);

        return redirect()
            ->route('admin.knowledge-bank.index')
            ->with('success', 'Data berhasil ditambahkan.');
    }

    /**
     * Show the form for editing an entry.
     */
    public function edit(KnowledgeBank $knowledgeBank)
    {
        $categories = KnowledgeBank::getCategories();
        return view('admin.knowledge-bank.edit', compact('knowledgeBank', 'categories'));
    }

    /**
     * Update an entry.
     */
    public function update(Request $request, KnowledgeBank $knowledgeBank)
    {
        $validated = $request->validate([
            'question' => 'required|string|max:500',
            'answer' => 'required|string',
            'category' => 'nullable|string',
            'tags' => 'nullable|string|max:255',
            'priority' => 'nullable|integer|min:0|max:100',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['priority'] = $validated['priority'] ?? 0;

        $knowledgeBank->update($validated);

        return redirect()
            ->route('admin.knowledge-bank.index')
            ->with('success', 'Data berhasil diupdate.');
    }

    /**
     * Remove an entry.
     */
    public function destroy(KnowledgeBank $knowledgeBank)
    {
        $knowledgeBank->delete();

        return redirect()
            ->route('admin.knowledge-bank.index')
            ->with('success', 'Data berhasil dihapus.');
    }
}
