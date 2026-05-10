<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

    protected $table = 'pages';

    protected $fillable = [
        'title',
        'slug',
        'content',
        'excerpt',
        'page_type',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'published_at',
        'legacy_id',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];
}
