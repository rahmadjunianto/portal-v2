<?php

namespace App\Services;

use App\Models\KnowledgeBank;
use Illuminate\Support\Facades\Log;

class KnowledgeBankService
{
    private int $minMatchScore = 30; // Minimum similarity score (0-100)

    /**
     * Find best matching answer from knowledge bank
     */
    public function findAnswer(string $query): ?string
    {
        $query = $this->normalizeText($query);
        
        // Get all active knowledge entries ordered by priority
        $entries = KnowledgeBank::active()
            ->orderByDesc('priority')
            ->get();

        $bestMatch = null;
        $bestScore = 0;

        foreach ($entries as $entry) {
            $score = $this->calculateMatchScore($query, $entry);
            
            if ($score > $bestScore && $score >= $this->minMatchScore) {
                $bestScore = $score;
                $bestMatch = $entry;
            }
        }

        if ($bestMatch) {
            Log::channel('whatsapp')->info('Knowledge Bank Match', [
                'query' => $query,
                'matched_question' => $bestMatch->question,
                'score' => $bestScore,
                'source' => $bestMatch->answer ? 'direct' : 'generated',
            ]);
            
            // Return resolved answer (direct or generated from service)
            return $bestMatch->getResolvedAnswer();
        }

        return null;
    }

    /**
     * Calculate match score between query and entry
     */
    private function calculateMatchScore(string $query, KnowledgeBank $entry): int
    {
        $score = 0;
        
        // Check question match (highest weight)
        $question = $this->normalizeText($entry->question);
        if ($this->contains($question, $query)) {
            $score += 60;
        }
        
        // Check tags match
        if ($entry->tags) {
            $tags = array_map('trim', explode(',', strtolower($entry->tags)));
            foreach ($tags as $tag) {
                if (strlen($tag) >= 3 && $this->contains($query, $tag)) {
                    $score += 25;
                    break;
                }
            }
        }
        
        // Check answer match (lowest weight)
        $answer = $this->normalizeText($entry->answer);
        if ($this->contains($answer, $query)) {
            $score += 15;
        }

        return min($score, 100);
    }

    /**
     * Normalize text for comparison
     */
    private function normalizeText(string $text): string
    {
        $text = strtolower($text);
        // Hapus karakter khusus, jaga spasi dan alphanumeric
        $text = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $text);
        // Normalisasi spasi
        $text = preg_replace('/\s+/', ' ', $text);
        return trim($text);
    }

    /**
     * Check if text1 contains text2
     */
    private function contains(string $text1, string $text2): bool
    {
        return str_contains($text1, $text2) || str_contains($text2, $text1);
    }

    /**
     * Get all categories with entry count
     */
    public function getCategoriesWithCount(): array
    {
        $categories = KnowledgeBank::getCategories();
        $result = [];
        
        foreach ($categories as $key => $name) {
            $count = KnowledgeBank::active()->where('category', $key)->count();
            if ($count > 0) {
                $result[$key] = [
                    'name' => $name,
                    'count' => $count,
                ];
            }
        }
        
        return $result;
    }
}
