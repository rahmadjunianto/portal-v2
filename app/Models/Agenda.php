<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Agenda extends Model
{
    use HasFactory;

    protected $table = 'agendas';

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

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'published_at' => 'datetime',
        'views' => 'integer',
    ];

    /**
     * Scope a query to only include upcoming agendas.
     */
    public function scopeUpcoming(Builder $query): Builder
    {
        return $query->where(function ($q) {
            $q->where('start_date', '>=', now()->toDateString())
              ->orWhere('end_date', '>=', now()->toDateString());
        })->whereNotNull('published_at');
    }

    /**
     * Scope a query to only include published agendas.
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->whereNotNull('published_at');
    }

    /**
     * Get the author.
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
