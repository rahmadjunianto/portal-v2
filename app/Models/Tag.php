<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'slug',
    ];

    // ==================== RELATIONSHIPS ====================

    /**
     * Get the posts that have this tag.
     */
    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'post_tag');
    }

    /**
     * Get only published posts with this tag.
     */
    public function publishedPosts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'post_tag')
            ->where('status', 'published')
            ->where('is_active', true);
    }

    // ==================== SCOPES ====================

    /**
     * Scope to find by slug.
     */
    public function scopeBySlug($query, string $slug)
    {
        return $query->where('slug', $slug);
    }
}
