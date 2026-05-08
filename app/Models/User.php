<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'name',
        'email',
        'phone',
        'photo',
        'password',
        'role_name',
        'is_active',
        'legacy_username',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    // ==================== RELATIONSHIPS ====================

    /**
     * Get all posts written by this user.
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'author_id');
    }

    /**
     * Get all pages written by this user.
     */
    public function pages(): HasMany
    {
        return $this->hasMany(Page::class, 'author_id');
    }

    /**
     * Get all agendas created by this user.
     */
    public function agendas(): HasMany
    {
        return $this->hasMany(Agenda::class, 'author_id');
    }

    // ==================== ACCESSORS & MUTATORS ====================

    /**
     * Check if user is an admin.
     */
    public function isAdmin(): bool
    {
        return $this->role_name === 'admin';
    }

    /**
     * Check if user is active.
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }

    /**
     * Get the user's display name.
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->name ?: $this->username ?: 'Unknown';
    }
}
