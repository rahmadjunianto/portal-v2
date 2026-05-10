<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
     * Get singleton instance (returns the single Setting model).
     */
    public static function getInstance(): ?Setting
    {
        return static::first();
    }

    /**
     * Get setting value by key.
     */
    public static function getValue(string $key, $default = null)
    {
        $setting = static::first();
        if (!$setting) {
            return $default;
        }

        return $setting->{$key} ?? $default;
    }

    /**
     * Set setting value by key.
     */
    public static function setValue(string $key, $value): void
    {
        $setting = static::firstOrCreate(['id' => 1], ['site_name' => 'Portal Kemenag Nganjuk']);
        $setting->{$key} = $value;
        $setting->save();
    }

    /**
     * Get singleton instance (alias for getInstance).
     */
    public static function getSingleton(): ?Setting
    {
        return static::first();
    }
}
