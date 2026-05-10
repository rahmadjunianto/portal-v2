<?php

namespace App\Services;

use App\Models\MenuItem;
use Illuminate\Support\Collection;

class MenuService
{
    /**
     * Ambil semua menu items aktif, diurutkan
     */
    public function getActiveMenus(): Collection
    {
        return MenuItem::active()
            ->ordered()
            ->get();
    }

    /**
     * Bangun tree menu dari flat array
     */
    public function buildTree(Collection $items): Collection
    {
        $grouped = $items->groupBy('parent_id');
        return $this->buildSubTree($grouped, null);
    }

    /**
     * Rekursif build subtree
     */
    protected function buildSubTree($grouped, $parentId): Collection
    {
        $collection = collect();

        if (!$grouped->has($parentId)) {
            return $collection;
        }

        foreach ($grouped->get($parentId) as $item) {
            $children = $this->buildSubTree($grouped, $item->id);
            $item->setRelation('children', $children);
            $collection->push($item);
        }

        return $collection;
    }

    /**
     * Get menu utama (parent_id NULL) dengan ALL children loaded (recursive)
     */
    public function getMainMenus(): Collection
    {
        return MenuItem::active()
            ->root()
            ->ordered()
            ->with(['children' => function($q) {
                $q->active()->ordered();
            }])
            ->get()
            ->each(function ($item) {
                $this->loadAllChildren($item);
            });
    }

    /**
     * Load all nested children recursively (max 3 levels untuk perfomansi)
     */
    protected function loadAllChildren($item, int $depth = 0, int $maxDepth = 3): void
    {
        if ($depth >= $maxDepth) {
            return;
        }

        if ($item->children && $item->children->count() > 0) {
            $childIds = $item->children->pluck('id')->toArray();

            $grandChildren = \App\Models\MenuItem::whereIn('parent_id', $childIds)
                ->active()
                ->ordered()
                ->get()
                ->groupBy('parent_id');

            foreach ($item->children as $child) {
                $childrenOfChild = $grandChildren->get($child->id, collect());
                $child->setRelation('children', $childrenOfChild);

                if ($childrenOfChild->count() > 0) {
                    $this->loadAllChildren($child, $depth + 1, $maxDepth);
                }
            }
        }
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
            if ($child->children->isNotEmpty() && $this->isChildActive($child->children, $currentPath)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Get menu tree lengkap
     */
    public function getMenuTree(): Collection
    {
        return $this->buildTree($this->getActiveMenus());
    }
}
