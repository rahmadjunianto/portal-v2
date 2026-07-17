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
        $query = ChatConversation::query();

        // Filter by user name (stored in user_name field, not relation)
        if ($request->user_name) {
            $query->where('user_name', 'like', '%' . $request->user_name . '%');
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
