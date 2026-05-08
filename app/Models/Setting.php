<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'site_name',
        'site_url',
        'email',
        'phone',
        'logo',
        'favicon',
        'meta_description',
        'meta_keywords',
        'maps_embed',
        'footer_description',
        'facebook_url',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'maps_embed' => 'array',
        ];
    }

    // ==================== SINGLETON PATTERN ====================

    /**
     * Get the singleton setting instance.
     */
    public static function getInstance(): self
    {
        return static::firstOrCreate([], [
            'site_name' => config('app.name', 'Portal Kemenag'),
        ]);
    }

    // ==================== HELPER METHODS ====================

    /**
     * Get logo URL or default.
     */
    public function getLogoUrlAttribute(): ?string
    {
        if (!$this->logo) {
            return null;
        }

        return asset('storage/' . $this->logo);
    }

    /**
     * Get favicon URL or default.
     */
    public function getFaviconUrlAttribute(): ?string
    {
        if (!$this->favicon) {
            return null;
        }

        return asset('storage/' . $this->favicon);
    }

    /**
     * Get meta keywords as array.
     */
    public function getKeywordsArrayAttribute(): array
    {
        if (!$this->meta_keywords) {
            return [];
        }

        return array_filter(array_map('trim', explode(',', $this->meta_keywords)));
    }
}
