<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UnknownQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'question',
        'normalized_question',
        'count',
        'last_asked_at',
        'status',
        'suggested_service_id',
    ];

    protected $casts = [
        'count' => 'integer',
        'last_asked_at' => 'datetime',
        'status' => 'string',
    ];

    /**
     * Get the suggested service
     */
    public function suggestedService(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'suggested_service_id');
    }

    /**
     * Scope untuk belum diproses
     */
    public function scopeUnprocessed($query)
    {
        return $query->where('status', 'unprocessed');
    }

    /**
     * Scope untuk sorting by popularity
     */
    public function scopePopular($query)
    {
        return $query->orderByDesc('count');
    }

    /**
     * Increment count and update last_asked_at
     */
    public function incrementCount(): void
    {
        $this->increment('count');
        $this->update(['last_asked_at' => now()]);
    }

    /**
     * Normalize question for deduplication
     */
    public static function normalize(string $question): string
    {
        // Lowercase, trim, remove extra spaces
        return strtolower(trim(preg_replace('/\s+/', ' ', $question)));
    }
}
