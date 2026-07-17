<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChatConversation;
use App\Models\KnowledgeBankFeedback;
use App\Models\UnknownQuestion;
use App\Models\WhatsappConversation;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class ChatbotController extends Controller
{
    /**
     * Display chat logs (combined from chatbot & whatsapp).
     */
    public function logs(Request $request): View
    {
        $type = $request->get('type', 'all');
        $search = $request->get('search');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');
        
        $conversations = collect();
        
        // Get chatbot conversations
        if ($type === 'all' || $type === 'chatbot') {
            $chatQuery = ChatConversation::query()
                ->select('id', 'user_name as name', 'user_email as email', 'user_phone as phone', 
                         'message', 'response', 'is_success', 'created_at')
                ->addSelect(DB::raw("'chatbot' as source"));
            
            if ($search) {
                $chatQuery->where(function($q) use ($search) {
                    $q->where('user_name', 'like', "%{$search}%")
                      ->orWhere('user_email', 'like', "%{$search}%")
                      ->orWhere('user_phone', 'like', "%{$search}%")
                      ->orWhere('message', 'like', "%{$search}%");
                });
            }
            if ($dateFrom) {
                $chatQuery->whereDate('created_at', '>=', $dateFrom);
            }
            if ($dateTo) {
                $chatQuery->whereDate('created_at', '<=', $dateTo);
            }
            
            $conversations = $chatQuery->orderByDesc('created_at');
        }
        
        // Get whatsapp conversations
        if ($type === 'all' || $type === 'whatsapp') {
            $waQuery = WhatsappConversation::query()
                ->select('id', 'name', 'phone', 'content as message', 'created_at')
                ->addSelect(DB::raw("'whatsapp' as source"))
                ->addSelect(DB::raw("NULL as email"))
                ->addSelect(DB::raw("NULL as response"))
                ->addSelect(DB::raw("NULL as is_success"));
            
            if ($search) {
                $waQuery->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%")
                      ->orWhere('content', 'like', "%{$search}%");
                });
            }
            if ($dateFrom) {
                $waQuery->whereDate('created_at', '>=', $dateFrom);
            }
            if ($dateTo) {
                $waQuery->whereDate('created_at', '<=', $dateTo);
            }
            
            if ($type === 'whatsapp') {
                $conversations = $waQuery->orderByDesc('created_at');
            } else {
                $conversations = $conversations->union($waQuery);
            }
        }
        
        if ($type === 'all') {
            $conversations = $conversations->orderByDesc('created_at')->paginate(25);
        } else {
            $conversations = $conversations->paginate(25);
        }

        return view('admin.chatbot.logs', compact('conversations', 'type'));
    }

    /**
     * Display a single chat conversation.
     */
    public function show(ChatConversation $conversation): View
    {
        // ChatConversation stores all data in its own fields, not in relations
        return view('admin.chatbot.show', compact('conversation'));
    }

    /**
     * Submit feedback for a conversation.
     */
    public function feedback(Request $request, ChatConversation $conversation)
    {
        $validated = $request->validate([
            'rating' => 'required|in:positive,negative',
            'comment' => 'nullable|string|max:500',
        ]);

        $feedbackData = [
            'chat_conversation_id' => $conversation->id,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'] ?? null,
        ];

        // Store response metadata if available (ChatConversation stores response directly)
        if ($conversation->response) {
            // Try to extract IDs from response if it's JSON
            $decoded = json_decode($conversation->response, true);
            if ($decoded && is_array($decoded)) {
                if (isset($decoded['knowledge_bank_id'])) {
                    $feedbackData['knowledge_bank_id'] = $decoded['knowledge_bank_id'];
                }
                if (isset($decoded['service_id'])) {
                    $feedbackData['service_id'] = $decoded['service_id'];
                }
            }
        }

        KnowledgeBankFeedback::create($feedbackData);

        return redirect()
            ->back()
            ->with('success', 'Feedback berhasil disimpan.');
    }

    /**
     * Log an unknown question (called from chatbot).
     */
    public static function logUnknownQuestion(string $question): void
    {
        $normalized = UnknownQuestion::normalize($question);
        
        // Check if this question already exists
        $existing = UnknownQuestion::where('normalized_question', $normalized)->first();
        
        if ($existing) {
            $existing->incrementCount();
        } else {
            UnknownQuestion::create([
                'question' => $question,
                'normalized_question' => $normalized,
                'count' => 1,
                'last_asked_at' => now(),
                'status' => 'unprocessed',
            ]);
        }
    }
}
