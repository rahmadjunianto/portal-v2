<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ $settings->meta_description ?? 'Portal Resmi Kantor Kementerian Agama Kabupaten Nganjuk' }}">
    <meta name="keywords" content="{{ $settings->meta_keywords ?? 'kemenag, nganjuk, agama, islam, hindu, buddha, kristen, katolik' }}">
    <meta name="author" content="{{ $settings->site_name }}">

    <!-- Open Graph -->
    <meta property="og:title" content="{{ $settings->site_name ?? 'Kementerian Agama Kabupaten Nganjuk' }}">
    <meta property="og:description" content="{{ $settings->meta_description ?? 'Portal Resmi Kantor Kementerian Agama Kabupaten Nganjuk' }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ $settings->site_url ?? url('/') }}">
    <meta property="og:image" content="{{ asset('logo-kemenag.png') }}">
    <meta property="og:image:alt" content="Logo Kementerian Agama Kabupaten Nganjuk">

    <title>{{ $settings->site_name ?? 'Kementerian Agama Kabupaten Nganjuk' }} | Portal Resmi Kemenag Nganjuk</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('logo-kemenag.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('logo-kemenag.png') }}">

    <!-- Preconnect to external domains for faster connection -->
    <link rel="preconnect" href="https://cdn.tailwindcss.com" crossorigin>
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>

    <!-- Critical CSS - Inlined for immediate render of above-the-fold content -->
    <style>
        /* Critical CSS for LCP optimization - Hero section and header */
        :root {
            --emerald-600: #059669;
            --emerald-700: #047857;
            --emerald-800: #065f46;
            --emerald-900: #064e3b;
        }
        body {
            font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: #f9fafb;
            color: #1f2937;
            margin: 0;
        }
        /* Header styles */
        header { background: white; box-shadow: 0 2px 4px rgba(0,0,0,0.1); position: sticky; top: 0; z-index: 40; }
        .bg-emerald-700 { background-color: var(--emerald-700); }
        .bg-emerald-800 { background-color: var(--emerald-800); }
        .text-white { color: white; }
        .text-emerald-800 { color: var(--emerald-800); }
        /* Hero section - LCP critical path */
        [role="banner"] { position: relative; overflow: hidden; }
        .bg-gradient-to-br { background: linear-gradient(to bottom right, var(--emerald-800), var(--emerald-700), var(--emerald-600)); }
        .text-2xl { font-size: 1.5rem; }
        .md\:text-4xl { font-size: 2.25rem; }
        .font-bold { font-weight: 700; }
        .mb-4 { margin-bottom: 1rem; }
        .leading-tight { line-height: 1.25; }
        /* Prevent layout shift */
        img { max-width: 100%; height: auto; }
        /* Focus styles for accessibility */
        a:focus-visible, button:focus-visible {
            outline: 2px solid #059669;
            outline-offset: 2px;
            border-radius: 4px;
        }
    </style>

    <!-- Tailwind CSS CDN with Typography plugin - loaded with defer for performance -->
    <script defer src="https://cdn.tailwindcss.com?plugins=typography"></script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Non-critical styles - loaded after main content -->
    <style>
        /* Fix untuk format HTML lama dari database CMS */
        .prose-emerald {
            --tw-prose-body: #374151;
            --tw-prose-headings: #111827;
            --tw-prose-links: #059669;
            --tw-prose-bold: #111827;
            --tw-prose-code: #059669;
            --tw-prose-quotes: #374151;
            --tw-prose-quote-borders: #10b981;
            --tw-prose-captions: #6b7280;
            --tw-prose-bullets: #10b981;
            --tw-prose-hr: #e5e7eb;
        }

        .prose-emerald p,
        .prose-emerald div {
            margin-bottom: 1rem;
            line-height: 1.75;
        }

        .prose-emerald img {
            max-width: 100%;
            height: auto;
            border-radius: 0.75rem;
            margin: 1.5rem auto;
            display: block;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .prose-emerald h2 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-top: 2rem;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid #e5e7eb;
            color: #111827;
        }

        .prose-emerald h3 {
            font-size: 1.25rem;
            font-weight: 600;
            margin-top: 1.5rem;
            margin-bottom: 0.75rem;
            color: #111827;
        }

        .prose-emerald a {
            color: #059669;
            text-decoration: none;
            font-weight: 500;
        }

        .prose-emerald a:hover {
            text-decoration: underline;
        }

        .prose-emerald blockquote {
            border-left: 4px solid #10b981;
            background-color: #ecfdf5;
            padding: 1rem 1.5rem;
            border-radius: 0 0.5rem 0.5rem 0;
            margin: 1.5rem 0;
            font-style: normal;
            color: #374151;
        }

        .prose-emerald ul,
        .prose-emerald ol {
            margin: 1rem 0;
            padding-left: 1.5rem;
        }

        .prose-emerald ul {
            list-style-type: disc;
        }

        .prose-emerald ol {
            list-style-type: decimal;
        }

        .prose-emerald li {
            margin-bottom: 0.5rem;
            line-height: 1.6;
        }

        .prose-emerald table {
            width: 100%;
            border-collapse: collapse;
            margin: 1.5rem 0;
            border-radius: 0.5rem;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .prose-emerald th {
            background-color: #059669;
            color: white;
            padding: 0.75rem 1rem;
            font-weight: 600;
            text-align: left;
        }

        .prose-emerald td {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid #e5e7eb;
        }

        .prose-emerald tr:hover {
            background-color: #f9fafb;
        }

        .prose-emerald code {
            background-color: #f3f4f6;
            padding: 0.125rem 0.5rem;
            border-radius: 0.25rem;
            font-size: 0.875rem;
            color: #059669;
            font-family: monospace;
        }

        .prose-emerald pre {
            background-color: #1f2937;
            color: #f3f4f6;
            padding: 1rem;
            border-radius: 0.5rem;
            overflow-x: auto;
            margin: 1.5rem 0;
        }

        .prose-emerald pre code {
            background: transparent;
            color: inherit;
            padding: 0;
        }

        .prose-emerald hr {
            border: none;
            border-top: 1px solid #e5e7eb;
            margin: 2rem 0;
        }

        .prose-emerald font {
            font-size: inherit !important;
            color: inherit !important;
        }

        .prose-emerald [align="center"] { text-align: center; }
        .prose-emerald [align="left"] { text-align: left; }
        .prose-emerald [align="right"] { text-align: right; }
        .prose-emerald [style*="text-align: center"] { text-align: center; }
        .prose-emerald [style*="text-align:center"] { text-align: center; }

        /* Focus styles */
        .skip-link:focus,
        [role="button"]:focus-visible {
            outline: 3px solid #10b981;
            outline-offset: 3px;
            box-shadow: 0 0 0 6px rgba(16, 185, 129, 0.3);
        }

        .sr-only:focus {
            position: absolute !important;
            width: auto !important;
            height: auto !important;
            padding: 0.75rem 1rem !important;
            margin: 0 !important;
            overflow: visible !important;
            clip: auto !important;
            white-space: nowrap !important;
            border-radius: 0.5rem !important;
            z-index: 9999 !important;
        }
    </style>

    <!-- Custom styles for portal -->
    @stack('styles')
</head>
<body class="bg-gray-50 text-gray-800 font-sans antialiased min-h-screen flex flex-col">
    <!-- Skip to Main Content Link for Accessibility -->
    <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:z-50 focus:bg-emerald-600 focus:text-white focus:px-4 focus:py-2 focus:rounded-lg focus:shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-400">
        Langsung ke konten utama
    </a>

    <!-- Header -->
    @include('partials.header')

    <!-- Mobile Menu Overlay -->
    @include('partials.mobile-menu')

    <!-- Main Content - Fixed CLS dengan min-height yang stabil -->
    <main id="main-content" class="flex-1 min-h-[50vh]" tabindex="-1">
        @yield('content')
    </main>

    <!-- Footer -->
    @include('partials.footer')

    <!-- Back to Top Button -->
    <button
        id="back-to-top"
        class="fixed bottom-6 right-6 bg-emerald-600 hover:bg-emerald-700 text-white w-12 h-12 rounded-full shadow-lg flex items-center justify-center transition-all duration-300 opacity-0 invisible z-50"
        aria-label="Kembali ke atas"
    >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
        </svg>
    </button>

    <!-- Scripts -->
    @stack('scripts')

    <!-- CSRF Token for AJAX -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Chat Widget - Load directly (optimized for LCP) -->
    @if(config('chatbot.enabled', true))
    <div x-data="chatWidget()" x-init="init()"
         class="fixed z-50 {{ config('chatbot.ui.position', 'bottom-right') === 'bottom-right' ? 'right-4 md:right-6' : 'left-4 md:left-6' }} bottom-4 md:bottom-6"
         @keydown.escape.window="isOpen = false"
         style="content-visibility: visible;">

        <!-- Chat Button (Always visible) -->
        <button
                @click="openChat()"
                class="group relative w-14 h-14 md:w-16 md:h-16 bg-gradient-to-br from-emerald-500 to-emerald-700 hover:from-emerald-600 hover:to-emerald-800 rounded-full shadow-2xl hover:shadow-emerald-500/40 transition-all duration-300 flex items-center justify-center ring-4 ring-emerald-300/30 hover:ring-emerald-400/50 hover:scale-110"
                :class="{'animate-bounce': hasUnread}">
            <!-- Unread Badge -->
            <span x-show="hasUnread"
                  x-transition:enter="transition ease-out duration-300"
                  x-transition:enter-start="opacity-0 scale-0"
                  x-transition:enter-end="opacity-100 scale-100"
                  class="absolute -top-1 -right-1 w-6 h-6 bg-gradient-to-br from-red-500 to-red-600 text-white text-xs rounded-full flex items-center justify-center font-bold shadow-lg"
                  x-text="unreadCount"></span>

            <!-- Chat Icon -->
            <svg x-show="!isOpen" class="w-7 h-7 md:w-8 md:h-8 text-white drop-shadow-lg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
            </svg>
            <svg x-show="isOpen" x-cloak class="w-7 h-7 md:w-8 md:h-8 text-white drop-shadow-lg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>

        <!-- Chat Window -->
        <div x-show="isOpen"
             x-transition
             @click.outside="closeOnOutside && closeChat()"
             class="bg-white rounded-2xl shadow-2xl w-[calc(100vw-2rem)] md:w-96 max-h-[calc(100vh-8rem)] md:max-h-[600px] flex flex-col overflow-hidden"
             :class="{{ config('chatbot.ui.position', 'bottom-right') === 'bottom-right' ? 'origin-bottom-right' : 'origin-bottom-left' }}">

            <!-- Header -->
            <div class="bg-emerald-700 text-white px-4 py-3 md:px-6 md:py-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-emerald-600 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-sm md:text-base">AI Assistant</h3>
                        <p class="text-xs text-emerald-200 flex items-center gap-1">
                            <span class="w-2 h-2 bg-emerald-300 rounded-full animate-pulse"></span>
                            Online
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <button @click="clearChat()"
                            class="p-2 hover:bg-emerald-600 rounded-full transition-colors"
                            title="Hapus riwayat chat">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                    <button @click="closeChat()"
                            class="p-2 hover:bg-emerald-600 rounded-full transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Chat Messages -->
            <div class="flex-1 overflow-y-auto p-4 space-y-4 bg-gray-50" x-ref="messagesContainer" id="chat-messages">
                <!-- Welcome Message -->
                <template x-if="messages.length === 0">
                    <div class="text-center py-4">
                        <div class="w-16 h-16 mx-auto mb-3 bg-emerald-100 rounded-full flex items-center justify-center">
                            <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <h4 class="font-semibold text-gray-800 mb-2">Selamat datang!</h4>
                        <p class="text-sm text-gray-600">Saya asisten virtual Kemenag Nganjuk. Silakan tanyakan tentang layanan kami.</p>
                    </div>
                </template>

                <!-- Messages -->
                <template x-for="(msg, index) in messages" :key="index">
                    <div class="flex animate-fadeIn" :class="msg.role === 'user' ? 'justify-end' : 'justify-start'">
                        <div x-show="msg.role !== 'user'" class="w-8 h-8 mr-2 flex-shrink-0 bg-emerald-600 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div class="max-w-[80%] md:max-w-[75%] rounded-2xl px-4 py-2.5 text-sm"
                             :class="msg.role === 'user' ? 'bg-emerald-600 text-white rounded-br-md' : 'bg-white text-gray-800 rounded-bl-md shadow-sm border border-gray-100'">
                            <div x-html="formatMessage(msg.content)"></div>
                            <p class="text-[10px] mt-1 opacity-60" :class="msg.role === 'user' ? 'text-emerald-200' : 'text-gray-400'" x-text="msg.time"></p>
                        </div>
                    </div>
                </template>

                <!-- Typing Indicator -->
                <div x-show="isTyping" class="flex justify-start animate-fadeIn">
                    <div class="w-8 h-8 mr-2 flex-shrink-0 bg-emerald-600 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div class="bg-white rounded-2xl rounded-bl-md shadow-sm border border-gray-100 px-4 py-3">
                        <div class="flex gap-1">
                            <div class="w-2 h-2 bg-emerald-400 rounded-full animate-bounce" style="animation-delay: 0ms"></div>
                            <div class="w-2 h-2 bg-emerald-400 rounded-full animate-bounce" style="animation-delay: 150ms"></div>
                            <div class="w-2 h-2 bg-emerald-400 rounded-full animate-bounce" style="animation-delay: 300ms"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Input Area -->
            <div class="p-3 md:p-4 bg-white border-t border-gray-100">
                <!-- User Info Form -->
                <div x-show="!userInfoSubmitted" class="mb-3 p-3 bg-amber-50 border border-amber-200 rounded-lg">
                    <p class="text-xs text-amber-700 mb-2 font-medium">⚠️ Wajib diisi sebelum chat:</p>
                    <div class="grid grid-cols-1 gap-2">
                        <div>
                            <input type="text" x-model="userInfo.name" placeholder="Nama lengkap *"
                                   class="px-3 py-2 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 w-full"
                                   :class="nameError ? 'border-red-500 bg-red-50' : 'border-gray-300'">
                            <p x-show="nameError" x-text="nameError" class="text-xs text-red-500 mt-1"></p>
                        </div>
                        <div>
                            <input type="email" x-model="userInfo.email" placeholder="Email *"
                                   class="px-3 py-2 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 w-full"
                                   :class="emailError ? 'border-red-500 bg-red-50' : 'border-gray-300'">
                            <p x-show="emailError" x-text="emailError" class="text-xs text-red-500 mt-1"></p>
                        </div>
                        <div>
                            <input type="tel" x-model="userInfo.phone" placeholder="No. WhatsApp *"
                                   class="px-3 py-2 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 w-full"
                                   :class="phoneError ? 'border-red-500 bg-red-50' : 'border-gray-300'">
                            <p x-show="phoneError" x-text="phoneError" class="text-xs text-red-500 mt-1"></p>
                        </div>
                    </div>
                    <button type="button" @click="submitUserInfo()"
                            class="mt-3 w-full py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg transition-colors">
                        Lanjutkan Chat
                    </button>
                </div>

                <!-- Error Message -->
                <div x-show="error" x-transition class="mb-2 px-3 py-2 bg-red-50 border border-red-200 rounded-lg text-xs text-red-600">
                    <span x-text="error"></span>
                </div>

                <!-- Chat Input with Suggestions -->
                <template x-if="userInfoSubmitted">
                    <div>
                        <!-- Quick suggestions -->
                        <div class="flex flex-wrap gap-2 mb-3">
                            <template x-for="suggestion in suggestions" :key="suggestion">
                                <button @click="sendSuggestion(suggestion)"
                                        class="px-3 py-1.5 text-xs bg-white border border-emerald-200 text-emerald-700 rounded-full hover:bg-emerald-50 transition-colors">
                                    <span x-text="suggestion"></span>
                                </button>
                            </template>
                        </div>
                        <form @submit.prevent="sendMessage()" class="flex gap-2">
                            <input type="text" x-model="newMessage" @input="error = ''" :placeholder="placeholder" :disabled="isTyping"
                                   maxlength="500"
                                   class="flex-1 px-4 py-2.5 text-sm border border-gray-200 rounded-full focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent disabled:bg-gray-100 disabled:cursor-not-allowed transition-all">
                            <button type="submit" :disabled="!newMessage.trim() || isTyping"
                                    class="w-10 h-10 bg-emerald-600 hover:bg-emerald-700 disabled:bg-gray-300 disabled:cursor-not-allowed rounded-full flex items-center justify-center transition-colors flex-shrink-0">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </template>

                <p class="text-[10px] text-gray-400 mt-2 text-center" x-show="userInfoSubmitted">
                    AI Assistant • Jawaban AI mungkin tidak selalu 100% akurat
                </p>
            </div>
        </div>
    </div>

    <script>
        // Back to top functionality
        document.getElementById('back-to-top')?.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
        window.addEventListener('scroll', () => {
            const btn = document.getElementById('back-to-top');
            if (btn) {
                if (window.scrollY > 300) {
                    btn.classList.remove('opacity-0', 'invisible');
                    btn.classList.add('opacity-100', 'visible');
                } else {
                    btn.classList.add('opacity-0', 'invisible');
                    btn.classList.remove('opacity-100', 'visible');
                }
            }
        });

        function chatWidget() {
            return {
                isOpen: false,
                isTyping: false,
                hasUnread: false,
                unreadCount: 0,
                newMessage: '',
                messages: [],
                error: '',
                placeholder: 'Ketik pertanyaan Anda...',
                suggestions: ['Jam pelayanan?', 'Layanan yg Tersedia', 'Alamat kantor'],
                closeOnOutside: false,
                userInfo: { name: '', email: '', phone: '' },
                userInfoSubmitted: false,
                userInfoExpiry: 4 * 60 * 60 * 1000,
                nameError: '',
                emailError: '',
                phoneError: '',

                init() {
                    const savedUserInfo = localStorage.getItem('chatbot_user_info');
                    const userSubmitted = localStorage.getItem('chatbot_user_submitted');
                    const savedTime = localStorage.getItem('chatbot_user_time');

                    if (savedUserInfo && userSubmitted === 'true' && savedTime) {
                        const elapsed = Date.now() - parseInt(savedTime);
                        if (elapsed < this.userInfoExpiry) {
                            this.userInfo = JSON.parse(savedUserInfo);
                            this.userInfoSubmitted = true;
                        } else {
                            localStorage.removeItem('chatbot_user_info');
                            localStorage.removeItem('chatbot_user_submitted');
                            localStorage.removeItem('chatbot_user_time');
                        }
                    }

                    const saved = localStorage.getItem('chatbot_messages');
                    if (saved) {
                        this.messages = JSON.parse(saved);
                    }

                    this.placeholder = '{{ config("chatbot.ui.placeholder", "Ketik pertanyaan Anda...") }}';
                },

                openChat() { this.isOpen = true; this.hasUnread = false; this.unreadCount = 0; this.scrollToBottom(); },
                closeChat() { this.isOpen = false; },
                clearChat() { this.messages = []; localStorage.removeItem('chatbot_messages'); },

                validateName() {
                    this.nameError = '';
                    if (!this.userInfo.name.trim()) this.nameError = 'Nama wajib diisi';
                    else if (this.userInfo.name.trim().length < 2) this.nameError = 'Nama minimal 2 karakter';
                    return !this.nameError;
                },
                validateEmail() {
                    this.emailError = '';
                    if (!this.userInfo.email.trim()) this.emailError = 'Email wajib diisi';
                    else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.userInfo.email.trim())) this.emailError = 'Format email tidak valid';
                    return !this.emailError;
                },
                validatePhone() {
                    this.phoneError = '';
                    if (!this.userInfo.phone.trim()) this.phoneError = 'WhatsApp wajib diisi';
                    return !this.phoneError;
                },

                submitUserInfo() {
                    const isNameValid = this.validateName();
                    const isEmailValid = this.validateEmail();
                    const isPhoneValid = this.validatePhone();

                    if (isNameValid && isEmailValid && isPhoneValid) {
                        this.userInfoSubmitted = true;
                        this.nameError = ''; this.emailError = ''; this.phoneError = '';
                        localStorage.setItem('chatbot_user_info', JSON.stringify(this.userInfo));
                        localStorage.setItem('chatbot_user_submitted', 'true');
                        localStorage.setItem('chatbot_user_time', Date.now().toString());
                        this.scrollToBottom();
                    }
                },

                async sendMessage() {
                    if (!this.userInfo.name || !this.userInfo.email || !this.userInfo.phone) {
                        this.error = 'Mohon isi data terlebih dahulu.';
                        return;
                    }
                    if (!this.newMessage.trim() || this.isTyping) return;

                    const userMessage = this.newMessage.trim();
                    this.newMessage = '';
                    this.error = '';

                    this.messages.push({ role: 'user', content: userMessage, time: this.formatTime(new Date()) });
                    this.scrollToBottom();
                    this.isTyping = true;

                    try {
                        const response = await fetch('{{ route("chatbot.chat") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify({
                                message: userMessage,
                                conversation: this.getConversationHistory(),
                                user_name: this.userInfo.name,
                                user_email: this.userInfo.email,
                                user_phone: this.userInfo.phone
                            })
                        });

                        const data = await response.json();
                        if (data.success) {
                            this.messages.push({ role: 'assistant', content: data.message, time: this.formatTime(new Date()) });
                        } else {
                            this.error = data.message || 'Terjadi kesalahan.';
                        }
                    } catch (err) {
                        console.error('Chat error:', err);
                        this.error = 'Koneksi gagal.';
                    } finally {
                        this.isTyping = false;
                        this.scrollToBottom();
                        this.saveMessages();
                    }
                },

                sendSuggestion(text) {
                    this.newMessage = text;
                    this.sendMessage();
                },

                getConversationHistory() {
                    return this.messages.slice(-10).map(m => ({ role: m.role, content: m.content }));
                },
                saveMessages() { localStorage.setItem('chatbot_messages', JSON.stringify(this.messages)); },

                scrollToBottom() {
                    this.$nextTick(() => {
                        const container = document.getElementById('chat-messages');
                        if (container) container.scrollTop = container.scrollHeight;
                    });
                },

                formatMessage(text) {
                    return text.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>').replace(/\*(.*?)\*/g, '<em>$1</em>').replace(/\n/g, '<br>');
                },

                formatTime(date) {
                    const utcDate = new Date(date.getTime() + (7 * 60 * 60 * 1000));
                    const hours = String(utcDate.getUTCHours()).padStart(2, '0');
                    const minutes = String(utcDate.getUTCMinutes()).padStart(2, '0');
                    return `${hours}.${minutes}`;
                }
            };
        }
    </script>
    @endif
</body>
</html>
