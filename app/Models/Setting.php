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
     * Get or create the singleton setting.
     */
    public static function getOrCreate(): Setting
    {
        return static::firstOrCreate(
            ['id' => 1],
            ['site_name' => 'Portal Kemenag Nganjuk']
        );
    }
}
