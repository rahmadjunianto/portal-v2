<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KnowledgeBank extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id',
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
     * Get the service that owns this knowledge
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

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

    /**
     * Get the resolved answer - either from answer field or generate from service
     */
    public function getResolvedAnswer(): ?string
    {
        // If answer exists, return it directly
        if ($this->answer) {
            return $this->answer;
        }

        // Otherwise, generate from service
        if ($this->service) {
            return $this->service->generateAnswer();
        }

        return null;
    }
}
