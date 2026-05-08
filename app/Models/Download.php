<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Download extends Model
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
        'slug',
        'file_name',
        'file_path',
        'file_type',
        'file_size',
        'downloads_count',
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
            'file_size' => 'integer',
            'downloads_count' => 'integer',
            'published_at' => 'datetime',
        ];
    }

    // ==================== SCOPES ====================

    /**
     * Scope for downloads by file type.
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('file_type', $type);
    }

    /**
     * Scope to order by most downloaded.
     */
    public function scopeMostDownloaded($query)
    {
        return $query->orderBy('downloads_count', 'desc');
    }

    // ==================== ACCESSORS & MUTATORS ====================

    /**
     * Increment the downloads count.
     */
    public function incrementDownloads(): void
    {
        $this->increment('downloads_count');
    }

    /**
     * Get human-readable file size.
     */
    public function getFileSizeFormattedAttribute(): string
    {
        if (!$this->file_size) {
            return 'Unknown';
        }

        $units = ['B', 'KB', 'MB', 'GB'];
        $unitIndex = 0;
        $size = (float) $this->file_size;

        while ($size >= 1024 && $unitIndex < count($units) - 1) {
            $size /= 1024;
            $unitIndex++;
        }

        return round($size, 2) . ' ' . $units[$unitIndex];
    }

    /**
     * Get file extension from file_name.
     */
    public function getExtensionAttribute(): string
    {
        return pathinfo($this->file_name, PATHINFO_EXTENSION);
    }
}
