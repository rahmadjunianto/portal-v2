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
        'legacy_id',
        'menu_id',
        'parent_id',
        'title',
        'url',
        'is_active',
        'target_blank',
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
            'target_blank' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    // ==================== RELATIONSHIPS ====================

    /**
     * Get the menu that owns this item.
     */
    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }

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
     * Get the link attributes for HTML anchor tag.
     */
    public function getLinkAttributesAttribute(): array
    {
        $attributes = [];

        if ($this->target_blank) {
            $attributes['target'] = '_blank';
            $attributes['rel'] = 'noopener noreferrer';
        }

        return $attributes;
    }

    /**
     * Check if URL is internal (relative path).
     */
    public function isInternalLink(): bool
    {
        if (!$this->url) {
            return false;
        }

        return !str_starts_with($this->url, 'http://')
            && !str_starts_with($this->url, 'https://')
            && !str_starts_with($this->url, '//');
    }

    /**
     * Check if URL is external.
     */
    public function isExternalLink(): bool
    {
        return !$this->isInternalLink();
    }

    /**
     * Get breadcrumb trail (ancestors).
     */
    public function getAncestors(): array
    {
        $ancestors = [];
        $current = $this;

        while ($current->parent) {
            array_unshift($ancestors, $current->parent);
            $current = $current->parent;
        }

        return $ancestors;
    }
}
