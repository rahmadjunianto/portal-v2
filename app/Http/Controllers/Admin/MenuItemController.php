<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;

class MenuItemController extends Controller
{
    /**
     * Display a listing of menu items.
     */
    public function index(): View
    {
        $menuItems = MenuItem::with(['parent', 'children'])
            ->orderBy('parent_id')
            ->orderBy('sort_order')
            ->get();

        return view('admin.menu-items.index', compact('menuItems'));
    }

    /**
     * Show the form for creating a new menu item.
     */
    public function create(Request $request): View
    {
        $parentId = $request->get('parent_id');
        $parent = null;
        
        if ($parentId) {
            $parent = MenuItem::find($parentId);
        }

        // Get all menu items for parent selection (exclude current item and descendants if editing)
        $menuItems = MenuItem::orderBy('parent_id')
            ->orderBy('sort_order')
            ->get();

        return view('admin.menu-items.create', compact('parent', 'menuItems'));
    }

    /**
     * Store a newly created menu item in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'parent_id' => 'nullable|exists:menu_items,id',
            'title' => 'required|string|max:255',
            'url' => 'nullable|string|max:500',
            'open_in_new_tab' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['open_in_new_tab'] = $request->has('open_in_new_tab');
        
        // Auto-set sort_order if not provided
        if (!isset($validated['sort_order'])) {
            $validated['sort_order'] = MenuItem::where('parent_id', $validated['parent_id'] ?? null)->max('sort_order') + 1;
        }

        MenuItem::create($validated);
        Cache::forget('menu_tree');

        return redirect()
            ->route('admin.menu-items.index')
            ->with('success', 'Menu item berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified menu item.
     */
    public function edit(MenuItem $menuItem): View
    {
        // Get all menu items except current item and its descendants
        $excludeIds = $this->getDescendantIds($menuItem);
        $excludeIds[] = $menuItem->id;
        
        $menuItems = MenuItem::whereNotIn('id', $excludeIds)
            ->orderBy('parent_id')
            ->orderBy('sort_order')
            ->get();

        return view('admin.menu-items.edit', compact('menuItem', 'menuItems'));
    }

    /**
     * Update the specified menu item in storage.
     */
    public function update(Request $request, MenuItem $menuItem): RedirectResponse
    {
        $validated = $request->validate([
            'parent_id' => 'nullable|exists:menu_items,id',
            'title' => 'required|string|max:255',
            'url' => 'nullable|string|max:500',
            'open_in_new_tab' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['open_in_new_tab'] = $request->has('open_in_new_tab');

        // Prevent setting self as parent
        if (isset($validated['parent_id']) && $validated['parent_id'] == $menuItem->id) {
            return back()->withErrors(['parent_id' => 'Item tidak dapat dijadikan parent dari dirinya sendiri.']);
        }

        $menuItem->update($validated);
        Cache::forget('menu_tree');

        return redirect()
            ->route('admin.menu-items.index')
            ->with('success', 'Menu item berhasil diperbarui.');
    }

    /**
     * Remove the specified menu item from storage.
     */
    public function destroy(MenuItem $menuItem): RedirectResponse
    {
        // Delete children recursively or move to root
        $this->moveChildrenToRoot($menuItem->id);
        
        $menuItem->delete();
        Cache::forget('menu_tree');

        return redirect()
            ->route('admin.menu-items.index')
            ->with('success', 'Menu item berhasil dihapus.');
    }

    /**
     * Get all descendant IDs of a menu item.
     */
    protected function getDescendantIds(MenuItem $menuItem): array
    {
        $ids = [];
        
        foreach ($menuItem->children as $child) {
            $ids[] = $child->id;
            $ids = array_merge($ids, $this->getDescendantIds($child));
        }

        return $ids;
    }

    /**
     * Move children of a deleted item to root level.
     */
    protected function moveChildrenToRoot(int $parentId): void
    {
        MenuItem::where('parent_id', $parentId)->update(['parent_id' => null]);
    }

    /**
     * Toggle active status.
     */
    public function toggleActive(MenuItem $menuItem): RedirectResponse
    {
        $menuItem->update(['is_active' => !$menuItem->is_active]);
        Cache::forget('menu_tree');

        return back()->with('success', 'Status menu berhasil diubah.');
    }

    /**
     * Reorder menu items.
     */
    public function reorder(Request $request): RedirectResponse
    {
        $order = $request->get('order', []);
        
        foreach ($order as $index => $id) {
            MenuItem::where('id', $id)->update(['sort_order' => $index]);
        }
        
        Cache::forget('menu_tree');

        return response()->json(['success' => true]);
    }
}
