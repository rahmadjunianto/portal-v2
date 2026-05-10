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
     */
    public function compose(View $view): void
    {
        // getMainMenus sudah load recursive children
        $mainMenus = $this->menuService->getMainMenus();

        // menuTree dari flat collection
        $menuTree = $this->menuService->getMenuTree();
        $currentPath = '/' . request()->path();

        $view->with([
            'menuTree' => $menuTree,
            'mainMenus' => $mainMenus,
            'headerMenuItems' => $mainMenus, // Langsung pakai, sudah ada children
            'currentPath' => $currentPath,
        ]);
    }
}
