<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KnowledgeBankFeedback extends Model
{
    use HasFactory;

    protected $table = 'knowledge_bank_feedback';

    protected $fillable = [
        'chat_conversation_id',
        'knowledge_bank_id',
        'service_id',
        'rating',
        'comment',
    ];

    /**
     * Get the chat conversation
     */
    public function chatConversation(): BelongsTo
    {
        return $this->belongsTo(ChatConversation::class);
    }

    /**
     * Get the knowledge bank
     */
    public function knowledgeBank(): BelongsTo
    {
        return $this->belongsTo(KnowledgeBank::class);
    }

    /**
     * Get the service
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Scope untuk feedback positif
     */
    public function scopePositive($query)
    {
        return $query->where('rating', 'positive');
    }

    /**
     * Scope untuk feedback negatif
     */
    public function scopeNegative($query)
    {
        return $query->where('rating', 'negative');
    }
}
