<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    use HasFactory;

    protected $table = 'settings';

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
        'instagram_url',
        'youtube_url',
        'twitter_url',
    ];

    /**
     * Cache key for settings singleton data.
     */
    public const CACHE_KEY = 'settings_singleton_data';

    /**
     * Cache lifetime in seconds (24 hours).
     */
    public const CACHE_TTL = 86400;

    /**
     * In-memory cache to avoid repeated database queries per request.
     */
    protected static ?Setting $instance = null;

    /**
     * Boot method to clear cache on model changes.
     */
    protected static function booted(): void
    {
        static::saved(function () {
            Cache::forget(self::CACHE_KEY);
            self::$instance = null;
        });

        static::deleted(function () {
            Cache::forget(self::CACHE_KEY);
            self::$instance = null;
        });
    }

    /**
     * Get singleton instance (returns the single Setting model with caching).
     */
    public static function getInstance(): ?Setting
    {
        if (self::$instance !== null) {
            return self::$instance;
        }

        // Check cache for array data
        $cached = Cache::get(self::CACHE_KEY);
        
        if (is_array($cached)) {
            self::$instance = new self($cached);
            self::$instance->exists = true;
            return self::$instance;
        }
        
        $setting = static::first();
        
        if ($setting) {
            Cache::put(self::CACHE_KEY, $setting->toArray(), self::CACHE_TTL);
            self::$instance = $setting;
        }
        
        return $setting;
    }

    /**
     * Get or create the singleton setting (with caching).
     */
    public static function getOrCreate(): Setting
    {
        if (self::$instance !== null) {
            return self::$instance;
        }

        // Check cache for array data
        $cached = Cache::get(self::CACHE_KEY);
        
        if (is_array($cached)) {
            self::$instance = new self($cached);
            self::$instance->exists = true;
            return self::$instance;
        }
        
        $setting = static::firstOrCreate(
            ['id' => 1],
            ['site_name' => 'Portal Kemenag Nganjuk']
        );
        
        Cache::put(self::CACHE_KEY, $setting->toArray(), self::CACHE_TTL);
        self::$instance = $setting;
        
        return $setting;
    }
}
