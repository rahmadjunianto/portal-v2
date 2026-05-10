<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatConversation extends Model
{
    protected $fillable = [
        'ip_address',
        'user_name',
        'user_email',
        'user_phone',
        'user_agent',
        'role',
        'message',
        'response',
        'tokens_used',
        'is_success',
        'error_message',
    ];

    protected $casts = [
        'is_success' => 'boolean',
        'tokens_used' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function scopeUserMessages($query)
    {
        return $query->where('role', 'user');
    }

    public function scopeSuccessful($query)
    {
        return $query->where('is_success', true);
    }

    public function scopeByIp($query, $ip)
    {
        return $query->where('ip_address', $ip);
    }

    public function scopeDateRange($query, $start, $end)
    {
        return $query->whereBetween('created_at', [$start, $end]);
    }
}
