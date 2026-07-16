<?php

return [

    /*
    |--------------------------------------------------------------------------
    | WhatsApp Service Configuration
    |--------------------------------------------------------------------------
    |
    | Konfigurasi untuk integrasi WhatsApp dengan Go WhatsApp Web Multi-Device
    |
    */

    // Webhook secret untuk keamanan
    'webhook_secret' => env('WHATSAPP_WEBHOOK_SECRET', 'your-secret-key'),

    // Izinkan pesan dari group
    'allow_group_messages' => env('WHATSAPP_ALLOW_GROUP', false),

    // Rate limiting
    'rate_limit' => [
        'max_requests' => env('WHATSAPP_RATE_LIMIT_MAX', 5),
        'window_seconds' => env('WHATSAPP_RATE_LIMIT_WINDOW', 60),
    ],

    // Pesan error
    'error_message' => env('WHATSAPP_ERROR_MESSAGE', 'Mohon maaf, terjadi gangguan sistem. Silakan coba beberapa saat lagi.'),

    // Rate limit exceeded message
    'rate_limit_message' => env('WHATSAPP_RATE_LIMIT_MESSAGE', 'Terlalu banyak permintaan. Mohon tunggu sebentar sebelum mengirim pesan lagi.'),

    // URL untuk broadcast (opsional)
    'broadcast_webhook_url' => env('WHATSAPP_BROADCAST_WEBHOOK_URL'),

    // Enable/disable service
    'enabled' => env('WHATSAPP_ENABLED', true),

    // Max karakter pesan
    'max_message_length' => env('WHATSAPP_MAX_MESSAGE_LENGTH', 4096),

    // Typing indicator delay (ms)
    'typing_delay' => env('WHATSAPP_TYPING_DELAY', 1000),

    // Admin numbers (untuk special commands)
    'admin_numbers' => explode(',', env('WHATSAPP_ADMIN_NUMBERS', '')),

    /*
    |--------------------------------------------------------------------------
    | Auto Reply Settings
    |--------------------------------------------------------------------------
    */
    'auto_reply' => [
        'enabled' => env('WHATSAPP_AUTO_REPLY_ENABLED', true),
        
        // Pesan saat di luar jam kerja
        'offline_message' => env('WHATSAPP_OFFLINE_MESSAGE', 'Terima kasih telah menghubungi kami. Saat ini kami sedang di luar jam pelayanan. Jam pelayanan: Senin-Kamis 08.00-15.30 WIB, Jumat 08.00-16.00 WIB.'),
        
        // Pesan salam otomatis
        'greeting_enabled' => env('WHATSAPP_GREETING_ENABLED', true),
        'greeting_message' => env('WHATSAPP_GREETING_MESSAGE', 'Assalamualaikum! Terima kasih telah menghubungi Kantor Kemenag Nganjuk. Saya AI Assistant yang siap membantu Anda. Silakan sampaikan pertanyaan Anda.'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Session Management
    |--------------------------------------------------------------------------
    */
    'session' => [
        // Durasi history conversación dalam jam
        'history_duration_hours' => env('WHATSAPP_HISTORY_DURATION', 24),
        
        // Max pesan dalam conversation
        'max_history_messages' => env('WHATSAPP_MAX_HISTORY', 20),
    ],

    /*
    |--------------------------------------------------------------------------
    | Logging
    |--------------------------------------------------------------------------
    */
    'logging' => [
        'enabled' => env('WHATSAPP_LOGGING_ENABLED', true),
        'channel' => env('WHATSAPP_LOG_CHANNEL', 'single'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Feature Flags
    |--------------------------------------------------------------------------
    */
    'features' => [
        'conversation_context' => env('WHATSAPP_CONVERSATION_CONTEXT', true),
        'rate_limiting' => env('WHATSAPP_RATE_LIMITING', true),
        'broadcast' => env('WHATSAPP_BROADCAST', false),
        'media_support' => env('WHATSAPP_MEDIA_SUPPORT', false),
    ],
];
