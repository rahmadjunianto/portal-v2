<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ServiceController extends Controller
{
    /**
     * Display a listing of services.
     */
    public function index(Request $request): View
    {
        $query = Service::with('category');

        // Filter by category
        if ($request->category) {
            $query->where('service_category_id', $request->category);
        }

        // Search
        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter active/inactive
        if ($request->filled('active')) {
            $active = $request->active == '1' ? true : false;
            $query->where('is_active', $active);
        }

        $services = $query->orderBy('name')->paginate(20);
        $categories = ServiceCategory::active()->ordered()->get();

        return view('admin.services.index', compact('services', 'categories'));
    }

    /**
     * Show the form for creating a new service.
     */
    public function create(): View
    {
        $categories = ServiceCategory::active()->ordered()->get();
        return view('admin.services.create', compact('categories'));
    }

    /**
     * Store a newly created service.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'service_category_id' => 'nullable|exists:service_categories,id',
            'description' => 'nullable|string',
            'requirements' => 'nullable|string',
            'workflow' => 'nullable|string',
            'processing_time' => 'nullable|string|max:100',
            'cost' => 'nullable|string|max:100',
            'contact' => 'nullable|string|max:255',
            'office' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
            'download_link' => 'nullable|url|max:255',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        Service::create($validated);

        return redirect()
            ->route('admin.services.index')
            ->with('success', 'Layanan berhasil ditambahkan.');
    }

    /**
     * Show the form for editing a service.
     */
    public function edit(Service $service): View
    {
        $categories = ServiceCategory::active()->ordered()->get();
        return view('admin.services.edit', compact('service', 'categories'));
    }

    /**
     * Update a service.
     */
    public function update(Request $request, Service $service): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'service_category_id' => 'nullable|exists:service_categories,id',
            'description' => 'nullable|string',
            'requirements' => 'nullable|string',
            'workflow' => 'nullable|string',
            'processing_time' => 'nullable|string|max:100',
            'cost' => 'nullable|string|max:100',
            'contact' => 'nullable|string|max:255',
            'office' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
            'download_link' => 'nullable|url|max:255',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $service->update($validated);

        return redirect()
            ->route('admin.services.index')
            ->with('success', 'Layanan berhasil diupdate.');
    }

    /**
     * Remove a service.
     */
    public function destroy(Service $service): RedirectResponse
    {
        $service->delete();

        return redirect()
            ->route('admin.services.index')
            ->with('success', 'Layanan berhasil dihapus.');
    }

    /**
     * Get knowledge bank tab content for a service.
     */
    public function knowledge(Service $service): View
    {
        $knowledgeBanks = $service->knowledgeBanks()
            ->orderByDesc('priority')
            ->orderByDesc('created_at')
            ->get();

        return view('admin.services._tab-knowledge', compact('service', 'knowledgeBanks'));
    }
}
