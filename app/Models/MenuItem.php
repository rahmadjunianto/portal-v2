<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MenuItem extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'parent_id',
        'title',
        'url',
        'sort_order',
        'is_active',
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

    // ==================== RELATIONSHIPS ====================

    /**
     * Get the parent menu item.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class, 'parent_id');
    }

    /**
     * Get child menu items.
     */
    public function children(): HasMany
    {
        return $this->hasMany(MenuItem::class, 'parent_id')
            ->orderBy('sort_order', 'asc');
    }

    /**
     * Get active child menu items.
     */
    public function activeChildren(): HasMany
    {
        return $this->hasMany(MenuItem::class, 'parent_id')
            ->where('is_active', true)
            ->orderBy('sort_order', 'asc');
    }

    // ==================== SCOPES ====================

    /**
     * Scope for active items.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for root items (no parent).
     */
    public function scopeRoot($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Scope to order by sort order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc');
    }

    // ==================== ACCESSORS & MUTATORS ====================

    /**
     * Check if this item has children.
     */
    public function hasChildren(): bool
    {
        return $this->children()->exists();
    }

    /**
     * Check if this is a child item.
     */
    public function isChild(): bool
    {
        return $this->parent_id !== null;
    }

    /**
     * Get the depth level of this item.
     */
    public function getDepth(): int
    {
        $depth = 0;
        $current = $this;

        while ($current->parent) {
            $depth++;
            $current = $current->parent;
        }

        return $depth;
    }

    /**
     * Check if URL is external (starts with http/https).
     */
    public function isExternal(): bool
    {
        if (!$this->url) {
            return false;
        }

        return str_starts_with($this->url, 'http://')
            || str_starts_with($this->url, 'https://');
    }

    /**
     * Get link target attribute.
     */
    public function getTargetAttribute(): ?string
    {
        return $this->isExternal ? '_blank' : null;
    }

    /**
     * Get children collection - selalu pakai data yang sudah di-load.
     * Tidak pernah menjalankan query baru.
     */
    public function getChildrenCollectionAttribute(): \Illuminate\Support\Collection
    {
        // Jika children sudah di-load via relation, pakai itu
        if (isset($this->relations['children'])) {
            return collect($this->relations['children']);
        }
        // Fallback ke empty collection (bukan query baru)
        return collect();
    }
}
