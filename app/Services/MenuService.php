<?php

namespace App\Services;

use App\Models\MenuItem;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

class MenuService
{
    /**
     * Cache menu tree untuk menghindari query berulang
     */
    protected ?Collection $cachedTree = null;

    /**
     * Ambil SEMUA menu items aktif dalam SATU query, lalu build tree di memory
     */
    public function getAllActiveMenus(): Collection
    {
        // Gunakan cache jika sudah ada
        if ($this->cachedTree !== null) {
            return $this->cachedTree;
        }

        // Single query untuk ambil semua menu items aktif
        $items = MenuItem::active()
            ->ordered()
            ->get();

        // Build tree di PHP memory (tidak ada query tambahan)
        $this->cachedTree = $this->buildTreeFromCollection($items);

        return $this->cachedTree;
    }

    /**
     * Build tree dari flat collection (di memory, tanpa query)
     */
    public function buildTreeFromCollection(Collection $items): Collection
    {
        // Group berdasarkan parent_id untuk efisiensi O(n)
        $grouped = $items->groupBy('parent_id');

        // Build subtree dimulai dari root (parent_id = null)
        return $this->buildSubTreeFromGrouped($grouped, null);
    }

    /**
     * Rekursif build subtree dari grouped collection
     */
    protected function buildSubTreeFromGrouped($grouped, $parentId): Collection
    {
        $collection = collect();

        // Jika tidak ada children untuk parent ini, return empty collection
        if (!$grouped->has($parentId)) {
            return $collection;
        }

        // Iterate children dan set relation
        foreach ($grouped->get($parentId) as $item) {
            // Rekursif ambil children dari grouped collection
            $children = $this->buildSubTreeFromGrouped($grouped, $item->id);
            $item->setRelation('children', $children);
            $collection->push($item);
        }

        return $collection;
    }

    /**
     * Clear cache (panggil jika ada perubahan menu)
     */
    public function clearCache(): void
    {
        $this->cachedTree = null;
    }

    /**
     * Get main menus (root items dengan children) - backward compatible
     */
    public function getMainMenus(): Collection
    {
        return $this->getAllActiveMenus();
    }

    /**
     * Get menu tree - alias dari getAllActiveMenus
     */
    public function getMenuTree(): Collection
    {
        return $this->getAllActiveMenus();
    }

    /**
     * Get active menus (flat) - untuk backward compatibility
     */
    public function getActiveMenus(): Collection
    {
        return MenuItem::active()->ordered()->get();
    }

    /**
     * Check apakah path saat ini ada di dalam submenu
     */
    public function isChildActive(Collection $children, string $currentPath): bool
    {
        foreach ($children as $child) {
            if ($child->url === $currentPath) {
                return true;
            }
            if ($child->children && $child->children->isNotEmpty() && $this->isChildActive($child->children, $currentPath)) {
                return true;
            }
        }
        return false;
    }
}
