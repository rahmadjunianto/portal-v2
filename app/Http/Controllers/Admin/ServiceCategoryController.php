<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ServiceCategoryController extends Controller
{
    /**
     * Display a listing of categories.
     */
    public function index(): View
    {
        $categories = ServiceCategory::withCount(['services' => function ($query) {
            $query->where('is_active', true);
        }])->ordered()->get();

        return view('admin.service-categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new category.
     */
    public function create(): View
    {
        return view('admin.service-categories.create');
    }

    /**
     * Store a newly created category.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:100',
            'color' => 'nullable|string|max:7',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        ServiceCategory::create($validated);

        return redirect()
            ->route('admin.service-categories.index')
            ->with('success', 'Kategori berhasil ditambahkan.');
    }

    /**
     * Show the form for editing a category.
     */
    public function edit(ServiceCategory $serviceCategory): View
    {
        return view('admin.service-categories.edit', compact('serviceCategory'));
    }

    /**
     * Update a category.
     */
    public function update(Request $request, ServiceCategory $serviceCategory): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:100',
            'color' => 'nullable|string|max:7',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $serviceCategory->update($validated);

        return redirect()
            ->route('admin.service-categories.index')
            ->with('success', 'Kategori berhasil diupdate.');
    }

    /**
     * Remove a category.
     */
    public function destroy(ServiceCategory $serviceCategory): RedirectResponse
    {
        // Check if category has services
        if ($serviceCategory->services()->exists()) {
            return redirect()
                ->route('admin.service-categories.index')
                ->with('error', 'Kategori tidak bisa dihapus karena masih memiliki layanan.');
        }

        $serviceCategory->delete();

        return redirect()
            ->route('admin.service-categories.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }
}
