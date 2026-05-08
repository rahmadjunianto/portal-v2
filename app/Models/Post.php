<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'legacy_id',
        'category_id',
        'author_id',
        'title',
        'subtitle',
        'slug',
        'content',
        'thumbnail',
        'image_caption',
        'youtube_url',
        'type',
        'is_headline',
        'is_featured',
        'is_active',
        'status',
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
            'is_headline' => 'boolean',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    // ==================== RELATIONSHIPS ====================

    /**
     * Get the category that owns the post.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(PostCategory::class, 'category_id');
    }

    /**
     * Get the author that owns the post.
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Get the tags for the post.
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'post_tag');
    }

    // ==================== SCOPES ====================

    /**
     * Scope for published posts.
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->where('is_active', true);
    }

    /**
     * Scope for draft posts.
     */
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    /**
     * Scope for headline posts.
     */
    public function scopeHeadline($query)
    {
        return $query->where('is_headline', true);
    }

    /**
     * Scope for featured posts.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope for posts by type.
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
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
     * Check if post is published.
     */
    public function isPublished(): bool
    {
        return $this->status === 'published' && $this->is_active;
    }
}
