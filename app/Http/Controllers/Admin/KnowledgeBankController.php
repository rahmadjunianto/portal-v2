<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KnowledgeBank;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class KnowledgeBankController extends Controller
{
    /**
     * Display a listing of knowledge bank entries.
     */
    public function index(Request $request): View
    {
        $query = KnowledgeBank::with('service');

        // Filter by service
        if ($request->service) {
            $query->where('service_id', $request->service);
        }

        // Filter general FAQ (no service)
        if ($request->filled('general_faq')) {
            $query->whereNull('service_id');
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
        $services = Service::active()->with('category')->orderBy('name')->get();

        return view('admin.knowledge-bank.index', compact('entries', 'services'));
    }

    /**
     * Show the form for creating a new entry.
     */
    public function create(): View
    {
        $services = Service::active()
            ->with('category')
            ->orderBy('service_category_id')
            ->orderBy('name')
            ->get();
        return view('admin.knowledge-bank.create', compact('services'));
    }

    /**
     * Store a newly created entry.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'service_id' => 'nullable|exists:services,id',
            'question' => 'required|string|max:500',
            'answer' => 'nullable|required_without:service_id|string',
            'tags' => 'nullable|string|max:255',
            'priority' => 'nullable|integer|min:0|max:100',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['priority'] = $validated['priority'] ?? 0;

        // If service is selected, answer is not required (will be generated from service)
        if ($request->filled('service_id')) {
            unset($validated['answer']);
        }

        KnowledgeBank::create($validated);

        return redirect()
            ->route('admin.knowledge-bank.index')
            ->with('success', 'Data berhasil ditambahkan.');
    }

    /**
     * Show the form for editing an entry.
     */
    public function edit(KnowledgeBank $knowledgeBank): View
    {
        $services = Service::active()
            ->with('category')
            ->orderBy('service_category_id')
            ->orderBy('name')
            ->get();
        return view('admin.knowledge-bank.edit', compact('knowledgeBank', 'services'));
    }

    /**
     * Update an entry.
     */
    public function update(Request $request, KnowledgeBank $knowledgeBank): RedirectResponse
    {
        $validated = $request->validate([
            'service_id' => 'nullable|exists:services,id',
            'question' => 'required|string|max:500',
            'answer' => 'nullable|required_without:service_id|string',
            'tags' => 'nullable|string|max:255',
            'priority' => 'nullable|integer|min:0|max:100',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['priority'] = $validated['priority'] ?? 0;

        // If service is selected, clear answer (will be generated from service)
        if ($request->filled('service_id')) {
            unset($validated['answer']);
        }

        $knowledgeBank->update($validated);

        return redirect()
            ->route('admin.knowledge-bank.index')
            ->with('success', 'Data berhasil diupdate.');
    }

    /**
     * Remove an entry.
     */
    public function destroy(KnowledgeBank $knowledgeBank): RedirectResponse
    {
        $knowledgeBank->delete();

        return redirect()
            ->route('admin.knowledge-bank.index')
            ->with('success', 'Data berhasil dihapus.');
    }
}
