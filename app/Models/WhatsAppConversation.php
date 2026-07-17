<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsappConversation extends Model
{
    protected $fillable = [
        'phone',
        'session_id',
        'role',
        'content',
        'name',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function scopeByPhone($query, $phone)
    {
        return $query->where('phone', $phone);
    }

    public function scopeUserMessages($query)
    {
        return $query->where('role', 'user');
    }

    public function scopeAssistantMessages($query)
    {
        return $query->where('role', 'assistant');
    }

    public function scopeDateRange($query, $start, $end)
    {
        return $query->whereBetween('created_at', [$start, $end]);
    }

    public function scopeForPhone($query, string $phone)
    {
        return $query->where('phone', $phone);
    }

    /**
     * Get recent messages for a phone number
     */
    public static function getRecentMessages(string $phone, int $limit = 10): array
    {
        $messages = static::where('phone', $phone)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();

        return $messages->map(function ($msg) {
            return [
                'role' => $msg->role,
                'content' => $msg->content,
                'name' => $msg->name,
                'created_at' => $msg->created_at->toISOString(),
            ];
        })->toArray();
    }
}
