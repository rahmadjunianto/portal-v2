<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KnowledgeBank extends Model
{
    use HasFactory;

    protected $fillable = [
        'question',
        'answer',
        'category',
        'tags',
        'is_active',
        'priority',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'priority' => 'integer',
    ];

    /**
     * Scope untuk data aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope berdasarkan kategori
     */
    public function scopeCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Search by question or tags
     */
    public function scopeSearch($query, string $search)
    {
        $search = strtolower(trim($search));
        
        return $query->where(function($q) use ($search) {
            $q->whereRaw('LOWER(question) LIKE ?', ["%{$search}%"])
              ->orWhereRaw('LOWER(tags) LIKE ?', ["%{$search}%"])
              ->orWhereRaw('LOWER(answer) LIKE ?', ["%{$search}%"]);
        });
    }

    /**
     * Get categories list
     */
    public static function getCategories(): array
    {
        return [
            'nikah' => 'Layanan Nikah',
            'pendidikan' => 'Pendidikan Madrasah',
            'zakat' => 'Zakat',
            'wakaf' => 'Wakaf',
            'bimas' => 'Bimas Islam',
            'halal' => 'Produk Halal',
            'umum' => 'Informasi Umum',
            'kepegawaian' => 'Kepegawaian',
        ];
    }
}
