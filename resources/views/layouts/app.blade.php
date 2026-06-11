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

    <!-- RSS/Atom Feeds -->
    <link rel="alternate" type="application/rss+xml" title="RSS Feed - {{ $settings->site_name ?? 'Kementerian Agama Kabupaten Nganjuk' }}" href="{{ url('/feed') }}">
    <link rel="alternate" type="application/atom+xml" title="Atom Feed - {{ $settings->site_name ?? 'Kementerian Agama Kabupaten Nganjuk' }}" href="{{ url('/atom') }}">

    <!-- Schema.org Structured Data -->
    @yield('schema')

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
        
        /* Hero Carousel - CSS-only for instant LCP render (no JS blocking) */
        .hero-carousel { position: relative; width: 100%; overflow: hidden; }
        .hero-slide {
            position: absolute;
            inset: 0;
            opacity: 0;
            z-index: 1;
            transition: opacity 0.5s ease-in-out;
        }
        /* First slide visible IMMEDIATELY - critical for LCP */
        .hero-slide--first {
            opacity: 1;
            z-index: 2;
        }
        /* Active slide (controlled by JS after load) */
        .hero-slide--active {
            opacity: 1;
            z-index: 2;
        }
        /* Indicator styles */
        .hero-indicator {
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .hero-indicator--active {
            background-color: white;
            width: 2rem;
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

    <!-- Accessibility Styles - WCAG 2.1 AA Compliant -->
    <style>
        /* Enhanced Focus Indicators - Visible on all interactive elements */
        :focus {
            outline: 3px solid #059669;
            outline-offset: 2px;
        }
        
        :focus:not(:focus-visible) {
            outline: none;
        }
        
        :focus-visible {
            outline: 3px solid #059669;
            outline-offset: 2px;
            box-shadow: 0 0 0 6px rgba(5, 150, 105, 0.3);
        }
        
        /* Skip Link - High visibility when focused */
        .skip-link:focus,
        a[href="#main-content"]:focus {
            position: absolute !important;
            top: 0 !important;
            left: 0 !important;
            width: auto !important;
            height: auto !important;
            padding: 1rem 1.5rem !important;
            background-color: #059669 !important;
            color: white !important;
            font-weight: 600 !important;
            text-decoration: none !important;
            z-index: 9999 !important;
            clip: auto !important;
            border-radius: 0 0 0.5rem 0 !important;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06) !important;
        }
        
        /* Screen reader only class */
        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border: 0;
        }
        
        /* Form accessibility improvements */
        input:focus,
        select:focus,
        textarea:focus {
            outline: 3px solid #059669;
            outline-offset: 2px;
            border-color: #059669;
        }
        
        /* Button focus improvements */
        button:focus,
        [role="button"]:focus {
            outline: 3px solid #059669;
            outline-offset: 2px;
        }
        
        /* Link focus improvements */
        a:focus {
            outline: 3px solid #059669;
            outline-offset: 2px;
            border-radius: 2px;
        }
        
        /* Reduced motion preference */
        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
                scroll-behavior: auto !important;
            }
        }
        
        /* High contrast mode support */
        @media (forced-colors: active) {
            a:focus,
            button:focus,
            input:focus {
                outline: 3px solid CanvasText;
            }
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 font-sans antialiased min-h-screen flex flex-col">
    <!-- Skip to Main Content Link for Accessibility - WCAG 2.1 SC 2.4.1 -->
    <a href="#main-content" class="skip-link sr-only focus:not-sr-only">
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

    <!-- Back to Top Script -->
    <script>
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
    </script>

    <!-- Chat Widget Component -->
    @if(config('chatbot.enabled', true))
    @include('components.chat-widget')
    @endif

    <!-- Accessibility Widget Component -->
    @include('components.accessibility-widget')
</body>
</html>
