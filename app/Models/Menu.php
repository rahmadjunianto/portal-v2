<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Menu extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'location',
    ];

    // ==================== CONSTANTS ====================

    public const LOCATION_HEADER = 'header';
    public const LOCATION_FOOTER = 'footer';
    public const LOCATION_SIDEBAR = 'sidebar';

    // ==================== RELATIONSHIPS ====================

    /**
     * Get all items in this menu.
     */
    public function items(): HasMany
    {
        return $this->hasMany(MenuItem::class, 'menu_id');
    }

    /**
     * Get only top-level items (no parent).
     */
    public function rootItems(): HasMany
    {
        return $this->hasMany(MenuItem::class, 'menu_id')
            ->whereNull('parent_id')
            ->orderBy('sort_order', 'asc');
    }

    /**
     * Get active items only.
     */
    public function activeItems(): HasMany
    {
        return $this->hasMany(MenuItem::class, 'menu_id')
            ->where('is_active', true)
            ->orderBy('sort_order', 'asc');
    }

    // ==================== SCOPES ====================

    /**
     * Scope for header menus.
     */
    public function scopeHeader($query)
    {
        return $query->where('location', self::LOCATION_HEADER);
    }

    /**
     * Scope for footer menus.
     */
    public function scopeFooter($query)
    {
        return $query->where('location', self::LOCATION_FOOTER);
    }

    /**
     * Scope for sidebar menus.
     */
    public function scopeSidebar($query)
    {
        return $query->where('location', self::LOCATION_SIDEBAR);
    }

    // ==================== STATIC METHODS ====================

    /**
     * Get or create header menu.
     */
    public static function getOrCreateHeader(): self
    {
        return static::firstOrCreate(
            ['name' => 'Main Menu', 'location' => self::LOCATION_HEADER],
            ['name' => 'Main Menu', 'location' => self::LOCATION_HEADER]
        );
    }

    /**
     * Get or create footer menu.
     */
    public static function getOrCreateFooter(): self
    {
        return static::firstOrCreate(
            ['name' => 'Footer Menu', 'location' => self::LOCATION_FOOTER],
            ['name' => 'Footer Menu', 'location' => self::LOCATION_FOOTER]
        );
    }
}
