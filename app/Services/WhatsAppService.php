<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use App\Models\WhatsAppConversation;

class WhatsAppService
{
    private AIService $aiService;
    private KnowledgeBankService $knowledgeBank;
    private int $maxHistoryMessages = 10;
    private int $rateLimitMaxRequests = 25;
    private int $rateLimitWindowSeconds = 60;

    public function __construct(AIService $aiService, KnowledgeBankService $knowledgeBank)
    {
        $this->aiService = $aiService;
        $this->knowledgeBank = $knowledgeBank;
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
            Log::channel('whatsapp')->info('WhatsApp Processing', [
                'phone' => $phone,
                'name' => $name,
                'message' => $message,
            ]);

            // Check if this is the first message (new conversation)
            $conversationHistory = $this->getConversationHistory($phone);
            $isFirstMessage = empty($conversationHistory);

            // If first message, send welcome + services list
            if ($isFirstMessage) {
                $welcomeMessage = $this->getWelcomeMessage($name);
                
                // Save welcome message to history
                $this->saveToHistory($phone, 'assistant', $welcomeMessage, null);
                
                // Return welcome message
                return [
                    'success' => true,
                    'message' => $welcomeMessage,
                    'is_welcome' => true,
                    'timestamp' => now()->toISOString(),
                ];
            }

            // Check Knowledge Bank FIRST before using AI
            $kbAnswer = $this->knowledgeBank->findAnswer($message);
            
            if ($kbAnswer) {
                Log::channel('whatsapp')->info('Knowledge Bank Response', [
                    'phone' => $phone,
                    'query' => $message,
                ]);
                
                // Save to history
                $this->saveToHistory($phone, 'user', $message, $name);
                $this->saveToHistory($phone, 'assistant', $kbAnswer, null);
                
                return [
                    'success' => true,
                    'message' => $kbAnswer,
                    'source' => 'knowledge_bank',
                    'timestamp' => now()->toISOString(),
                ];
            }

            // Build messages array with system prompt
            $messages = [
                ['role' => 'system', 'content' => $this->getSystemPrompt($name)],
            ];

            // Add conversation history
            foreach ($conversationHistory as $msg) {
                // Skip invalid history entries
                if (!isset($msg['role']) || !isset($msg['content'])) {
                    Log::channel('whatsapp')->warning('Skipping invalid history entry', ['msg' => $msg]);
                    continue;
                }
                $messages[] = [
                    'role' => $msg['role'],
                    'content' => $msg['content'],
                ];
            }

            // Add current message
            $messages[] = ['role' => 'user', 'content' => $message];

            // Limit conversation context - keep system prompt + recent messages
            if (count($messages) > $this->maxHistoryMessages + 1) {
                $systemPrompt = array_slice($messages, 0, 1); // Returns [[system]]
                $recentMessages = array_slice($messages, -$this->maxHistoryMessages);
                $messages = array_merge($systemPrompt, $recentMessages);
            }

            // Log messages structure for debugging
            Log::channel('whatsapp')->info('WhatsApp Messages Structure', [
                'message_count' => count($messages),
                'messages_preview' => array_map(function($m) {
                    if (!isset($m['role']) || !isset($m['content'])) {
                        return ['invalid_entry' => true, 'data' => $m];
                    }
                    return [
                        'role' => $m['role'],
                        'content_length' => strlen($m['content']),
                        'content_preview' => substr($m['content'], 0, 100) . '...'
                    ];
                }, $messages),
            ]);

            // Send to AI
            $response = $this->aiService->sendMessage($messages);

            // Check if AI returned an error
            if (!$response['success']) {
                Log::channel('whatsapp')->warning('WhatsApp AI Error Response', [
                    'phone' => $phone,
                    'error_message' => $response['message'],
                ]);

                return [
                    'success' => false,
                    'message' => $response['message'], // Return actual error from AIService
                    'error_type' => 'ai_error',
                    'timestamp' => now()->toISOString(),
                ];
            }

            // Save conversation to history
            $this->saveToHistory($phone, 'user', $message, $name);
            $this->saveToHistory($phone, 'assistant', $response['message'], null);

            // Log interaction
            Log::channel('whatsapp')->info('WhatsApp AI Response', [
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
            Log::channel('whatsapp')->error('WhatsApp Process Exception', [
                'phone' => $phone,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
                'error_type' => 'exception',
            ];
        }
    }

    /**
     * Get welcome message with services list for first-time users
     */
    private function getWelcomeMessage(string $userName): string
    {
        $greeting = $userName ? "Assalamu'alaikum {$userName}," : "Assalamu'alaikum,";
        
        return <<<WELCOME
{$greeting}

Selamat datang di WhatsAppBot *Kantor Kementerian Agama Kabupaten Nganjuk*! 👋

Saya asisten virtual yang siap membantu Anda. Berikut layanan kami:

*LAYANAN KEMENAG NGANJUK:*

1️⃣ *Layanan Nikah* - Pendaftaran & pengelolaan pernikahan
2️⃣ *Pendidikan Madrasah* - PAUD, MI, MTs, MA
3️⃣ *Zakat* - Zakat fitrah & zakat mal
4️⃣ *Wakaf* - Pengurusan tanah wakaf
5️⃣ *Bimas Islam* - Pembinaan agama Islam
6️⃣ *Produk Halal* - Sertifikasi halal

*JAM PELAYANAN:*
🕐 Senin-Kamis: 08.00-15.30 WIB
🕐 Jumat: 08.00-16.00 WIB
🗓️ Sabtu-Minggu: Libur

📞 *Kontak:*
(0358) 321085
kantor@nganjuk.kemenag.go.id

Silakan ketik pertanyaan Anda! 😊
WELCOME;
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
     * Get conversation history from database
     */
    public function getConversationHistory(string $phone): array
    {
        return WhatsAppConversation::getRecentMessages($phone, $this->maxHistoryMessages);
    }

    /**
     * Save message to conversation history (database)
     */
    private function saveToHistory(string $phone, string $role, string $content, ?string $name = null): void
    {
        WhatsAppConversation::create([
            'phone' => $phone,
            'role' => $role,
            'content' => $content,
            'name' => $name,
        ]);
    }

    /**
     * Clear conversation history from database
     */
    public function clearConversationHistory(string $phone): void
    {
        WhatsAppConversation::forPhone($phone)->delete();
    }

    /**
     * Check rate limit for phone number
     */
    public function checkRateLimit(string $phone): bool
    {
        $key = $this->getRateLimitKey($phone);

        $requests = Cache::get($key, 0);

        if ($requests >= $this->rateLimitMaxRequests) {
            Log::channel('whatsapp')->info('WhatsApp Rate Limited', ['phone' => $phone, 'requests' => $requests]);
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
