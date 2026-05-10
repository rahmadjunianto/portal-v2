<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Chatbot Configuration
    |--------------------------------------------------------------------------
    |
    | Konfigurasi untuk AI Chatbot menggunakan OpenRouter API
    |
    */

    // Enable/disable chatbot
    'enabled' => env('CHATBOT_ENABLED', true),

    // AI API Configuration (OpenAI-compatible)
    'api_key' => env('CHATBOT_API_KEY'),
    'base_url' => env('CHATBOT_BASE_URL', 'https://api.openai.com/v1'),
    'model' => env('CHATBOT_MODEL', 'gpt-3.5-turbo'),

    // Model options (you can change based on preference/budget)
    'available_models' => [
        'openai/gpt-4.1-mini' => 'GPT-4.1 Mini (Recommended - Fast & Affordable)',
        'openai/gpt-4.1' => 'GPT-4.1 (More Powerful, More Expensive)',
        'anthropic/claude-3-haiku' => 'Claude 3 Haiku (Fast)',
        'google/gemini-2.0-flash' => 'Gemini 2.0 Flash (Free & Fast)',
        'meta-llama/llama-3-8b-instruct' => 'Llama 3 8B (Free)',
    ],

    // Chat settings
    'max_characters' => env('CHATBOT_MAX_CHARS', 500),
    'max_history' => 10, // Maximum conversation history messages
    'temperature' => 0.7, // Response creativity (0.1-1.0)
    'max_tokens' => 500, // Maximum response length

    // Rate limiting
    'rate_limit' => [
        'enabled' => false, // Disabled for testing
        'max_requests' => 30, // Maximum requests per window
        'window_seconds' => 60, // Time window in seconds
    ],

    // Daily token limit (per IP address)
    'daily_token_limit' => [
        'enabled' => true,
        'max_tokens' => 100000, // Maximum tokens per day per IP
    ],

    // UI Configuration
    'ui' => [
        'position' => 'bottom-right', // bottom-right or bottom-left
        'primary_color' => '#047857', // Emerald-700
        'secondary_color' => '#059669', // Emerald-600
        'welcome_message' => 'Selamat datang! Saya asisten virtual Kemenag Nganjuk. Ada yang bisa saya bantu?',
        'placeholder' => 'Ketik pertanyaan Anda...',
    ],

    // Welcome messages
    'welcome_messages' => [
        'Halo! 👋 Selamat datang di Portal Kemenag Nganjuk.',
        'Ada yang bisa saya bantu hari ini?',
        'Saya bisa membantu informasi tentang:',
        '• Jam pelayanan',
        '• Layanan nikah, haji, zakat',
        '• Pendidikan madrasah',
        '• Dan layanan lainnya',
    ],

    // Fallback message when API fails
    'fallback_message' => 'Mohon maaf, saya sedang mengalami gangguan. Silakan hubungi kami di (0358) 321085 atau coba beberapa saat lagi.',

    // Error messages
    'error_messages' => [
        'rate_limit' => 'Terlalu banyak permintaan. Mohon tunggu sebentar.',
        'empty' => 'Silakan ketik pesan terlebih dahulu.',
        'too_long' => 'Pesan terlalu panjang. Maksimal :max karakter.',
        'api_error' => 'Mohon maaf, terjadi gangguan sistem. Silakan coba lagi.',
        'offline' => 'Chatbot sedang offline. Silakan hubungi kami langsung.',
    ],

    // System prompt customization
    'system_prompt' => env('CHATBOT_SYSTEM_PROMPT'),
];
