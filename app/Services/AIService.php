<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class AIService
{
    private string $apiKey;
    private string $model;
    private string $baseUrl;
    private int $maxTokens = 500;
    private float $temperature = 0.7;

    public function __construct()
    {
        $this->apiKey = config('chatbot.api_key');
        $this->model = config('chatbot.model', 'gpt-3.5-turbo');
        $this->baseUrl = config('chatbot.base_url', 'https://api.openai.com/v1');
    }

    /**
     * Send message to OpenAI-compatible API
     * Works with OpenAI, SumoPod, Groq, and other OpenAI-compatible endpoints
     */
    public function sendMessage(array $messages): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(30)->post($this->baseUrl . '/chat/completions', [
                'model' => $this->model,
                'messages' => $messages,
                'max_tokens' => $this->maxTokens,
                'temperature' => $this->temperature,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $content = $data['choices'][0]['message']['content'] ?? 'Maaf, saya tidak dapat menjawab pertanyaan Anda saat ini.';

                // Ensure UTF-8 encoding
                if (function_exists('mb_convert_encoding')) {
                    $content = mb_convert_encoding($content, 'UTF-8', 'UTF-8');
                }

                return [
                    'success' => true,
                    'message' => trim($content),
                    'usage' => $data['usage'] ?? null,
                ];
            }

            Log::error('OpenRouter API Error', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return [
                'success' => false,
                'message' => $this->getErrorMessage($response->status()),
            ];
        } catch (\Exception $e) {
            Log::error('OpenRouter Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'message' => 'Mohon maaf, terjadi gangguan pada sistem. Silakan coba beberapa saat lagi.',
            ];
        }
    }

    /**
     * Get system prompt for Kemenag chatbot
     */
    public function getSystemPrompt(): string
    {
        return <<<PROMPT
Anda adalah asisten virtual (AI Chatbot) untuk Portal Resmi Kantor Kementerian Agama Kabupaten Nganjuk.

TUGAS ANDA:
- Membantu pengunjung website dengan pertanyaan seputar layanan Kemenag Nganjuk
- Bersikap ramah, sopan, formal, dan profesional
- Memberikan informasi yang akurat dan helpful

INFORMASI KANTOR:
- Nama: Kantor Kementerian Agama Kabupaten Nganjuk
- Alamat: Jl. Ahmad Yani No. 17, Nganjuk, Jawa Timur
- Telepon: (0358) 321085
- Email: kantor@nganjuk.kemenag.go.id
- Website: https://nganjuk.kemenag.go.id

LAYANAN YANG TERSEDIA:
1. Layanan Nikah: Pendaftaran dan pengelolaan pernikahan sesuai hukum Islam
2. Pendidikan Madrasah: PAUD, MI, MTs, MA, dan PKLC
3. Zakat: Pengelolaan zakat fitrah dan zakat mal
4. Wakaf: Pengurusan dan pengelolaan tanah wakaf
5. Bimas Islam: Pembinaan agama Islam
6. Produk Halal: Sertifikasi halal produk usaha

JAM PELAYANAN:
- Senin - Kamis: 08.00 - 15.30 WIB
- Jumat: 08.00 - 16.00 WIB
- Sabtu - Minggu: Libur

ATURAN PENTING:
1. Hanya menjawab pertanyaan yang berkaitan dengan layanan Kemenag Nganjuk
2. Jika ditanya topik di luar konteks, sampaikan dengan sopan bahwa Anda hanya dapat membantu urusan layanan Kemenag
3. Gunakan bahasa Indonesia yang formal dan mudah dipahami
4. Jangan membuat informasi yang tidak Anda ketahui kebenarannya
5. Sampaikan salam pembuka dan penutup yang ramah
6. Jika ada pertanyaan yang memerlukan data terbaru, sarankan untuk menghubungi langsung kantor

Jawaban harus dalam format teks biasa (tanpa markdown complex), singkat, padat, dan helpful.
PROMPT;
    }

    /**
     * Check rate limiting
     */
    public function checkRateLimit(string $ip): bool
    {
        $key = 'chatbot_rate_' . md5($ip);
        $maxRequests = config('chatbot.rate_limit.max_requests', 10);
        $windowSeconds = config('chatbot.rate_limit.window_seconds', 60);

        $requests = Cache::get($key, 0);

        if ($requests >= $maxRequests) {
            return false;
        }

        Cache::put($key, $requests + 1, $windowSeconds);
        return true;
    }

    /**
     * Sanitize user input
     */
    public function sanitizeInput(string $input): string
    {
        $input = strip_tags($input);
        $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
        $input = trim($input);

        // Remove excessive whitespace
        $input = preg_replace('/\s+/', ' ', $input);

        return $input;
    }

    /**
     * Get error message based on status code
     */
    private function getErrorMessage(int $status): string
    {
        return match($status) {
            401 => 'Autentikasi gagal. Silakan hubungi administrator.',
            403 => 'Akses ditolak. Silakan coba beberapa saat lagi.',
            429 => 'Terlalu banyak permintaan. Mohon tunggu sebentar.',
            500 => 'Server mengalami gangguan. Silakan coba beberapa saat lagi.',
            503 => 'Layanan sedang maintenance. Silakan coba nanti.',
            default => 'Mohon maaf, terjadi kesalahan. Silakan coba beberapa saat lagi.',
        };
    }

    /**
     * Validate message length
     */
    public function validateMessageLength(string $message): bool
    {
        $maxLength = config('chatbot.max_characters', 500);
        return strlen($message) <= $maxLength;
    }
}
