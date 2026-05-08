<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Agenda extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'legacy_id',
        'author_id',
        'title',
        'slug',
        'description',
        'location',
        'sender_name',
        'image',
        'start_date',
        'end_date',
        'event_time_text',
        'views',
        'published_at',
    ];

    /**
     * Get the attributes that should be cast.
     * Note: Date casting disabled during migration - handled by command
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
        ];
    }

    // ==================== RELATIONSHIPS ====================

    /**
     * Get the author that owns the agenda.
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    // ==================== SCOPES ====================

    /**
     * Scope for upcoming agendas (end_date >= today).
     */
    public function scopeUpcoming($query)
    {
        return $query->whereDate('end_date', '>=', now()->toDateString())
            ->orderBy('start_date', 'asc');
    }

    /**
     * Scope for past agendas (end_date < today).
     */
    public function scopePast($query)
    {
        return $query->whereDate('end_date', '<', now()->toDateString())
            ->orderBy('start_date', 'desc');
    }

    /**
     * Scope for ongoing agendas (today is between start and end date).
     */
    public function scopeOngoing($query)
    {
        return $query->whereDate('start_date', '<=', now()->toDateString())
            ->whereDate('end_date', '>=', now()->toDateString());
    }

    // ==================== ACCESSORS & MUTATORS ====================

    /**
     * Increment the views count.
     */
    public function incrementViews(): void
    {
        $this->increment('views');
    }

    /**
     * Check if agenda is upcoming.
     */
    public function isUpcoming(): bool
    {
        return $this->end_date >= now()->toDateString();
    }

    /**
     * Check if agenda is past.
     */
    public function isPast(): bool
    {
        return $this->end_date < now()->toDateString();
    }
}
