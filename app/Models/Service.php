<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'description',
        'requirements',
        'workflow',
        'processing_time',
        'cost',
        'contact',
        'office',
        'website',
        'download_link',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the knowledge banks for this service
     */
    public function knowledgeBanks(): HasMany
    {
        return $this->hasMany(KnowledgeBank::class);
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
    public function scopeCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Generate formatted answer from service data
     */
    public function generateAnswer(): string
    {
        $parts = [];

        $parts[] = "📋 *{$this->name}*";
        $parts[] = "";

        if ($this->description) {
            $parts[] = $this->description;
            $parts[] = "";
        }

        if ($this->requirements) {
            $parts[] = "📌 *Persyaratan:*";
            $parts[] = $this->requirements;
            $parts[] = "";
        }

        if ($this->workflow) {
            $parts[] = "📝 *Alur/Langkah:*";
            $parts[] = $this->workflow;
            $parts[] = "";
        }

        if ($this->processing_time) {
            $parts[] = "⏱️ *Waktu Proses:* {$this->processing_time}";
            $parts[] = "";
        }

        if ($this->cost) {
            $parts[] = "💰 *Biaya:* {$this->cost}";
            $parts[] = "";
        }

        if ($this->contact) {
            $parts[] = "📞 *Kontak:* {$this->contact}";
            $parts[] = "";
        }

        if ($this->website) {
            $parts[] = "🌐 *Website:* {$this->website}";
            $parts[] = "";
        }

        $parts[] = "━━━━━━━━━━━━━━━━━━━━";
        $parts[] = "📍 *Kantor Kemenag Kabupaten Nganjuk*";
        $parts[] = "Jl. Ahmad Yani No. 17, Nganjuk";
        $parts[] = "Telp: (0358) 321085";

        return implode("\n", $parts);
    }

    /**
     * Get categories list
     */
    public static function getCategories(): array
    {
        return [
            'Kepegawaian' => 'Kepegawaian',
            'Umum & FKUB' => 'Umum & FKUB',
            'Pendidikan Madrasah' => 'Pendidikan Madrasah',
            'Pendidikan Diniyah dan Pondok Pesantren' => 'Pendidikan Diniyah & Pondok Pesantren',
            'Pendidikan Agama Islam' => 'Pendidikan Agama Islam',
            'Bimbingan Masyarakat Islam' => 'Bimbingan Masyarakat Islam',
            'Zakat dan Wakaf' => 'Zakat dan Wakaf',
            'Kearsipan' => 'Kearsipan',
            'Pembinaan Agama Kristen' => 'Pembinaan Agama Kristen',
            'Kehumasan' => 'Kehumasan',
        ];
    }
}
