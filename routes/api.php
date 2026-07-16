<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WhatsAppWebhookController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group.
|
*/

// WhatsApp Webhook Routes
Route::prefix('whatsapp')->group(function () {
    // Main webhook endpoint for receiving WhatsApp messages
    Route::post('/webhook', [WhatsAppWebhookController::class, 'webhook'])
        ->name('whatsapp.webhook');
    
    // Health check endpoint
    Route::get('/health', [WhatsAppWebhookController::class, 'health'])
        ->name('whatsapp.health');
    
    // Get conversation history
    Route::get('/conversation/{phone}', [WhatsAppWebhookController::class, 'getConversation'])
        ->name('whatsapp.conversation.get');
    
    // Clear conversation history
    Route::delete('/conversation/{phone}', [WhatsAppWebhookController::class, 'clearConversation'])
        ->name('whatsapp.conversation.clear');
    
    // Broadcast message
    Route::post('/broadcast', [WhatsAppWebhookController::class, 'broadcast'])
        ->name('whatsapp.broadcast');
});
