<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>Halaman Tidak Ditemukan - 404 | Portal Kemenag Nganjuk</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('logo-kemenag.png') }}">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                    },
                }
            }
        }
    </script>

    <!-- Alpine.js for mobile menu dropdown -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50 text-gray-800 font-sans antialiased min-h-screen flex flex-col">

    <!-- Skip to Main Content Link for Accessibility -->
    <a href="#main-content" class="skip-link sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:z-50 focus:bg-emerald-600 focus:text-white focus:px-4 focus:py-2 focus:rounded-lg">
        Langsung ke konten utama
    </a>

    <!-- ========== HEADER (Using Partial) ========== -->
    @include('partials.header')

    <!-- ========== MOBILE MENU (Using Partial) ========== -->
    @include('partials.mobile-menu')

    <!-- ========== MAIN CONTENT ========== -->
    <main id="main-content" class="flex-1 min-h-[60vh] flex items-center justify-center py-16 px-4" tabindex="-1">
        <div class="max-w-2xl mx-auto text-center">

            <!-- Error Illustration -->
            <div class="mb-8">
                <svg class="w-48 h-48 md:w-64 md:h-64 mx-auto" viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <!-- Search icon with question mark -->
                    <circle cx="90" cy="90" r="60" fill="rgba(5,150,105,0.1)" stroke="rgba(5,150,105,0.3)" stroke-width="3"/>
                    <circle cx="90" cy="90" r="40" fill="none" stroke="rgba(5,150,105,0.2)" stroke-width="2"/>
                    <!-- Magnifying glass handle -->
                    <line x1="135" y1="135" x2="170" y2="170" stroke="rgba(5,150,105,0.4)" stroke-width="6" stroke-linecap="round"/>
                    <!-- Question mark -->
                    <text x="90" y="105" text-anchor="middle" font-size="50" font-weight="bold" fill="#059669">?</text>
                    <!-- 404 Badge -->
                    <circle cx="150" cy="45" r="30" fill="#ef4444"/>
                    <text x="150" y="55" text-anchor="middle" font-size="16" font-weight="bold" fill="white">404</text>
                </svg>
            </div>

            <!-- Error Code -->
            <div class="mb-4">
                <span class="text-8xl md:text-9xl font-black text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-emerald-800">
                    404
                </span>
            </div>

            <!-- Title -->
            <h1 class="text-2xl md:text-4xl font-bold text-gray-800 mb-4">
                Halaman Tidak Ditemukan
            </h1>

            <!-- Description -->
            <p class="text-gray-600 text-base md:text-lg mb-8 max-w-xl mx-auto leading-relaxed">
                Maaf, halaman yang Anda cari tidak ditemukan atau telah dipindahkan.
                Mungkin URL yang Anda masukkan salah atau konten sudah tidak tersedia.
            </p>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center mb-10">
                <!-- Home Button -->
                <a href="{{ url('/') }}" class="group inline-flex items-center gap-3 bg-emerald-600 text-white px-6 py-3 md:px-8 md:py-4 rounded-full font-semibold text-base hover:bg-emerald-700 transition-all duration-300 shadow-lg hover:shadow-xl hover:-translate-y-0.5">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/>
                    </svg>
                    Kembali ke Beranda
                </a>

                <!-- Back Button -->
                <button onclick="history.back()" class="inline-flex items-center gap-2 bg-gray-100 text-gray-700 px-6 py-3 md:px-8 md:py-4 rounded-full font-medium text-base hover:bg-gray-200 transition-all duration-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Halaman Sebelumnya
                </button>
            </div>

            <!-- Quick Links -->
            <div class="bg-gray-100 rounded-2xl p-6 md:p-8">
                <p class="text-gray-600 text-sm mb-4 font-medium">Atau coba halaman berikut:</p>
                <div class="flex flex-wrap justify-center gap-3">
                    <a href="{{ route('posts.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white hover:bg-gray-50 text-gray-700 rounded-full text-sm font-medium transition-all duration-300 border border-gray-200 hover:border-emerald-300 hover:text-emerald-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                        </svg>
                        Berita
                    </a>
                    <a href="{{ route('agendas.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white hover:bg-gray-50 text-gray-700 rounded-full text-sm font-medium transition-all duration-300 border border-gray-200 hover:border-emerald-300 hover:text-emerald-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Agenda
                    </a>
                    <a href="{{ route('downloads.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white hover:bg-gray-50 text-gray-700 rounded-full text-sm font-medium transition-all duration-300 border border-gray-200 hover:border-emerald-300 hover:text-emerald-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Download
                    </a>
                    <a href="{{ route('pages.show', 'kontak') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white hover:bg-gray-50 text-gray-700 rounded-full text-sm font-medium transition-all duration-300 border border-gray-200 hover:border-emerald-300 hover:text-emerald-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        Kontak
                    </a>
                </div>
            </div>

        </div>
    </main>

    <!-- ========== FOOTER (Using Partial) ========== -->
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

    <!-- Push scripts from partials -->
    @stack('scripts')
</body>
</html>
