<?php

namespace App\Http\Controllers;

use App\Models\ChatConversation;
use App\Services\AIService;
use App\Services\KnowledgeBankService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ChatbotController extends Controller
{
    private AIService $aiService;
    private KnowledgeBankService $knowledgeBank;

    public function __construct(AIService $aiService, KnowledgeBankService $knowledgeBank)
    {
        $this->aiService = $aiService;
        $this->knowledgeBank = $knowledgeBank;
    }

    /**
     * Handle chat message
     */
    public function chat(Request $request): JsonResponse
    {
        // Validate request
        $validated = $request->validate([
            'message' => 'required|string',
            'conversation' => 'nullable|array',
            'user_name' => 'required|string|max:100',
            'user_email' => 'required|email|max:100',
            'user_phone' => 'required|string|max:20',
        ]);

        $ip = $request->ip();
        $message = $validated['message'];

        // Rate limiting check
        if (!$this->aiService->checkRateLimit($ip)) {
            return response()->json([
                'success' => false,
                'message' => 'Terlalu banyak permintaan. Mohon tunggu sebentar sebelum mengirim pesan lagi.',
                'error' => 'rate_limit',
            ], 429);
        }

        // Validate message length
        if (!$this->aiService->validateMessageLength($message)) {
            return response()->json([
                'success' => false,
                'message' => 'Pesan terlalu panjang. Maksimal ' . config('chatbot.max_characters') . ' karakter.',
                'error' => 'message_too_long',
            ], 422);
        }

        if (empty($message)) {
            return response()->json([
                'success' => false,
                'message' => 'Pesan tidak boleh kosong.',
                'error' => 'empty_message',
            ], 422);
        }

        // Check Knowledge Bank FIRST before using AI
        $kbAnswer = $this->knowledgeBank->findAnswer($message);
        
        if ($kbAnswer) {
            Log::info('Chatbot Knowledge Bank Response', [
                'ip' => $ip,
                'query' => $message,
                'source' => 'knowledge_bank',
            ]);

            // Log to database
            try {
                ChatConversation::create([
                    'ip_address' => $ip,
                    'user_name' => $validated['user_name'] ?? null,
                    'user_email' => $validated['user_email'] ?? null,
                    'user_phone' => $validated['user_phone'] ?? null,
                    'user_agent' => $request->userAgent(),
                    'role' => 'user',
                    'message' => $message,
                    'response' => $kbAnswer,
                    'tokens_used' => 0,
                    'is_success' => true,
                    'error_message' => null,
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to log chat conversation', ['error' => $e->getMessage()]);
            }

            return response()->json([
                'success' => true,
                'message' => $kbAnswer,
                'source' => 'knowledge_bank',
            ]);
        }

        // Build conversation messages for AI
        $messages = [
            ['role' => 'system', 'content' => $this->aiService->getSystemPrompt()],
        ];

        // Add conversation history if exists
        $conversation = $validated['conversation'] ?? [];
        foreach ($conversation as $msg) {
            if (isset($msg['role']) && isset($msg['content'])) {
                $messages[] = [
                    'role' => in_array($msg['role'], ['user', 'assistant']) ? $msg['role'] : 'user',
                    'content' => $msg['content'],
                ];
            }
        }

        // Add current message
        $messages[] = ['role' => 'user', 'content' => $message];

        // Limit conversation history
        if (count($messages) > 11) {
            $messages = array_slice($messages, 0, 11);
        }

        // Check daily token limit (global - all IPs combined)
        $dailyLimitEnabled = config('chatbot.daily_token_limit.enabled', false);
        if ($dailyLimitEnabled) {
            $maxTokens = config('chatbot.daily_token_limit.max_tokens', 100000);
            $todayStart = Carbon::today()->startOfDay();
            $todayEnd = Carbon::today()->endOfDay();

            $todayTokens = ChatConversation::whereBetween('created_at', [$todayStart, $todayEnd])
                ->where('is_success', true)
                ->sum('tokens_used');

            // Check if adding this request would exceed the limit
            $estimatedTokens = config('chatbot.max_tokens', 500);
            if (($todayTokens + $estimatedTokens) > $maxTokens) {
                return response()->json([
                    'success' => false,
                    'message' => 'Batas penggunaan token harian telah tercapai. Mohon coba lagi besok.',
                    'error' => 'token_limit_exceeded',
                    'remaining_tokens' => max(0, $maxTokens - $todayTokens),
                ], 429);
            }
        }

        // Send to AI API
        $response = $this->aiService->sendMessage($messages);

        // Log to database
        try {
            ChatConversation::create([
                'ip_address' => $ip,
                'user_name' => $validated['user_name'] ?? null,
                'user_email' => $validated['user_email'] ?? null,
                'user_phone' => $validated['user_phone'] ?? null,
                'user_agent' => $request->userAgent(),
                'role' => 'user',
                'message' => $message,
                'response' => $response['message'],
                'tokens_used' => $response['usage']['total_tokens'] ?? null,
                'is_success' => $response['success'],
                'error_message' => !$response['success'] ? $response['message'] : null,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to log chat conversation', ['error' => $e->getMessage()]);
        }

        // Log the interaction
        Log::info('Chatbot Interaction', [
            'ip' => $ip,
            'message' => $message,
            'success' => $response['success'],
        ]);

        return response()->json($response);
    }

    /**
     * Get chatbot info
     */
    public function info(): JsonResponse
    {
        return response()->json([
            'name' => 'AI Assistant Kemenag Nganjuk',
            'version' => '1.0.0',
            'status' => config('chatbot.enabled', true) ? 'online' : 'offline',
            'working_hours' => [
                'weekdays' => '08.00 - 15.30 WIB',
                'friday' => '08.00 - 14.00 WIB',
                'weekend' => 'Tutup',
            ],
            'contact' => [
                'phone' => '(0358) 321085',
                'email' => 'kantor@nganjuk.kemenag.go.id',
            ],
        ]);
    }

    /**
     * Health check endpoint
     */
    public function health(): JsonResponse
    {
        $hasApiKey = !empty(config('chatbot.api_key'));

        return response()->json([
            'status' => $hasApiKey ? 'healthy' : 'configuration_required',
            'api_configured' => $hasApiKey,
            'timestamp' => now()->toISOString(),
        ]);
    }

    /**
     * Get chat widget HTML (for deferred loading)
     */
    public function widget()
    {
        return response(view('components.chat-widget')->render())
            ->header('Content-Type', 'text/html');
    }

    /**
     * Show chat history (Admin page)
     */
    public function history(Request $request)
    {
        $query = ChatConversation::query();

        // Filter by IP
        if ($request->has('ip') && $request->ip) {
            $query->byIp($request->ip);
        }

        // Filter by date range
        if ($request->has('start_date') && $request->start_date) {
            $startDate = $request->start_date . ' 00:00:00';
            $endDate = $request->has('end_date') && $request->end_date
                ? $request->end_date . ' 23:59:59'
                : now()->endOfDay();
            $query->dateRange($startDate, $endDate);
        }

        // Filter by success status
        if ($request->has('status') && $request->status !== '') {
            $query->where('is_success', $request->status === '1');
        }

        $conversations = $query->orderBy('created_at', 'desc')->paginate(10);

        // Statistics
        $stats = [
            'total' => ChatConversation::count(),
            'today' => ChatConversation::whereDate('created_at', today())->count(),
            'success' => ChatConversation::successful()->count(),
            'failed' => ChatConversation::where('is_success', false)->count(),
            'total_tokens' => ChatConversation::sum('tokens_used') ?? 0,
        ];

        return view('admin.chat-history', compact('conversations', 'stats'));
    }
}
