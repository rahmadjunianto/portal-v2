<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChatConversation;
use App\Models\KnowledgeBankFeedback;
use App\Models\UnknownQuestion;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ChatbotController extends Controller
{
    /**
     * Display chat logs.
     */
    public function logs(Request $request): View
    {
        $query = ChatConversation::with('user');

        // Filter by user
        if ($request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by date range
        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $conversations = $query->latest()->paginate(25);

        return view('admin.chatbot.logs', compact('conversations'));
    }

    /**
     * Display a single chat conversation.
     */
    public function show(ChatConversation $conversation): View
    {
        $conversation->load(['messages', 'user']);
        
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

        // Determine what to link feedback to
        $lastAssistantMessage = $conversation->messages()
            ->where('sender', 'assistant')
            ->latest()
            ->first();

        $feedbackData = [
            'chat_conversation_id' => $conversation->id,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'] ?? null,
        ];

        // If there's a knowledge bank answer, link to it
        if ($lastAssistantMessage && $lastAssistantMessage->metadata) {
            $metadata = is_string($lastAssistantMessage->metadata) 
                ? json_decode($lastAssistantMessage->metadata, true) 
                : $lastAssistantMessage->metadata;
            
            if (isset($metadata['knowledge_bank_id'])) {
                $feedbackData['knowledge_bank_id'] = $metadata['knowledge_bank_id'];
            }
            if (isset($metadata['service_id'])) {
                $feedbackData['service_id'] = $metadata['service_id'];
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
