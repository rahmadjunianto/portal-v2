<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExternalLink extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'legacy_id',
        'title',
        'description',
        'url',
        'category',
        'is_active',
        'sort_order',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    // ==================== SCOPES ====================

    /**
     * Scope for active links.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to order by sort order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc');
    }

    /**
     * Scope for links by category.
     */
    public function scopeInCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    // ==================== ACCESSORS & MUTATORS ====================

    /**
     * Get domain from URL.
     */
    public function getDomainAttribute(): ?string
    {
        if (!$this->url) {
            return null;
        }

        $parsed = parse_url($this->url);
        return $parsed['host'] ?? null;
    }

    /**
     * Check if URL is valid.
     */
    public function isValidUrl(): bool
    {
        return filter_var($this->url, FILTER_VALIDATE_URL) !== false;
    }
}
