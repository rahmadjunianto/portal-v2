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

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

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
