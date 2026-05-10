<?php

namespace App\Providers;

use App\Services\MenuService;
use App\ViewComposers\MenuViewComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(MenuService::class, function () {
            return new MenuService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('layouts.*', MenuViewComposer::class);
        View::composer('partials.*', MenuViewComposer::class);
        View::composer('components.layout', MenuViewComposer::class);
    }
}
