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

    <!-- Tailwind CSS CDN with Typography plugin -->
    <script src="https://cdn.tailwindcss.com?plugins=typography"></script>

    <!-- Custom styles for legacy HTML content -->
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

        /* Styling untuk elemen HTML lama */
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

        /* Fix untuk font lama yang tidak standar */
        .prose-emerald font {
            font-size: inherit !important;
            color: inherit !important;
        }

        /* Fix untuk align attribute lama */
        .prose-emerald [align="center"] {
            text-align: center;
        }
        .prose-emerald [align="left"] {
            text-align: left;
        }
        .prose-emerald [align="right"] {
            text-align: right;
        }

        /* Fix untuk style inline lama */
        .prose-emerald [style*="text-align: center"] {
            text-align: center;
        }
        .prose-emerald [style*="text-align:center"] {
            text-align: center;
        }
    </style>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Custom styles for portal -->
    @stack('styles')
</head>
<body class="bg-gray-50 text-gray-800 font-sans antialiased min-h-screen flex flex-col">

    <!-- Header -->
    @include('partials.header')

    <!-- Mobile Menu Overlay -->
    @include('partials.mobile-menu')

    <!-- Main Content -->
    <main class="flex-1">
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

    <script>
        // Back to top functionality
        const backToTop = document.getElementById('back-to-top');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 300) {
                backToTop.classList.remove('opacity-0', 'invisible');
                backToTop.classList.add('opacity-100', 'visible');
            } else {
                backToTop.classList.add('opacity-0', 'invisible');
                backToTop.classList.remove('opacity-100', 'visible');
            }
        });
        backToTop.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    </script>
</body>
</html>
