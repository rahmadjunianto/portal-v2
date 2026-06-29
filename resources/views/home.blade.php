@extends('layouts.app')

@section('content')
<!-- ===================== HERO SECTION ===================== -->
<section class="relative overflow-hidden" role="banner" aria-label="Banner berita utama">
    <!-- Background Pattern -->
    <div class="absolute inset-0 bg-gradient-to-br from-emerald-800 via-emerald-700 to-emerald-600">
        <div class="absolute inset-0 opacity-10" style="background-image: url('data:image/svg+xml,%3Csvg width=\"60\" height=\"60\" viewBox=\"0 0 60 60\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg fill=\"none\" fill-rule=\"evenodd\"%3E%3Cg fill=\"%23ffffff\" fill-opacity=\"0.4\"%3E%3Cpath d=\"M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
    </div>

    @if($headlinePosts->count() > 0)
    <!-- Hero Carousel - CSS-only for LCP optimization (no JS blocking) -->
    <div class="hero-carousel relative w-full" style="aspect-ratio: 16/9; max-height: 70vh;" data-total="{{ $headlinePosts->count() }}">
        <!-- Slides - CSS animation handles auto-rotation, first slide visible immediately -->
        <div class="relative w-full h-full">
            @foreach($headlinePosts as $index => $post)
            <div
                class="hero-slide absolute inset-0 {{ $index === 0 ? 'hero-slide--first' : '' }}"
                style="animation-delay: {{ $index * 5 }}s; --slide-index: {{ $index }};"
                data-index="{{ $index }}"
            >
                <!-- Image Background -->
                @if($post->thumbnail_url)
                <img
                    src="{{ $post->thumbnail_url }}"
                    alt="{{ $post->title }}"
                    width="1920"
                    height="1080"
                    class="w-full h-full object-cover transition-opacity duration-500"
                    @if($index === 0) fetchpriority="high" @endif
                    loading="{{ $index === 0 ? 'eager' : 'lazy' }}"
                    decoding="{{ $index === 0 ? 'sync' : 'async' }}"
                >
                @else
                <!-- Placeholder untuk mencegah CLS -->
                <div class="absolute inset-0 bg-gradient-to-br from-emerald-700 to-emerald-900 flex items-center justify-center">
                    <svg class="w-24 h-24 text-emerald-500 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                    </svg>
                </div>
                <img src="{{ asset('images/placeholder-news.jpg') }}" alt="Gambar berita {{ $post->title }}" width="1920" height="1080" class="w-full h-full object-cover" onerror="this.style.display='none';">
                <div class="w-full h-full bg-gradient-to-br from-emerald-700 to-emerald-900 flex items-center justify-center hidden">
                    <div class="text-center text-emerald-200">
                        <svg class="w-24 h-24 mx-auto mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                        </svg>
                        <p class="text-lg font-medium">Tidak ada gambar</p>
                    </div>
                </div>
                @endif

                <!-- Overlay -->
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>

                <!-- Content -->
                <div class="absolute bottom-0 left-0 right-0 p-6 md:p-12">
                    <div class="container mx-auto">
                        <div class="max-w-3xl">
                            @if($post->category)
                            <span class="inline-block px-3 py-1 bg-emerald-500 text-white text-sm font-medium rounded-full mb-4">
                                {{ $post->category->name }}
                            </span>
                            @endif
                            <h2 class="text-2xl md:text-4xl font-bold text-white mb-4 leading-tight">
                                <a href="{{ route('posts.show', $post->slug) }}" class="hover:text-emerald-300 transition-colors">
                                    {{ $post->title }}
                                </a>
                            </h2>
                            @if($post->subtitle)
                            <p class="text-gray-200 text-base md:text-lg mb-4 line-clamp-2">{{ $post->subtitle }}</p>
                            @endif
                            <div class="flex items-center gap-4 text-gray-300 text-sm">
                                @if($post->author)
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    {{ $post->author->name }}
                                </span>
                                @endif
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    {{ $post->published_at ? $post->published_at->format('d M Y') : '' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Navigation Arrows - CSS-only carousel uses anchor links -->
        @if($headlinePosts->count() > 1)
        <button onclick="heroCarouselPrev()" class="absolute left-4 top-1/2 -translate-y-1/2 bg-white/20 hover:bg-white/40 backdrop-blur-sm text-white p-3 rounded-full transition-all z-10" aria-label="Slide sebelumnya">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </button>
        <button onclick="heroCarouselNext()" class="absolute right-4 top-1/2 -translate-y-1/2 bg-white/20 hover:bg-white/40 backdrop-blur-sm text-white p-3 rounded-full transition-all z-10" aria-label="Slide berikutnya">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </button>

        <!-- Indicators - CSS-only with active state animation -->
        <div class="absolute bottom-6 left-1/2 -translate-x-1/2 flex gap-2 z-10">
            @foreach($headlinePosts as $index => $post)
            <button
                onclick="heroCarouselGoTo({{ $index }})"
                class="hero-indicator h-3 rounded-full transition-all duration-300 flex-shrink-0 {{ $index === 0 ? 'hero-indicator--active bg-white w-8' : 'bg-white/50 w-3' }}"
                data-index="{{ $index }}"
                aria-label="Slide {{ $index + 1 }}"
            ></button>
            @endforeach
        </div>
        @endif
    </div>
    @else
    <!-- Static Hero (No posts) -->
    <div class="relative h-[400px] flex items-center">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-3xl md:text-5xl font-bold text-white mb-4">{{ $settings->site_name }}</h1>
            <p class="text-xl text-emerald-100 mb-8">{{ $settings->meta_description ?? 'Portal Resmi Kementerian Agama Kabupaten Nganjuk' }}</p>
            <a href="#berita" class="inline-flex items-center gap-2 bg-white text-emerald-700 px-6 py-3 rounded-lg font-semibold hover:bg-emerald-50 transition-colors">
                Lihat Berita Terbaru
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
        </div>
    </div>
    @endif
</section>

<!-- ===================== SEKILAS KEMENAG SECTION ===================== -->
<section class="py-10 md:py-20 bg-gradient-to-br from-slate-50 via-emerald-50/30 to-slate-50 relative overflow-hidden" style="content-visibility: auto; contain-intrinsic-size: 0 800px;">
    <!-- Modern Decorative Elements (reduced on mobile) -->
    <div class="absolute top-0 right-0 w-64 h-64 md:w-[500px] md:h-[500px] bg-gradient-to-br from-emerald-200/30 to-teal-200/20 rounded-full blur-[80px] md:blur-[100px] pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 w-48 h-48 md:w-[400px] md:h-[400px] bg-gradient-to-tr from-emerald-300/20 to-emerald-100/10 rounded-full blur-[60px] md:blur-[80px] pointer-events-none hidden sm:block"></div>

    <div class="container mx-auto px-4 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-16 items-center">
            <!-- Gambar di kiri - Hidden on Mobile -->
            <div class="lg:col-span-5 order-2 lg:order-1 hidden sm:block">
                <div class="relative group max-w-xs mx-auto lg:max-w-none">
                    <!-- Glow Effect (smaller on mobile) -->
                    <div class="absolute -inset-4 md:-inset-8 bg-gradient-to-br from-emerald-400/20 via-emerald-300/10 to-transparent rounded-2xl md:rounded-[3rem] blur-xl opacity-60 group-hover:opacity-80 transition duration-700"></div>

                    <!-- Main card -->
                    <div class="relative bg-gradient-to-br from-white to-emerald-50/50 rounded-xl md:rounded-[2rem] p-2 md:p-3 shadow-2xl shadow-emerald-200/50 border border-white/80 backdrop-blur-sm">
                        <!-- Inner Image Container with CLS prevention -->
                        <div class="relative aspect-[4/5] rounded-xl md:rounded-[1.5rem] overflow-hidden bg-gradient-to-br from-emerald-600 to-emerald-800">
                            <!-- Placeholder untuk mencegah CLS -->
                            <div class="absolute inset-0 bg-emerald-700 animate-pulse"></div>
                            @if($settings->logo_path && file_exists(public_path('storage/' . $settings->logo_path)))
                            <img src="{{ asset('storage/' . $settings->logo_path) }}" alt="Logo Kemenag Nganjuk" width="400" height="500" class="w-full h-full object-contain p-4 md:p-8 mix-blend-luminosity group-hover:mix-blend-normal transition-all duration-700">
                            @else
                            <img src="{{ asset('logo-kemenag.png') }}" alt="Logo Kemenag Nganjuk" width="400" height="500" class="w-full h-full object-contain p-6 md:p-10 group-hover:scale-105 transition-transform duration-700" style="aspect-ratio: 4/5;">
                            @endif

                            <!-- Overlay Gradient -->
                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>

                            <!-- Floating Badge (smaller on mobile) -->
                            <div class="absolute top-3 right-3 md:top-6 md:right-6 bg-white/95 backdrop-blur-md rounded-xl md:rounded-2xl px-3 py-2 md:px-5 md:py-3 shadow-xl">
                                <p class="text-emerald-700 font-bold text-sm md:text-lg tracking-tight">SENYUM</p>
                                <p class="text-emerald-600/70 text-[10px] md:text-xs hidden sm:block">Sehat, Nyaman, Unggul & Maju</p>
                            </div>

                            <!-- Bottom Info (hidden on mobile) -->
                            <div class="absolute bottom-0 left-0 right-0 p-4 md:p-6 bg-gradient-to-t from-black/80 to-transparent hidden md:block">
                                <p class="text-white/90 text-sm font-light leading-relaxed">
                                    "Melayani umat agama dengan excellence dan inovasi"
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Decorative Corner Elements (hidden on mobile) -->
                    <div class="absolute -top-2 -left-2 md:-top-4 md:-left-4 w-8 h-8 md:w-16 md:h-16 border-l-2 md:border-l-4 border-t-2 md:border-t-4 border-emerald-300/50 rounded-tl-xl md:rounded-tl-3xl hidden md:block"></div>
                    <div class="absolute -bottom-2 -right-2 md:-bottom-4 md:-right-4 w-8 h-8 md:w-16 md:h-16 border-r-2 md:border-r-4 border-b-2 md:border-b-4 border-emerald-300/50 rounded-br-xl md:rounded-br-3xl hidden md:block"></div>
                </div>
            </div>

            <!-- Konten di kanan - Compact on Mobile -->
            <div class="lg:col-span-7 order-1 lg:order-2" x-data="sekilasTabs()">
                <!-- Compact Header -->
                <div class="mb-4 md:mb-10">
                    <h2 class="text-2xl md:text-4xl lg:text-5xl font-bold text-gray-900 tracking-tight leading-[1.1] mb-2 md:mb-4">
                        Sekilas
                        <span class="bg-gradient-to-r from-emerald-600 to-teal-500 bg-clip-text text-transparent">Kemenag</span>
                    </h2>
                    <p class="text-gray-500 text-sm md:text-base max-w-xl leading-relaxed">
                        Mengenal lebih dekat Kemenag Nganjuk
                    </p>
                </div>

                <!-- Compact Tab Pills -->
                <div class="flex flex-wrap gap-2 md:gap-3 mb-4 md:mb-8">
                    <button
                        @click="activeTab = 'sejarah'"
                        :class="activeTab === 'sejarah' ? 'bg-gradient-to-r from-emerald-600 to-teal-500 text-white shadow-lg shadow-emerald-500/30 scale-105' : 'bg-white text-gray-600 hover:bg-gray-50 shadow-sm hover:shadow-md'"
                        class="px-3 py-2 md:px-6 md:py-3 rounded-full font-bold text-xs md:text-sm tracking-wide transition-all duration-300 border border-gray-100/50"
                    >
                        <span class="flex items-center gap-1 md:gap-2">
                            <svg class="w-3 h-3 md:w-4 md:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="hidden sm:inline">Tentang Kami</span>
                            <span class="sm:hidden">Tentang</span>
                        </span>
                    </button>
                    <button
                        @click="activeTab = 'visi'"
                        :class="activeTab === 'visi' ? 'bg-gradient-to-r from-emerald-600 to-teal-500 text-white shadow-lg shadow-emerald-500/30 scale-105' : 'bg-white text-gray-600 hover:bg-gray-50 shadow-sm hover:shadow-md'"
                        class="px-3 py-2 md:px-6 md:py-3 rounded-full font-bold text-xs md:text-sm tracking-wide transition-all duration-300 border border-gray-100/50"
                    >
                        <span class="flex items-center gap-1 md:gap-2">
                            <svg class="w-3 h-3 md:w-4 md:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            Visi
                        </span>
                    </button>
                    <button
                        @click="activeTab = 'misi'"
                        :class="activeTab === 'misi' ? 'bg-gradient-to-r from-emerald-600 to-teal-500 text-white shadow-lg shadow-emerald-500/30 scale-105' : 'bg-white text-gray-600 hover:bg-gray-50 shadow-sm hover:shadow-md'"
                        class="px-3 py-2 md:px-6 md:py-3 rounded-full font-bold text-xs md:text-sm tracking-wide transition-all duration-300 border border-gray-100/50"
                    >
                        <span class="flex items-center gap-1 md:gap-2">
                            <svg class="w-3 h-3 md:w-4 md:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            Misi
                        </span>
                    </button>
                    <button
                        @click="activeTab = 'motto'"
                        :class="activeTab === 'motto' ? 'bg-gradient-to-r from-emerald-600 to-teal-500 text-white shadow-lg shadow-emerald-500/30 scale-105' : 'bg-white text-gray-600 hover:bg-gray-50 shadow-sm hover:shadow-md'"
                        class="px-3 py-2 md:px-6 md:py-3 rounded-full font-bold text-xs md:text-sm tracking-wide transition-all duration-300 border border-gray-100/50"
                    >
                        <span class="flex items-center gap-1 md:gap-2">
                            <svg class="w-3 h-3 md:w-4 md:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                            Motto
                        </span>
                    </button>
                </div>

                <!-- Compact Content Card -->
                <div class="relative bg-white rounded-xl md:rounded-3xl p-4 md:p-8 shadow-xl shadow-gray-200/50 border border-gray-100/50 overflow-hidden">
                    <!-- Content Area -->
                    <div class="relative min-h-[120px] md:min-h-[200px]">
                        <!-- Sejarah Tab -->
                        <div x-show="activeTab === 'sejarah'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                            <div class="flex items-start gap-3 md:gap-4 mb-4 md:mb-6">
                                <div class="w-10 h-10 md:w-14 md:h-14 bg-gradient-to-br from-emerald-100 to-teal-100 rounded-xl md:rounded-2xl flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 md:w-7 md:h-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg md:text-2xl font-bold text-gray-900 mb-1 md:mb-2">Tentang Kami</h3>
                                    <p class="text-emerald-600 font-medium text-xs md:text-sm">Kantor Kemenag Nganjuk</p>
                                </div>
                            </div>
                            @if($pagesSejarah)
                            <p class="text-gray-600 leading-relaxed text-sm md:text-base mb-4 md:mb-8 line-clamp-2 md:line-clamp-3">{{ html_entity_decode(strip_tags($pagesSejarah->content)) }}</p>
                            <a href="{{ route('pages.show', $pagesSejarah->slug) }}" class="group inline-flex items-center gap-2 md:gap-3 bg-gradient-to-r from-emerald-600 to-teal-500 text-white px-4 py-2 md:px-6 md:py-3 rounded-full font-semibold text-sm hover:shadow-lg hover:shadow-emerald-500/30 transition-all duration-300">
                                Baca Selengkapnya
                                <svg class="w-4 h-4 md:w-5 md:h-5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                </svg>
                            </a>
                            @else
                            <div class="text-center py-4 md:py-8">
                                <div class="w-12 h-12 md:w-16 md:h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3 md:mb-4">
                                    <svg class="w-6 h-6 md:w-8 md:h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <p class="text-gray-500 text-sm">Data sejarah belum tersedia.</p>
                            </div>
                            @endif
                        </div>

                        <!-- Visi Tab -->
                        <div x-show="activeTab === 'visi'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                            <div class="flex items-start gap-3 md:gap-4 mb-4 md:mb-6">
                                <div class="w-10 h-10 md:w-14 md:h-14 bg-gradient-to-br from-emerald-100 to-teal-100 rounded-xl md:rounded-2xl flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 md:w-7 md:h-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg md:text-2xl font-bold text-gray-900 mb-1 md:mb-2">Visi</h3>
                                    <p class="text-emerald-600 font-medium text-xs md:text-sm">Visi Kemenag Nganjuk</p>
                                </div>
                            </div>
                            @if($pagesVisi)
                            <p class="text-gray-600 leading-relaxed text-sm md:text-base mb-4 md:mb-8 line-clamp-2 md:line-clamp-3">{{ html_entity_decode(strip_tags($pagesVisi->content)) }}</p>
                            <a href="{{ route('pages.show', $pagesVisi->slug) }}" class="group inline-flex items-center gap-2 md:gap-3 bg-gradient-to-r from-emerald-600 to-teal-500 text-white px-4 py-2 md:px-6 md:py-3 rounded-full font-semibold text-sm hover:shadow-lg hover:shadow-emerald-500/30 transition-all duration-300">
                                Baca Selengkapnya
                                <svg class="w-4 h-4 md:w-5 md:h-5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                </svg>
                            </a>
                            @else
                            <div class="text-center py-4 md:py-8">
                                <div class="w-12 h-12 md:w-16 md:h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3 md:mb-4">
                                    <svg class="w-6 h-6 md:w-8 md:h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <p class="text-gray-500 text-sm">Data visi belum tersedia.</p>
                            </div>
                            @endif
                        </div>

                        <!-- Misi Tab -->
                        <div x-show="activeTab === 'misi'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                            <div class="flex items-start gap-3 md:gap-4 mb-4 md:mb-6">
                                <div class="w-10 h-10 md:w-14 md:h-14 bg-gradient-to-br from-emerald-100 to-teal-100 rounded-xl md:rounded-2xl flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 md:w-7 md:h-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg md:text-2xl font-bold text-gray-900 mb-1 md:mb-2">Misi</h3>
                                    <p class="text-emerald-600 font-medium text-xs md:text-sm">Misi Kemenag Nganjuk</p>
                                </div>
                            </div>
                            @if($pagesMisi)
                            <p class="text-gray-600 leading-relaxed text-sm md:text-base mb-4 md:mb-8 line-clamp-2 md:line-clamp-3">{{ html_entity_decode(strip_tags($pagesMisi->content)) }}</p>
                            <a href="{{ route('pages.show', $pagesMisi->slug) }}" class="group inline-flex items-center gap-2 md:gap-3 bg-gradient-to-r from-emerald-600 to-teal-500 text-white px-4 py-2 md:px-6 md:py-3 rounded-full font-semibold text-sm hover:shadow-lg hover:shadow-emerald-500/30 transition-all duration-300">
                                Baca Selengkapnya
                                <svg class="w-4 h-4 md:w-5 md:h-5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                </svg>
                            </a>
                            @else
                            <div class="text-center py-4 md:py-8">
                                <div class="w-12 h-12 md:w-16 md:h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3 md:mb-4">
                                    <svg class="w-6 h-6 md:w-8 md:h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <p class="text-gray-500 text-sm">Data misi belum tersedia.</p>
                            </div>
                            @endif
                        </div>

                        <!-- Motto Tab -->
                        <div x-show="activeTab === 'motto'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                            <div class="flex items-start gap-3 md:gap-4 mb-4 md:mb-6">
                                <div class="w-10 h-10 md:w-14 md:h-14 bg-gradient-to-br from-emerald-100 to-teal-100 rounded-xl md:rounded-2xl flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 md:w-7 md:h-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg md:text-2xl font-bold text-gray-900 mb-1 md:mb-2">Motto</h3>
                                    <p class="text-emerald-600 font-medium text-xs md:text-sm">Semangat Kemenag Nganjuk</p>
                                </div>
                            </div>
                            @if($pagesMotto)
                            <p class="text-gray-600 leading-relaxed text-sm md:text-base mb-4 md:mb-8 line-clamp-2 md:line-clamp-3">{{ html_entity_decode(strip_tags($pagesMotto->content)) }}</p>
                            <a href="{{ route('pages.show', $pagesMotto->slug) }}" class="group inline-flex items-center gap-2 md:gap-3 bg-gradient-to-r from-emerald-600 to-teal-500 text-white px-4 py-2 md:px-6 md:py-3 rounded-full font-semibold text-sm hover:shadow-lg hover:shadow-emerald-500/30 transition-all duration-300">
                                Baca Selengkapnya
                                <svg class="w-4 h-4 md:w-5 md:h-5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                </svg>
                            </a>
                            @else
                            <div class="bg-gradient-to-br from-emerald-50 to-teal-50 rounded-xl md:rounded-2xl p-4 md:p-8 text-center border border-emerald-100">
                                <div class="w-12 h-12 md:w-20 md:h-20 bg-gradient-to-br from-emerald-500 to-teal-500 rounded-full flex items-center justify-center mx-auto mb-3 md:mb-6 shadow-lg shadow-emerald-500/30">
                                    <span class="text-white font-black text-lg md:text-2xl">S</span>
                                </div>
                                <p class="text-xl md:text-3xl font-bold text-emerald-700 mb-2 md:mb-4">SENYUM</p>
                                <p class="text-gray-600 leading-relaxed mb-4 md:mb-6 text-sm">
                                    <span class="font-bold text-emerald-600">S</span>ehat,
                                    <span class="font-bold text-emerald-600">N</span>yam,
                                    <span class="font-bold text-emerald-600">Y</span>ang,
                                    <span class="font-bold text-emerald-600">U</span>ntuk,
                                    <span class="font-bold text-emerald-600">M</span>asyarakat
                                </p>
                                <p class="text-emerald-600 font-medium italic text-sm">"Sehat, Nyaman, Unggul, dan Maju"</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ===================== STATISTICS SECTION ===================== -->
<section class="relative py-12 md:py-16 overflow-hidden">
    <!-- Background Gradient -->
    <div class="absolute inset-0 bg-gradient-to-br from-emerald-800 via-emerald-700 to-emerald-900"></div>

    <!-- Decorative Overlay -->
    <div class="absolute inset-0 opacity-15">
        <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
            <defs>
                <pattern id="dots" width="20" height="20" patternUnits="userSpaceOnUse">
                    <circle cx="2" cy="2" r="1" fill="rgba(251, 191, 36, 0.5)"/>
                </pattern>
            </defs>
            <rect width="100" height="100" fill="url(#dots)"/>
        </svg>
    </div>

    <!-- Decorative Elements -->
    <div class="absolute top-4 left-0 w-24 h-[1px] bg-gradient-to-r from-amber-400 to-transparent"></div>
    <div class="absolute top-4 right-0 w-24 h-[1px] bg-gradient-to-l from-amber-400 to-transparent"></div>
    <div class="absolute bottom-4 left-1/2 w-40 h-[1px] bg-gradient-to-r from-transparent via-amber-400 to-transparent transform -translate-x-1/2"></div>

    <!-- Floating Shapes -->
    <div class="absolute top-12 left-[5%] w-16 h-16 border border-white/20 rounded-full hidden md:block"></div>
    <div class="absolute bottom-12 right-[10%] w-12 h-12 border border-amber-400/30 rotate-45 hidden md:block"></div>

    <div class="container mx-auto px-4 relative z-10">
        <!-- Section Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center gap-2 mb-3">
                <span class="w-8 h-[1px] bg-amber-400"></span>
                <span class="text-amber-400 text-xs font-semibold tracking-widest uppercase">Statistik</span>
                <span class="w-8 h-[1px] bg-amber-400"></span>
            </div>
            <h2 class="text-2xl md:text-3xl font-bold text-white mb-2 tracking-tight">
                Kemenag Nganjuk <span class="text-amber-400">dalam Angka</span>
            </h2>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-4 gap-3 md:gap-4">
            <!-- Card 1: Berita -->
            <div class="group relative">
                <div class="relative bg-white/10 backdrop-blur-sm rounded-xl p-4 md:p-5 border border-white/10 hover:border-amber-400/30 transition-all duration-300 hover:-translate-y-1 text-center">
                    <div class="w-10 h-10 md:w-12 md:h-12 bg-gradient-to-br from-amber-400/20 to-amber-500/10 rounded-full flex items-center justify-center mx-auto mb-3 group-hover:scale-105 transition-transform">
                        <svg class="w-5 h-5 md:w-6 md:h-6 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                        </svg>
                    </div>
                    <div class="text-2xl md:text-3xl font-bold text-white mb-1">{{ $latestPosts->count() }}+</div>
                    <div class="text-emerald-100/70 text-xs md:text-sm">Berita</div>
                </div>
            </div>

            <!-- Card 2: Agenda -->
            <div class="group relative">
                <div class="relative bg-white/10 backdrop-blur-sm rounded-xl p-4 md:p-5 border border-white/10 hover:border-amber-400/30 transition-all duration-300 hover:-translate-y-1 text-center">
                    <div class="w-10 h-10 md:w-12 md:h-12 bg-gradient-to-br from-amber-400/20 to-amber-500/10 rounded-full flex items-center justify-center mx-auto mb-3 group-hover:scale-105 transition-transform">
                        <svg class="w-5 h-5 md:w-6 md:h-6 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div class="text-2xl md:text-3xl font-bold text-white mb-1">{{ $upcomingAgendas->count() }}+</div>
                    <div class="text-emerald-100/70 text-xs md:text-sm">Agenda</div>
                </div>
            </div>

            <!-- Card 3: Download -->
            <div class="group relative">
                <div class="relative bg-white/10 backdrop-blur-sm rounded-xl p-4 md:p-5 border border-white/10 hover:border-amber-400/30 transition-all duration-300 hover:-translate-y-1 text-center">
                    <div class="w-10 h-10 md:w-12 md:h-12 bg-gradient-to-br from-amber-400/20 to-amber-500/10 rounded-full flex items-center justify-center mx-auto mb-3 group-hover:scale-105 transition-transform">
                        <svg class="w-5 h-5 md:w-6 md:h-6 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div class="text-2xl md:text-3xl font-bold text-white mb-1">{{ $latestDownloads->count() }}+</div>
                    <div class="text-emerald-100/70 text-xs md:text-sm">Download</div>
                </div>
            </div>

            <!-- Card 4: Layanan -->
            <div class="group relative">
                <div class="relative bg-white/10 backdrop-blur-sm rounded-xl p-4 md:p-5 border border-white/10 hover:border-amber-400/30 transition-all duration-300 hover:-translate-y-1 text-center">
                    <div class="w-10 h-10 md:w-12 md:h-12 bg-gradient-to-br from-amber-400/20 to-amber-500/10 rounded-full flex items-center justify-center mx-auto mb-3 group-hover:scale-105 transition-transform">
                        <svg class="w-5 h-5 md:w-6 md:h-6 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                        </svg>
                    </div>
                    <div class="text-2xl md:text-3xl font-bold text-white mb-1">{{ $externalLinks->count() }}+</div>
                    <div class="text-emerald-100/70 text-xs md:text-sm">Layanan</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ===================== BERITA TERBARU SECTION ===================== -->
<section id="berita" class="py-12 md:py-16 bg-gray-50 relative overflow-hidden">
    <!-- Decorative Elements -->
    <div class="absolute inset-0 opacity-10 pointer-events-none">
        <div class="absolute top-0 left-0 w-72 h-72 bg-white rounded-full -translate-x-1/2 -translate-y-1/2"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-white rounded-full translate-x-1/2 translate-y-1/2"></div>
        <div class="absolute top-1/4 left-1/4 w-32 h-32 border-4 border-emerald-400 rotate-45"></div>
        <div class="absolute bottom-1/3 right-1/3 w-24 h-24 border-4 border-emerald-400 rounded-full"></div>
    </div>

    <div class="container mx-auto px-4 relative z-10">
        <!-- Section Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
            <div>
                <h2 class="text-2xl md:text-3xl font-bold text-gray-800 flex items-center gap-2">
                    <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                    </svg>
                    Berita Terbaru
                </h2>
                <p class="text-gray-600 mt-1">Informasi terkini dari Kementerian Agama Kabupaten Nganjuk</p>
            </div>
            <a href="{{ route('posts.index') }}" class="inline-flex items-center gap-2 text-emerald-600 hover:text-emerald-700 font-medium">
                Lihat Semua Berita
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
        </div>

        <!-- News Grid - Mobile: List Horizontal | Desktop: Grid -->
        @if($latestPosts->count() > 0)
        <div class="space-y-3 md:space-y-0 md:grid md:grid-cols-2 lg:grid-cols-4 md:gap-6">
            @foreach($latestPosts as $post)
            <article class="bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-lg transition-shadow group">
                <!-- Mobile: flex-row thumbnail-left, Desktop: block stacked -->
                <a href="{{ route('posts.show', $post->slug) }}" class="flex flex-row sm:flex-col">
                    <!-- Thumbnail - Square on mobile (left), Full width on desktop -->
                    <div class="relative w-28 sm:w-full h-28 sm:h-44 flex-shrink-0 overflow-hidden bg-gradient-to-br from-emerald-100 to-emerald-200">
                        @if($post->thumbnail_url)
                        <img src="{{ $post->thumbnail_url }}" alt="{{ $post->title }}" width="400" height="300" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy">
                        @else
                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-emerald-400 to-emerald-600">
                            <svg class="w-10 h-10 text-white opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                            </svg>
                        </div>
                        @endif
                        @if($post->category)
                        <span class="absolute top-2 left-2 px-2 py-0.5 bg-emerald-600 text-white text-[10px] sm:text-xs font-medium rounded-full hidden sm:block">
                            {{ $post->category->name }}
                        </span>
                        @endif
                    </div>

                    <!-- Content -->
                    <div class="flex-1 p-3 sm:p-5 flex flex-col justify-center">
                        @if($post->category)
                        <span class="sm:hidden inline-block px-2 py-0.5 bg-emerald-600 text-white text-[10px] font-medium rounded-full mb-1 w-fit">
                            {{ Str::limit($post->category->name, 8) }}
                        </span>
                        @endif
                        <h3 class="font-semibold text-gray-800 mb-1 line-clamp-2 group-hover:text-emerald-600 transition-colors text-sm sm:text-base">
                            {{ $post->title }}
                        </h3>
                        <p class="text-gray-600 text-xs line-clamp-2 sm:text-sm sm:line-clamp-2">{{ Str::limit(strip_tags(html_entity_decode($post->subtitle ?? $post->content)), 100) }}</p>
                        <div class="flex items-center gap-3 text-xs text-gray-500 mt-2">
                            <span class="flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span class="text-[10px] sm:text-xs">{{ $post->published_at ? $post->published_at->diffForHumans() : '' }}</span>
                            </span>
                            <span class="flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <span class="text-[10px] sm:text-xs">{{ $post->views ?? 0 }}</span>
                            </span>
                        </div>
                    </div>
                </a>
            </article>
            @endforeach
        </div>
        @else
        <!-- Empty State -->
        <div class="text-center py-12 bg-white rounded-xl">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
            </svg>
            <p class="text-gray-500">Belum ada berita yang dipublikasikan</p>
        </div>
        @endif
    </div>
</section>

<!-- ===================== AGENDA, DOWNLOAD & LAYANAN SECTION ===================== -->
<section class="py-12 md:py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Agenda Column -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-emerald-600 px-4 py-3 flex items-center justify-between">
                    <h3 class="text-white font-bold flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Agenda
                    </h3>
                    <a href="{{ route('agendas.index') }}" class="text-emerald-100 hover:text-white text-xs font-medium">Lihat Semua</a>
                </div>
                <div class="p-4">
                    @if($upcomingAgendas->count() > 0)
                    <div class="space-y-3">
                        @foreach($upcomingAgendas->take(3) as $agenda)
                        <a href="{{ route('agendas.show', $agenda->slug) }}" class="block p-3 rounded-lg hover:bg-gray-50 transition-colors border border-gray-100">
                            <div class="flex items-start gap-3">
                                <div class="bg-emerald-100 text-emerald-700 rounded-lg px-2 py-1 text-center min-w-[50px]">
                                    <div class="text-lg font-bold">{{ $agenda->start_date ? \Carbon\Carbon::parse($agenda->start_date)->format('d') : '-' }}</div>
                                    <div class="text-[10px] uppercase">{{ $agenda->start_date ? \Carbon\Carbon::parse($agenda->start_date)->format('M') : '' }}</div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-medium text-gray-800 text-sm line-clamp-2">{{ $agenda->title }}</h4>
                                    @if($agenda->location)
                                    <p class="text-gray-500 text-xs mt-1 truncate">{{ $agenda->location }}</p>
                                    @endif
                                </div>
                            </div>
                        </a>
                        @endforeach
                    </div>
                    @else
                    <p class="text-gray-500 text-sm text-center py-4">Belum ada agenda</p>
                    @endif
                </div>
            </div>

            <!-- Download Column -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-emerald-600 px-4 py-3 flex items-center justify-between">
                    <h3 class="text-white font-bold flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Download
                    </h3>
                    <a href="{{ route('downloads.index') }}" class="text-emerald-100 hover:text-white text-xs font-medium">Lihat Semua</a>
                </div>
                <div class="p-4">
                    @if($latestDownloads->count() > 0)
                    <div class="space-y-2">
                        @foreach($latestDownloads->take(4) as $download)
                        <a href="{{ asset('storage/' . $download->file_path) }}" target="_blank" class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-50 transition-colors">
                            <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <span class="text-emerald-600 font-bold text-[10px]">{{ $download->extension ?? 'PDF' }}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="font-medium text-gray-800 text-sm truncate">{{ $download->title }}</h4>
                                <p class="text-gray-400 text-xs">{{ $download->file_size_formatted ?? '' }}</p>
                            </div>
                            <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                        </a>
                        @endforeach
                    </div>
                    @else
                    <p class="text-gray-500 text-sm text-center py-4">Belum ada dokumen</p>
                    @endif
                </div>
            </div>

            <!-- Layanan Column -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-emerald-600 px-4 py-3 flex items-center justify-between">
                    <h3 class="text-white font-bold flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                        </svg>
                        Layanan
                    </h3>
                </div>
                <div class="p-4">
                    @if($externalLinks->count() > 0)
                    <div class="grid grid-cols-2 gap-2">
                        @foreach($externalLinks->take(6) as $link)
                        <a href="{{ $link->url }}" target="_blank" rel="noopener noreferrer" class="flex flex-col items-center p-3 rounded-lg hover:bg-emerald-50 transition-colors text-center">
                            <div class="w-10 h-10 bg-emerald-100 rounded-full flex items-center justify-center mb-2">
                                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                </svg>
                            </div>
                            <h4 class="text-gray-700 text-xs font-medium line-clamp-2">{{ $link->title }}</h4>
                        </a>
                        @endforeach
                    </div>
                    @else
                    <p class="text-gray-500 text-sm text-center py-4">Belum ada tautan</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ===================== GOOGLE MAPS SECTION ===================== -->
<section class="bg-emerald-900 py-8" style="content-visibility: auto; contain-intrinsic-size: 0 400px;">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-center">
            <div class="lg:col-span-8">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3954.6677388334288!2d111.8991191768781!3d-7.6110870752418815!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e784b0a4b1c6983%3A0xcac034becf05e4ff!2sKementerian%20Agama%20Kabupaten%20Nganjuk!5e0!3m2!1sid!2sid!4v1778387725628!5m2!1sid!2sid"
                    width="100%"
                    height="350"
                    style="border:0; border-radius: 0.75rem;"
                    allowfullscreen=""
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"
                ></iframe>
            </div>
            <div class="lg:col-span-4 text-center lg:text-left">
                <h3 class="text-white text-xl font-bold mb-2">Lokasi Kami</h3>
                <p class="text-emerald-200 mb-4">Kementerian Agama Kabupaten Nganjuk</p>
                <p class="text-emerald-300 text-sm">Jalan Dermojoyo 22, Payaman,<br>Kec. Nganjuk, Kabupaten Nganjuk,<br>Jawa Timur</p>
                <a href="https://maps.google.com/?q=Kementerian+Agama+Kabupaten+Nganjuk" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 mt-4 bg-white text-emerald-700 px-4 py-2 rounded-lg font-medium hover:bg-emerald-50 transition-colors text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    </svg>
                    Buka di Maps
                </a>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
    // CSS-only Hero Carousel - Navigation controls (no Alpine.js dependency for LCP)
    // First slide is visible immediately via CSS, JS only handles manual navigation
    (function() {
        const totalSlides = {{ $headlinePosts->count() }};
        let currentSlide = 0;
        let autoplayInterval = null;

        function updateCarousel(index) {
            currentSlide = ((index % totalSlides) + totalSlides) % totalSlides;
            const slides = document.querySelectorAll('.hero-slide');
            const indicators = document.querySelectorAll('.hero-indicator');

            slides.forEach((slide, i) => {
                if (i === currentSlide) {
                    slide.classList.add('hero-slide--active');
                    slide.style.zIndex = '2';
                    slide.style.opacity = '1';
                } else {
                    slide.classList.remove('hero-slide--active');
                    slide.style.zIndex = '1';
                    slide.style.opacity = '0';
                }
            });

            indicators.forEach((indicator, i) => {
                if (i === currentSlide) {
                    indicator.classList.add('hero-indicator--active', 'bg-white', 'w-8');
                    indicator.classList.remove('bg-white/50', 'w-3');
                } else {
                    indicator.classList.remove('hero-indicator--active', 'bg-white', 'w-8');
                    indicator.classList.add('bg-white/50', 'w-3');
                }
            });
        }

        function startAutoplay() {
            stopAutoplay();
            autoplayInterval = setInterval(() => {
                updateCarousel(currentSlide + 1);
            }, 10000); // 10 detik untuk transisi lebih lambat
        }

        function stopAutoplay() {
            if (autoplayInterval) clearInterval(autoplayInterval);
        }

        // Expose global functions for onclick handlers
        window.heroCarouselNext = function() {
            updateCarousel(currentSlide + 1);
            stopAutoplay();
            startAutoplay();
        };

        window.heroCarouselPrev = function() {
            updateCarousel(currentSlide - 1);
            stopAutoplay();
            startAutoplay();
        };

        window.heroCarouselGoTo = function(index) {
            updateCarousel(index);
            stopAutoplay();
            startAutoplay();
        };

        // Initialize: first slide visible, start autoplay
        document.addEventListener('DOMContentLoaded', function() {
            updateCarousel(0);
            startAutoplay();
        });
    })();

    // Sekilas Kemenag Tabs
    function sekilasTabs() {
        return {
            activeTab: 'sejarah',
            setTab(tab) {
                this.activeTab = tab;
            },
            isActive(tab) {
                return this.activeTab === tab
                    ? 'bg-emerald-600 text-white shadow-xl shadow-emerald-600/30 md:scale-105'
                    : 'bg-gray-50 text-gray-400 hover:bg-gray-100';
            }
        }
    }
</script>
@endpush
