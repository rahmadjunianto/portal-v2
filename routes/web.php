<?php

use App\Http\Controllers\AccessibilityController;
use App\Http\Controllers\AgendaController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PrivacyController;
use App\Http\Controllers\RegulasiController;
use App\Http\Controllers\SitemapController;
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
Route::get('/check-storage', function () {
    return [
        'public_storage' => public_path('storage'),
        'storage_public' => storage_path('app/public'),
        'public_files' => scandir(public_path('storage')),
        'storage_files' => scandir(storage_path('app/public')),
    ];
});
Route::get('/check-storage-link', function () {
    $publicStorage = public_path('storage');

    return [
        'exists' => File::exists($publicStorage),
        'is_link' => is_link($publicStorage),
        'real_path' => @realpath($publicStorage),
        'target_exists' => File::exists(storage_path('app/public')),
    ];
});
// Homepage
Route::get('/', [HomeController::class, 'index'])->name('home');

// Accessibility Statement Page - WCAG 2.1 SC 4.1.2
Route::get('/aksesibilitas', [AccessibilityController::class, 'index'])->name('accessibility');

// Privacy Policy Page - UU PDP Compliance
Route::get('/kebijakan-privasi', [PrivacyController::class, 'index'])->name('privacy');

// Regulasi Page
Route::get('/regulasi', [RegulasiController::class, 'index'])->name('regulasi');

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

// Contact Form - CSRF Protected with Honeypot & Rate Limiting
Route::get('/kontak', [ContactController::class, 'index'])->name('contact');
Route::post('/kontak', [ContactController::class, 'submit'])->name('contact.submit');

// Chatbot Routes
Route::post('/chatbot/chat', [ChatbotController::class, 'chat'])->name('chatbot.chat');
Route::get('/chatbot/info', [ChatbotController::class, 'info'])->name('chatbot.info');
Route::get('/chatbot/health', [ChatbotController::class, 'health'])->name('chatbot.health');
Route::get('/chatbot/widget', [ChatbotController::class, 'widget'])->name('chatbot.widget');
// Route::get('/admin/chat-history', [ChatbotController::class, 'history'])->name('chatbot.history');

// Feed Routes - RSS/Atom for SEO and content distribution
Route::get('/feed', [FeedController::class, 'rss'])->name('feed.rss');
Route::get('/atom', [FeedController::class, 'atom'])->name('feed.atom');

// Sitemap Routes - SEO optimization
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap.xml');
Route::get('/sitemap/clear-cache', [SitemapController::class, 'clearCache'])->name('sitemap.clear-cache');
