<?php

namespace App\Http\Controllers;

use App\Services\AIService;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class WhatsAppWebhookController extends Controller
{
    private AIService $aiService;
    private WhatsAppService $whatsAppService;

    public function __construct(AIService $aiService, WhatsAppService $whatsAppService)
    {
        $this->aiService = $aiService;
        $this->whatsAppService = $whatsAppService;
    }

    /**
     * Handle incoming WhatsApp message via webhook
     * Endpoint: POST /api/whatsapp/webhook
     */
    public function webhook(Request $request): JsonResponse
    {
        // Validate webhook secret for security
        $secret = $request->header('X-Webhook-Secret');
        $expectedSecret = config('whatsapp.webhook_secret');

        if ($expectedSecret && $secret !== $expectedSecret) {
            Log::warning('WhatsApp Webhook: Invalid secret', ['ip' => $request->ip()]);
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Validate request payload
        $validated = $request->validate([
            'phone' => 'required|string',
            'name' => 'required|string',
            'message' => 'required|string',
            'message_id' => 'nullable|string',
            'timestamp' => 'nullable|string',
            'is_group' => 'nullable|boolean',
            'group_name' => 'nullable|string',
        ]);

        $phone = $validated['phone'];
        $name = $validated['name'];
        $message = $validated['message'];

        Log::info('WhatsApp Incoming Message', [
            'phone' => $phone,
            'name' => $name,
            'message' => $message,
            'message_id' => $validated['message_id'] ?? null,
        ]);

        // Skip processing if message is from group (unless enabled)
        if (($validated['is_group'] ?? false) && !config('whatsapp.allow_group_messages', false)) {
            return response()->json(['status' => 'skipped', 'reason' => 'group_message']);
        }

        // Rate limiting per phone number
        if (!$this->whatsAppService->checkRateLimit($phone)) {
            return response()->json([
                'status' => 'rate_limited',
                'reply' => config('whatsapp.rate_limit_message', 'Terlalu banyak permintaan. Mohon tunggu sebentar.'),
            ]);
        }

        // Process message and get AI response
        $response = $this->whatsAppService->processMessage($phone, $name, $message);

        return response()->json([
            'status' => 'success',
            'reply' => $response['message'],
            'message_id' => $validated['message_id'] ?? null,
        ]);
    }

    /**
     * Health check endpoint for WhatsApp service
     * GET /api/whatsapp/health
     */
    public function health(): JsonResponse
    {
        $hasApiKey = !empty(config('chatbot.api_key'));
        $webhookConfigured = !empty(config('whatsapp.webhook_secret'));

        return response()->json([
            'status' => $hasApiKey ? 'healthy' : 'configuration_required',
            'ai_configured' => $hasApiKey,
            'webhook_configured' => $webhookConfigured,
            'timestamp' => now()->toISOString(),
        ]);
    }

    /**
     * Get conversation history for a phone number
     * GET /api/whatsapp/conversation/{phone}
     */
    public function getConversation(string $phone): JsonResponse
    {
        $history = $this->whatsAppService->getConversationHistory($phone);

        return response()->json([
            'phone' => $phone,
            'messages' => $history,
            'count' => count($history),
        ]);
    }

    /**
     * Clear conversation history for a phone number
     * DELETE /api/whatsapp/conversation/{phone}
     */
    public function clearConversation(string $phone): JsonResponse
    {
        $this->whatsAppService->clearConversationHistory($phone);

        return response()->json([
            'status' => 'success',
            'message' => 'Conversation history cleared',
        ]);
    }

    /**
     * Broadcast message to multiple numbers
     * POST /api/whatsapp/broadcast
     */
    public function broadcast(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'numbers' => 'required|array',
            'numbers.*' => 'required|string',
            'message' => 'required|string',
        ]);

        $results = $this->whatsAppService->broadcastMessage(
            $validated['numbers'],
            $validated['message']
        );

        return response()->json([
            'status' => 'completed',
            'total' => count($validated['numbers']),
            'results' => $results,
        ]);
    }
}
