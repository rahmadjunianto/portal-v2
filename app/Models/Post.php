<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;

class Post extends Model
{
    use HasFactory;

    protected $table = 'posts';

    protected $fillable = [
        'title',
        'slug',
        'subtitle',
        'content',
        'thumbnail',
        'image_caption',
        'youtube_url',
        'type',
        'author_id',
        'category_id',
        'is_headline',
        'is_featured',
        'is_active',
        'status',
        'views',
        'published_at',
        'legacy_id',
    ];

    protected $casts = [
        'is_headline' => 'boolean',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'published_at' => 'datetime',
        'views' => 'integer',
    ];

    /**
     * Get the author that owns the post.
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Get the category that owns the post.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(PostCategory::class, 'category_id');
    }

    /**
     * Get the tags for the post.
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'post_tag', 'post_id', 'tag_id');
    }

    /**
     * Scope a query to only include published posts.
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published')
            ->where('is_active', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    /**
     * Increment the views count.
     */
    public function incrementViews(): void
    {
        $this->increment('views');
    }

    /**
     * Get excerpt from content.
     */
    public function getExcerptAttribute($value): ?string
    {
        if ($value === null) {
            return null;
        }
        $content = strip_tags($value);
        if (strlen($content) <= 150) {
            return $content;
        }
        return substr($content, 0, 150) . '...';
    }

    /**
     * Get the thumbnail URL (handles both old and new format).
     */
    public function getThumbnailUrlAttribute(): ?string
    {
        if (!$this->thumbnail) {
            return null;
        }

        // Check for new format (WebP variants with size prefix)
        foreach (['large', 'medium', 'small'] as $sizeName) {
            $webpPath = "posts/{$sizeName}-{$this->thumbnail}.webp";
            if (\Illuminate\Support\Facades\Storage::disk('public')->exists($webpPath)) {
                return asset('storage/' . $webpPath);
            }
        }

        // Check old format (direct file in posts folder)
        $oldPath = "posts/{$this->thumbnail}";
        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($oldPath)) {
            return asset('storage/' . $oldPath);
        }

        // Fallback to original behavior
        return \Illuminate\Support\Facades\Storage::url($this->thumbnail);
    }
}
