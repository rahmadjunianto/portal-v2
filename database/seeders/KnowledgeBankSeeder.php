<?php

namespace Database\Seeders;

use App\Models\KnowledgeBank;
use App\Models\Service;
use Illuminate\Database\Seeder;

class KnowledgeBankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all services
        $services = Service::all();

        $knowledgeEntries = [];

        foreach ($services as $service) {
            $serviceName = $service->name;
            $category = $this->mapCategoryToSlug($service->category);
            $tags = $this->generateTags($serviceName);

            // Generate 5 question variations for each service
            $questions = [
                "Apa saja syarat {$serviceName}?",
                "Bagaimana cara mengurus {$serviceName}?",
                "Cara daftar {$serviceName} bagaimana?",
                "Berapa lama proses {$serviceName}?",
                "Apakah {$serviceName} gratis?",
                "Dokumen apa saja untuk {$serviceName}?",
                "{$serviceName} bagaimana prosedurnya?",
                "{$serviceName} butuh apa saja?",
            ];

            // Take 5 random questions
            $selectedQuestions = array_slice($questions, 0, 5);

            foreach ($selectedQuestions as $index => $question) {
                $knowledgeEntries[] = [
                    'service_id' => $service->id,
                    'question' => $question,
                    'answer' => $service->generateAnswer(),
                    'category' => $category,
                    'tags' => $tags,
                    'priority' => 50 - $index * 5, // Higher priority for first questions
                    'is_active' => true,
                ];
            }
        }

        foreach ($knowledgeEntries as $entry) {
            KnowledgeBank::create($entry);
        }
    }

    /**
     * Map category to slug
     */
    private function mapCategoryToSlug(string $category): string
    {
        $mapping = [
            'Kepegawaian' => 'kepegawaian',
            'Umum & FKUB' => 'umum',
            'Pendidikan Madrasah' => 'pendidikan',
            'Pendidikan Diniyah dan Pondok Pesantren' => 'pontren',
            'Pendidikan Agama Islam' => 'pai',
            'Bimbingan Masyarakat Islam' => 'bimas',
            'Zakat dan Wakaf' => 'zakat',
            'Kearsipan' => 'kearsipan',
            'Pembinaan Agama Kristen' => 'kristen',
            'Kehumasan' => 'kehumasan',
        ];

        return $mapping[$category] ?? 'umum';
    }

    /**
     * Generate tags from service name
     */
    private function generateTags(string $serviceName): string
    {
        $tags = [];

        // Extract keywords from service name
        $words = explode(' ', strtolower($serviceName));
        $stopWords = ['surat', 'rekomendasi', 'izin', 'permohonan', 'penerbitan', 'pengajuan', 'perubahan', 'pengesahan', 'pembinaan', 'dan', 'di', 'untuk', 'bagi'];

        foreach ($words as $word) {
            if (strlen($word) > 3 && !in_array($word, $stopWords)) {
                $tags[] = $word;
            }
        }

        return implode(', ', array_slice($tags, 0, 8));
    }
}
