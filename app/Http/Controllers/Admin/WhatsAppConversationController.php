<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WhatsappConversation;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class WhatsAppConversationController extends Controller
{
    /**
     * Display a listing of WhatsApp conversations (grouped by phone).
     */
    public function index(Request $request): View
    {
        // Get unique phones with their latest message and name
        $conversations = DB::table('whatsapp_conversations')
            ->select('phone', 
                DB::raw('MAX(created_at) as last_message_at'),
                DB::raw('COUNT(*) as message_count'),
                DB::raw('SUM(CASE WHEN role = "user" THEN 1 ELSE 0 END) as user_messages'),
                DB::raw('SUM(CASE WHEN role = "assistant" THEN 1 ELSE 0 END) as assistant_messages'),
                DB::raw('MAX(CASE WHEN role = "user" THEN name ELSE NULL END) as contact_name'))
            ->groupBy('phone')
            ->orderByDesc('last_message_at')
            ->paginate(20);

        // Transform last_message_at to Carbon
        $conversations->getCollection()->transform(function ($item) {
            $item->last_message_at = Carbon::parse($item->last_message_at);
            // Format phone for display (62xxx -> 0xxx)
            $item->phone_display = str_starts_with($item->phone, '62') ? '0' . substr($item->phone, 2) : $item->phone;
            return $item;
        });

        $totalMessages = WhatsappConversation::count();
        $totalContacts = DB::table('whatsapp_conversations')->distinct('phone')->count('phone');

        return view('admin.whatsapp-conversations.index', 
            compact('conversations', 'totalMessages', 'totalContacts'));
    }

    /**
     * Display chat view for a specific phone.
     */
    public function show(string $phone): View
    {
        $originalPhone = $phone; // Keep original for delete operations
        
        $messages = WhatsappConversation::where('phone', $phone)
            ->orderBy('created_at', 'asc')
            ->get();

        $phoneDisplay = $this->formatPhone($phone);
        
        // Get contact info
        $firstUser = $messages->where('role', 'user')->first();
        $contactName = $firstUser->name ?? $phoneDisplay;

        return view('admin.whatsapp-conversations.show', 
            compact('messages', 'phone', 'phoneDisplay', 'contactName', 'originalPhone'));
    }

    /**
     * Delete all conversations for a phone.
     */
    public function destroy(string $phone)
    {
        WhatsappConversation::where('phone', $phone)->delete();

        return redirect()
            ->route('admin.whatsapp-conversations.index')
            ->with('success', 'Percakapan berhasil dihapus.');
    }

    /**
     * Delete all WhatsApp conversations.
     */
    public function destroyAll()
    {
        WhatsappConversation::truncate();

        return redirect()
            ->route('admin.whatsapp-conversations.index')
            ->with('success', 'Semua percakapan berhasil dihapus.');
    }

    /**
     * Format phone number for display.
     */
    private function formatPhone(string $phone): string
    {
        // Convert 62xxx to 0xxx
        if (str_starts_with($phone, '62')) {
            return '0' . substr($phone, 2);
        }
        return $phone;
    }
}
