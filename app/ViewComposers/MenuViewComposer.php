<?php

namespace App\ViewComposers;

use App\Services\MenuService;
use Illuminate\View\View;

class MenuViewComposer
{
    protected MenuService $menuService;

    public function __construct(MenuService $menuService)
    {
        $this->menuService = $menuService;
    }

    /**
     * Bind data ke view
     * OPTIMIZED: Hanya 1 query untuk semua menu items
     */
    public function compose(View $view): void
    {
        // Single query dengan tree building di memory
        $menuTree = $this->menuService->getAllActiveMenus();

        $view->with([
            'headerMenuItems' => $menuTree,
            'menuTree' => $menuTree,
            'mainMenus' => $menuTree,
            'currentPath' => '/' . request()->path(),
        ]);
    }
}
