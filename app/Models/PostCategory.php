<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PostCategory extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'legacy_id',
        'name',
        'slug',
        'is_active',
        'show_in_sidebar',
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
            'show_in_sidebar' => 'boolean',
        ];
    }

    // ==================== RELATIONSHIPS ====================

    /**
     * Get all posts in this category.
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'category_id');
    }

    /**
     * Get only published posts in this category.
     */
    public function publishedPosts(): HasMany
    {
        return $this->hasMany(Post::class, 'category_id')
            ->where('status', 'published')
            ->where('is_active', true);
    }

    // ==================== SCOPES ====================

    /**
     * Scope for active categories.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for sidebar categories.
     */
    public function scopeShowInSidebar($query)
    {
        return $query->where('show_in_sidebar', true);
    }
}
