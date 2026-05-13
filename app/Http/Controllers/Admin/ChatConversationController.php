<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChatConversation;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ChatConversationController extends Controller
{
    /**
     * Display a listing of chat conversations.
     */
    public function index(Request $request): View
    {
        $query = ChatConversation::query()
            ->orderBy('created_at', 'desc');

        // Filter by role
        if ($request->has('role') && $request->role) {
            $query->where('role', $request->role);
        }

        // Filter by success
        if ($request->has('status') && $request->status !== '') {
            if ($request->status === 'success') {
                $query->where('is_success', true);
            } else {
                $query->where('is_success', false);
            }
        }

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('user_name', 'like', "%{$search}%")
                    ->orWhere('user_email', 'like', "%{$search}%")
                    ->orWhere('message', 'like', "%{$search}%");
            });
        }

        $conversations = $query->paginate(15)->withQueryString();
        
        // Total tokens
        $totalTokens = ChatConversation::sum('tokens_used');
        $totalConversations = ChatConversation::count();

        return view('admin.chat-conversations.index', compact('conversations', 'totalTokens', 'totalConversations'));
    }

    /**
     * Delete a chat conversation.
     */
    public function destroy(int $id)
    {
        $conversation = ChatConversation::findOrFail($id);
        
        // Delete conversation
        $conversation->delete();

        return redirect()
            ->route('admin.chat-conversations.index')
            ->with('success', 'Chat conversation berhasil dihapus.');
    }
}
