<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class WhatsAppService
{
    private AIService $aiService;
    private int $maxHistoryMessages = 10;
    private int $rateLimitMaxRequests = 5;
    private int $rateLimitWindowSeconds = 60;

    public function __construct(AIService $aiService)
    {
        $this->aiService = $aiService;
    }

    /**
     * Process incoming message and get AI response
     */
    public function processMessage(string $phone, string $name, string $message): array
    {
        try {
            // Sanitize input
            $message = $this->sanitizeInput($message);
            $phone = $this->sanitizePhone($phone);

            // Log incoming message
            Log::info('WhatsApp Processing', [
                'phone' => $phone,
                'name' => $name,
                'message' => $message,
            ]);

            // Get conversation history for context
            $conversationHistory = $this->getConversationHistory($phone);

            // Build messages array with system prompt
            $messages = [
                ['role' => 'system', 'content' => $this->getSystemPrompt($name)],
            ];

            // Add conversation history
            foreach ($conversationHistory as $msg) {
                $messages[] = [
                    'role' => $msg['role'],
                    'content' => $msg['content'],
                ];
            }

            // Add current message
            $messages[] = ['role' => 'user', 'content' => $message];

            // Limit conversation context
            if (count($messages) > $this->maxHistoryMessages + 1) {
                $messages = array_merge(
                    [array_slice($messages, 0, 1)], // Keep system prompt
                    array_slice($messages, -$this->maxHistoryMessages)
                );
            }

            // Send to AI
            $response = $this->aiService->sendMessage($messages);

            // Save conversation to history
            $this->saveToHistory($phone, 'user', $message);
            $this->saveToHistory($phone, 'assistant', $response['message']);

            // Log interaction
            Log::info('WhatsApp AI Response', [
                'phone' => $phone,
                'success' => $response['success'],
                'response' => $response['message'],
            ]);

            return [
                'success' => true,
                'message' => $response['message'],
                'timestamp' => now()->toISOString(),
            ];
        } catch (\Exception $e) {
            Log::error('WhatsApp Process Error', [
                'phone' => $phone,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'message' => config('whatsapp.error_message', 'Mohon maaf, terjadi gangguan sistem. Silakan coba beberapa saat lagi.'),
            ];
        }
    }

    /**
     * Get system prompt customized for WhatsApp context
     */
    private function getSystemPrompt(string $userName): string
    {
        return <<<PROMPT
Anda adalah asisten virtual (AI Chatbot) untuk Portal Resmi Kantor Kementerian Agama Kabupaten Nganjuk.

TUGAS ANDA:
- Membantu pengunjung WhatsApp dengan pertanyaan seputar layanan Kemenag Nganjuk
- Bersikap ramah, sopan, dan profesional
- Memberikan informasi yang akurat dan helpful

INFORMASI KANTOR:
- Nama: Kantor Kementerian Agama Kabupaten Nganjuk
- Alamat: Jl. Ahmad Yani No. 17, Nganjuk, Jawa Timur
- Telepon: (0358) 321085
- WhatsApp: Hubungi melalui nomor ini
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
1. Jawab dengan singkat, padat, dan jelas (maksimal 500 karakter)
2. Gunakan bahasa Indonesia yang formal namun ramah
3. Jika ditanya topik di luar konteks, sampaikan sopan bahwa Anda hanya dapat membantu urusan layanan Kemenag
4. Jangan membuat informasi yang tidak Anda ketahui kebenarannya
5. Selalu tutup dengan sapaan singkat
6. Jika ada pertanyaan yang memerlukan data terbaru, sarankan untuk menghubungi langsung kantor

PERHATIAN KHUSUS:
- Untuk pesan WhatsApp, jawaban harus SINGKAT karena keterbatasan layar HP
- Prioritaskan informasi terpenting
- Gunakan poin-poin jika perlu menjelaskan banyak hal
PROMPT;
    }

    /**
     * Get conversation history from cache
     */
    public function getConversationHistory(string $phone): array
    {
        $key = $this->getHistoryKey($phone);
        return Cache::get($key, []);
    }

    /**
     * Save message to conversation history
     */
    private function saveToHistory(string $phone, string $role, string $content): void
    {
        $key = $this->getHistoryKey($phone);
        $history = $this->getConversationHistory($phone);

        $history[] = [
            'role' => $role,
            'content' => $content,
            'timestamp' => now()->toISOString(),
        ];

        // Keep only last N messages
        if (count($history) > $this->maxHistoryMessages * 2) {
            $history = array_slice($history, -$this->maxHistoryMessages * 2);
        }

        // Store for 24 hours
        Cache::put($key, $history, Carbon::now()->addHours(24));
    }

    /**
     * Clear conversation history
     */
    public function clearConversationHistory(string $phone): void
    {
        $key = $this->getHistoryKey($phone);
        Cache::forget($key);
    }

    /**
     * Check rate limit for phone number
     */
    public function checkRateLimit(string $phone): bool
    {
        $key = $this->getRateLimitKey($phone);

        $requests = Cache::get($key, 0);

        if ($requests >= $this->rateLimitMaxRequests) {
            Log::info('WhatsApp Rate Limited', ['phone' => $phone, 'requests' => $requests]);
            return false;
        }

        Cache::put($key, $requests + 1, $this->rateLimitWindowSeconds);
        return true;
    }

    /**
     * Broadcast message to multiple numbers
     */
    public function broadcastMessage(array $numbers, string $message): array
    {
        $results = [];

        foreach ($numbers as $number) {
            try {
                // Send via Go WA webhook
                $response = Http::timeout(10)->post(config('whatsapp.broadcast_webhook_url'), [
                    'phone' => $this->sanitizePhone($number),
                    'message' => $message,
                ]);

                $results[] = [
                    'phone' => $number,
                    'success' => $response->successful(),
                    'status' => $response->status(),
                ];
            } catch (\Exception $e) {
                $results[] = [
                    'phone' => $number,
                    'success' => false,
                    'error' => $e->getMessage(),
                ];
            }
        }

        return $results;
    }

    /**
     * Sanitize input message
     */
    private function sanitizeInput(string $input): string
    {
        $input = strip_tags($input);
        $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
        $input = trim($input);
        $input = preg_replace('/\s+/', ' ', $input);
        return $input;
    }

    /**
     * Sanitize phone number
     */
    private function sanitizePhone(string $phone): string
    {
        // Remove all non-digit characters except leading +
        $phone = preg_replace('/[^\d+]/', '', $phone);
        return $phone;
    }

    /**
     * Get cache key for conversation history
     */
    private function getHistoryKey(string $phone): string
    {
        return 'whatsapp_history_' . md5($phone);
    }

    /**
     * Get cache key for rate limiting
     */
    private function getRateLimitKey(string $phone): string
    {
        return 'whatsapp_rate_' . md5($phone);
    }

    /**
     * Get remaining rate limit for a phone
     */
    public function getRemainingRateLimit(string $phone): int
    {
        $key = $this->getRateLimitKey($phone);
        $requests = Cache::get($key, 0);
        return max(0, $this->rateLimitMaxRequests - $requests);
    }
}
