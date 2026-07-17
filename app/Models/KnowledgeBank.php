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
     * Scope berdasarkan service
     */
    public function scopeByService($query, $serviceId)
    {
        return $query->where('service_id', $serviceId);
    }

    /**
     * Scope untuk FAQ umum (tanpa service)
     */
    public function scopeGeneralFaq($query)
    {
        return $query->whereNull('service_id');
    }

    /**
     * Search by question or tags
     */
    public function scopeSearch($query, string $search)
    {
        $search = strtolower(trim($search));
        
        return $query->where(function($q) use ($search) {
            $q->whereRaw('LOWER(question) LIKE ?', ["%{$search}%"])
              ->orWhereRaw('LOWER(tags) LIKE ?', ["%{$search}%"]);
        });
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

    /**
     * Check if this is a service-linked question
     */
    public function isServiceLinked(): bool
    {
        return $this->service_id !== null;
    }
}
