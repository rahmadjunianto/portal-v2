<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Page extends Model
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
        'content',
        'cover_image',
        'page_type',
        'views',
        'published_at',
    ];

    /**
     * Get the attributes that should be cast.
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
     * Get the author that owns the page.
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    // ==================== SCOPES ====================

    /**
     * Scope for pages by type.
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('page_type', $type);
    }

    /**
     * Scope for profil pages.
     */
    public function scopeProfil($query)
    {
        return $query->where('page_type', 'profil');
    }

    /**
     * Scope for unit pages.
     */
    public function scopeUnit($query)
    {
        return $query->where('page_type', 'unit');
    }

    // ==================== ACCESSORS & MUTATORS ====================

    /**
     * Increment the views count.
     */
    public function incrementViews(): void
    {
        $this->increment('views');
    }
}
