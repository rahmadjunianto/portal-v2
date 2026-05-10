<?php

use App\Http\Controllers\DownloadController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group.
|
*/

// Homepage
Route::get('/', [HomeController::class, 'index'])->name('home');

// Posts Routes
Route::get('/berita', [PostController::class, 'index'])->name('posts.index');
Route::get('/berita/{slug}', [PostController::class, 'show'])->name('posts.show');

// Agendas Routes
Route::get('/agenda', function () { return view('agenda'); })->name('agendas.index');
Route::get('/agenda/{slug}', function ($slug) { return view('agenda-detail', ['slug' => $slug]); })->name('agendas.show');

// Downloads Route
Route::get('/download', [DownloadController::class, 'index'])->name('downloads.index');

// Pages Route
Route::get('/halaman/{slug}', function ($slug) { return view('page-detail', ['slug' => $slug]); })->name('pages.show');

// Static Pages
Route::get('/profil', function () { return view('profil'); })->name('profil');
Route::get('/kontak', function () { return view('kontak'); })->name('kontak');
