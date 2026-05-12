<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ForgotPasswordController;
use App\Http\Controllers\Admin\ResetPasswordController;
use App\Http\Controllers\Admin\UploadController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application.
| These routes are loaded by the RouteServiceProvider.
|
*/

// Wrap all routes in web middleware to get $errors variable shared
Route::middleware(['web'])->group(function () {
    
    // Auth Routes (without admin middleware)
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AuthController::class, 'login'])->name('admin.login.submit');
    Route::post('/logout', [AuthController::class, 'logout'])->name('admin.logout');
    
    // Password Reset Routes
    Route::get('/password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

    // Protected Admin Routes
    Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        
        // Categories
        Route::get('/categories', [\App\Http\Controllers\Admin\CategoryController::class, 'index'])->name('categories.index');
        Route::get('/categories/create', [\App\Http\Controllers\Admin\CategoryController::class, 'create'])->name('categories.create');
        Route::post('/categories', [\App\Http\Controllers\Admin\CategoryController::class, 'store'])->name('categories.store');
        Route::get('/categories/{id}/edit', [\App\Http\Controllers\Admin\CategoryController::class, 'edit'])->name('categories.edit');
        Route::put('/categories/{id}', [\App\Http\Controllers\Admin\CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{id}', [\App\Http\Controllers\Admin\CategoryController::class, 'destroy'])->name('categories.destroy');
        
        // Posts
        Route::get('/posts', [\App\Http\Controllers\Admin\PostController::class, 'index'])->name('posts.index');
        Route::get('/posts/create', [\App\Http\Controllers\Admin\PostController::class, 'create'])->name('posts.create');
        Route::post('/posts', [\App\Http\Controllers\Admin\PostController::class, 'store'])->name('posts.store');
        Route::get('/posts/{id}/edit', [\App\Http\Controllers\Admin\PostController::class, 'edit'])->name('posts.edit');
        Route::put('/posts/{id}', [\App\Http\Controllers\Admin\PostController::class, 'update'])->name('posts.update');
        Route::delete('/posts/{id}', [\App\Http\Controllers\Admin\PostController::class, 'destroy'])->name('posts.destroy');
        
        // Upload
        Route::post('/upload/image', [UploadController::class, 'image'])->name('upload.image');
        
        // Pages
        Route::get('/pages', [\App\Http\Controllers\Admin\PageController::class, 'index'])->name('pages.index');
        Route::get('/pages/create', [\App\Http\Controllers\Admin\PageController::class, 'create'])->name('pages.create');
        Route::post('/pages', [\App\Http\Controllers\Admin\PageController::class, 'store'])->name('pages.store');
        Route::get('/pages/{id}/edit', [\App\Http\Controllers\Admin\PageController::class, 'edit'])->name('pages.edit');
        Route::put('/pages/{id}', [\App\Http\Controllers\Admin\PageController::class, 'update'])->name('pages.update');
        Route::delete('/pages/{id}', [\App\Http\Controllers\Admin\PageController::class, 'destroy'])->name('pages.destroy');
        
        // Agendas
        Route::get('/agendas', [\App\Http\Controllers\Admin\AgendaController::class, 'index'])->name('agendas.index');
        Route::get('/agendas/create', [\App\Http\Controllers\Admin\AgendaController::class, 'create'])->name('agendas.create');
        Route::post('/agendas', [\App\Http\Controllers\Admin\AgendaController::class, 'store'])->name('agendas.store');
        Route::get('/agendas/{id}/edit', [\App\Http\Controllers\Admin\AgendaController::class, 'edit'])->name('agendas.edit');
        Route::put('/agendas/{id}', [\App\Http\Controllers\Admin\AgendaController::class, 'update'])->name('agendas.update');
        Route::delete('/agendas/{id}', [\App\Http\Controllers\Admin\AgendaController::class, 'destroy'])->name('agendas.destroy');
        
        // Downloads
        Route::get('/downloads', [\App\Http\Controllers\Admin\DownloadController::class, 'index'])->name('downloads.index');
        Route::get('/downloads/create', [\App\Http\Controllers\Admin\DownloadController::class, 'create'])->name('downloads.create');
        Route::post('/downloads', [\App\Http\Controllers\Admin\DownloadController::class, 'store'])->name('downloads.store');
        Route::get('/downloads/{id}/edit', [\App\Http\Controllers\Admin\DownloadController::class, 'edit'])->name('downloads.edit');
        Route::put('/downloads/{id}', [\App\Http\Controllers\Admin\DownloadController::class, 'update'])->name('downloads.update');
        Route::delete('/downloads/{id}', [\App\Http\Controllers\Admin\DownloadController::class, 'destroy'])->name('downloads.destroy');
        
        // Settings
        Route::get('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
        Route::post('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'store'])->name('settings.store');
        
        // External Links
        Route::get('/external-links', [\App\Http\Controllers\Admin\ExternalLinkController::class, 'index'])->name('external-links.index');
        Route::get('/external-links/create', [\App\Http\Controllers\Admin\ExternalLinkController::class, 'create'])->name('external-links.create');
        Route::post('/external-links', [\App\Http\Controllers\Admin\ExternalLinkController::class, 'store'])->name('external-links.store');
        Route::get('/external-links/{id}/edit', [\App\Http\Controllers\Admin\ExternalLinkController::class, 'edit'])->name('external-links.edit');
        Route::put('/external-links/{id}', [\App\Http\Controllers\Admin\ExternalLinkController::class, 'update'])->name('external-links.update');
        Route::delete('/external-links/{id}', [\App\Http\Controllers\Admin\ExternalLinkController::class, 'destroy'])->name('external-links.destroy');
        
        // Menus
        Route::get('/menus', [\App\Http\Controllers\Admin\MenuController::class, 'index'])->name('menus.index');
        Route::get('/menus/{id}/edit', [\App\Http\Controllers\Admin\MenuController::class, 'edit'])->name('menus.edit');
        Route::put('/menus/{id}', [\App\Http\Controllers\Admin\MenuController::class, 'update'])->name('menus.update');
        
        // Menu Items
        Route::get('/menu-items/create', [\App\Http\Controllers\Admin\MenuItemController::class, 'create'])->name('menu-items.create');
        Route::post('/menu-items', [\App\Http\Controllers\Admin\MenuItemController::class, 'store'])->name('menu-items.store');
        Route::get('/menu-items/{id}/edit', [\App\Http\Controllers\Admin\MenuItemController::class, 'edit'])->name('menu-items.edit');
        Route::put('/menu-items/{id}', [\App\Http\Controllers\Admin\MenuItemController::class, 'update'])->name('menu-items.update');
        Route::delete('/menu-items/{id}', [\App\Http\Controllers\Admin\MenuItemController::class, 'destroy'])->name('menu-items.destroy');
        
        // Users (Admin only)
        Route::middleware(['admin.only'])->group(function () {
            Route::get('/users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
            Route::get('/users/create', [\App\Http\Controllers\Admin\UserController::class, 'create'])->name('users.create');
            Route::post('/users', [\App\Http\Controllers\Admin\UserController::class, 'store'])->name('users.store');
            Route::get('/users/{id}/edit', [\App\Http\Controllers\Admin\UserController::class, 'edit'])->name('users.edit');
            Route::put('/users/{id}', [\App\Http\Controllers\Admin\UserController::class, 'update'])->name('users.update');
            Route::delete('/users/{id}', [\App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('users.destroy');
            Route::post('/users/{id}/reset-password', [\App\Http\Controllers\Admin\UserController::class, 'resetPassword'])->name('users.reset-password');
        });
    });
});
