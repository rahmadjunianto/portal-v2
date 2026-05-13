<?php

use App\Http\Controllers\AgendaController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
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

// Announcements Route
Route::get('/pengumuman', [PostController::class, 'announcements'])->name('posts.announcements');

// Agendas Routes
Route::get('/agenda', [AgendaController::class, 'index'])->name('agendas.index');
Route::get('/agenda/{slug}', [AgendaController::class, 'show'])->name('agendas.show');

// Downloads Route
Route::get('/download', [DownloadController::class, 'index'])->name('downloads.index');
Route::get('/download/{download}', [DownloadController::class, 'download'])->name('downloads.download');

// Pages Route
Route::get('/halaman/{slug}', [PageController::class, 'show'])->name('pages.show');

// Static Pages
Route::get('/profil', function () { return view('profil'); })->name('profil');
Route::get('/kontak', function () { return view('kontak'); })->name('kontak');

// Chatbot Routes
Route::post('/chatbot/chat', [ChatbotController::class, 'chat'])->name('chatbot.chat');
Route::get('/chatbot/info', [ChatbotController::class, 'info'])->name('chatbot.info');
Route::get('/chatbot/health', [ChatbotController::class, 'health'])->name('chatbot.health');
Route::get('/admin/chat-history', [ChatbotController::class, 'history'])->name('chatbot.history');
