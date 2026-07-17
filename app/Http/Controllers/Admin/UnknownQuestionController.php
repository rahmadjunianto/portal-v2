<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KnowledgeBank;
use App\Models\Service;
use App\Models\UnknownQuestion;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class UnknownQuestionController extends Controller
{
    /**
     * Display a listing of unknown questions.
     */
    public function index(Request $request): View
    {
        $query = UnknownQuestion::query();

        // Filter by status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->search) {
            $query->where('question', 'like', '%' . $request->search . '%');
        }

        // Default sort by count (popular)
        $unknownQuestions = $query->popular()->paginate(20);

        $statusCounts = [
            'all' => UnknownQuestion::count(),
            'unprocessed' => UnknownQuestion::unprocessed()->count(),
            'processing' => UnknownQuestion::where('status', 'processing')->count(),
            'resolved' => UnknownQuestion::where('status', 'resolved')->count(),
        ];

        return view('admin.unknown-questions.index', compact('unknownQuestions', 'statusCounts'));
    }

    /**
     * Show form to add unknown question to knowledge bank.
     */
    public function createFromUnknown(UnknownQuestion $unknownQuestion): View
    {
        $services = Service::active()->with('category')->orderBy('name')->get();
        
        return view('admin.unknown-questions.create', compact('unknownQuestion', 'services'));
    }

    /**
     * Add unknown question to knowledge bank.
     */
    public function addToKnowledge(Request $request, UnknownQuestion $unknownQuestion): RedirectResponse
    {
        $validated = $request->validate([
            'service_id' => 'nullable|exists:services,id',
            'question' => 'required|string|max:500',
            'answer' => 'nullable|required_if:service_id,null|string',
            'tags' => 'nullable|string|max:255',
            'priority' => 'nullable|integer|min:0|max:100',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['priority'] = $validated['priority'] ?? 0;

        // Create knowledge bank entry
        KnowledgeBank::create([
            'service_id' => $validated['service_id'] ?? null,
            'question' => $validated['question'],
            'answer' => $validated['answer'] ?? null,
            'tags' => $validated['tags'] ?? null,
            'priority' => $validated['priority'],
            'is_active' => $validated['is_active'],
        ]);

        // Mark unknown question as resolved
        $unknownQuestion->update(['status' => 'resolved']);

        return redirect()
            ->route('admin.unknown-questions.index')
            ->with('success', 'Pertanyaan berhasil ditambahkan ke Knowledge Bank.');
    }

    /**
     * Update status of unknown question.
     */
    public function updateStatus(Request $request, UnknownQuestion $unknownQuestion): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:unprocessed,processing,resolved',
        ]);

        $unknownQuestion->update($validated);

        return redirect()
            ->route('admin.unknown-questions.index')
            ->with('success', 'Status berhasil diupdate.');
    }

    /**
     * Delete an unknown question.
     */
    public function destroy(UnknownQuestion $unknownQuestion): RedirectResponse
    {
        $unknownQuestion->delete();

        return redirect()
            ->route('admin.unknown-questions.index')
            ->with('success', 'Pertanyaan berhasil dihapus.');
    }

    /**
     * Bulk resolve unknown questions.
     */
    public function bulkResolve(Request $request): RedirectResponse
    {
        $ids = $request->input('ids', []);
        
        UnknownQuestion::whereIn('id', $ids)->update(['status' => 'resolved']);

        return redirect()
            ->route('admin.unknown-questions.index')
            ->with('success', count($ids) . ' pertanyaan berhasil ditandai resolved.');
    }
}
