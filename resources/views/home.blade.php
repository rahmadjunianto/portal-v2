@extends('layouts.app')

@section('content')
<!-- ===================== HERO SECTION ===================== -->
<section class="relative overflow-hidden">
    <!-- Background Pattern -->
    <div class="absolute inset-0 bg-gradient-to-br from-emerald-800 via-emerald-700 to-emerald-600">
        <div class="absolute inset-0 opacity-10" style="background-image: url('data:image/svg+xml,%3Csvg width=\"60\" height=\"60\" viewBox=\"0 0 60 60\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg fill=\"none\" fill-rule=\"evenodd\"%3E%3Cg fill=\"%23ffffff\" fill-opacity=\"0.4\"%3E%3Cpath d=\"M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
    </div>

    @if($headlinePosts->count() > 0)
    <!-- Hero Carousel -->
    <div x-data="heroSlider()" x-init="init()" class="relative h-[500px] md:h-[550px]">
        <!-- Slides -->
        <div class="relative h-full">
            @foreach($headlinePosts as $index => $post)
            <div
                x-show="active === {{ $index }}"
                x-transition:enter="transition ease-out duration-500"
                x-transition:enter-start="opacity-0 transform translate-x-full"
                x-transition:enter-end="opacity-100 transform translate-x-0"
                x-transition:leave="transition ease-in duration-500"
                x-transition:leave-start="opacity-100 transform translate-x-0"
                x-transition:leave-end="opacity-0 transform -translate-x-full"
                class="absolute inset-0"
            >
                <!-- Image Background -->
                @if($post->thumbnail)
                <img src="{{ asset('storage/' . $post->thumbnail) }}" alt="{{ $post->title }}" class="w-full h-full object-cover">
                @else
                <div class="w-full h-full bg-gradient-to-br from-emerald-700 to-emerald-900"></div>
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

        <!-- Navigation Arrows -->
        @if($headlinePosts->count() > 1)
        <button @click="prev()" class="absolute left-4 top-1/2 -translate-y-1/2 bg-white/20 hover:bg-white/40 backdrop-blur-sm text-white p-3 rounded-full transition-all">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </button>
        <button @click="next()" class="absolute right-4 top-1/2 -translate-y-1/2 bg-white/20 hover:bg-white/40 backdrop-blur-sm text-white p-3 rounded-full transition-all">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </button>

        <!-- Indicators -->
        <div class="absolute bottom-6 left-1/2 -translate-x-1/2 flex gap-2">
            @foreach($headlinePosts as $index => $post)
            <button
                @click="goTo({{ $index }})"
                :class="active === {{ $index }} ? 'bg-white w-8' : 'bg-white/50 w-3'"
                class="h-3 rounded-full transition-all duration-300"
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

<!-- ===================== STATISTICS BAR ===================== -->
<section class="bg-white py-6 shadow-sm border-b">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
            <div class="p-4">
                <div class="text-3xl font-bold text-emerald-600">{{ $latestPosts->count() }}+</div>
                <div class="text-gray-600 text-sm">Berita</div>
            </div>
            <div class="p-4">
                <div class="text-3xl font-bold text-emerald-600">{{ $upcomingAgendas->count() }}+</div>
                <div class="text-gray-600 text-sm">Agenda</div>
            </div>
            <div class="p-4">
                <div class="text-3xl font-bold text-emerald-600">{{ $latestDownloads->count() }}+</div>
                <div class="text-gray-600 text-sm">Unduhan</div>
            </div>
            <div class="p-4">
                <div class="text-3xl font-bold text-emerald-600">{{ $externalLinks->count() }}+</div>
                <div class="text-gray-600 text-sm">Layanan</div>
            </div>
        </div>
    </div>
</section>

<!-- ===================== BERITA TERBARU SECTION ===================== -->
<section id="berita" class="py-12 md:py-16 bg-gray-50">
    <div class="container mx-auto px-4">
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

        <!-- News Grid -->
        @if($latestPosts->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($latestPosts as $post)
            <article class="bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-lg transition-shadow group">
                <!-- Thumbnail -->
                <a href="{{ route('posts.show', $post->slug) }}" class="block relative overflow-hidden">
                    @if($post->thumbnail)
                    <img src="{{ asset('storage/' . $post->thumbnail) }}" alt="{{ $post->title }}" class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-500">
                    @else
                    <div class="w-full h-48 bg-gradient-to-br from-emerald-100 to-emerald-200 flex items-center justify-center">
                        <svg class="w-16 h-16 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    @endif
                    @if($post->category)
                    <span class="absolute top-3 left-3 px-3 py-1 bg-emerald-600 text-white text-xs font-medium rounded-full">
                        {{ $post->category->name }}
                    </span>
                    @endif
                </a>

                <!-- Content -->
                <div class="p-5">
                    <h3 class="font-semibold text-gray-800 mb-2 line-clamp-2 group-hover:text-emerald-600 transition-colors">
                        <a href="{{ route('posts.show', $post->slug) }}">{{ $post->title }}</a>
                    </h3>
                    <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ $post->subtitle ?? strip_tags($post->content) }}</p>
                    <div class="flex items-center justify-between text-xs text-gray-500">
                        <span class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            {{ $post->published_at ? $post->published_at->diffForHumans() : '' }}
                        </span>
                        <span class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            {{ $post->views }} views
                        </span>
                    </div>
                </div>
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

<!-- ===================== AGENDA SECTION ===================== -->
<section class="py-12 md:py-16 bg-white">
    <div class="container mx-auto px-4">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
            <div>
                <h2 class="text-2xl md:text-3xl font-bold text-gray-800 flex items-center gap-2">
                    <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Agenda Terkini
                </h2>
                <p class="text-gray-600 mt-1">Jadwal kegiatan Kementerian Agama Kabupaten Nganjuk</p>
            </div>
            <a href="{{ route('agendas.index') }}" class="inline-flex items-center gap-2 text-emerald-600 hover:text-emerald-700 font-medium">
                Lihat Semua Agenda
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
        </div>

        @if($upcomingAgendas->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($upcomingAgendas as $agenda)
            <article class="bg-gray-50 rounded-xl p-6 hover:shadow-lg transition-shadow border border-gray-100 group">
                <!-- Date Badge -->
                <div class="flex items-center gap-3 mb-4">
                    <div class="bg-emerald-600 text-white rounded-lg p-3 text-center min-w-[60px]">
                        <div class="text-2xl font-bold">{{ $agenda->start_date ? \Carbon\Carbon::parse($agenda->start_date)->format('d') : '-' }}</div>
                        <div class="text-xs uppercase">{{ $agenda->start_date ? \Carbon\Carbon::parse($agenda->start_date)->format('M') : '' }}</div>
                    </div>
                    @if($agenda->end_date && $agenda->end_date != $agenda->start_date)
                    <span class="text-gray-400">-</span>
                    <div class="bg-gray-400 text-white rounded-lg p-3 text-center min-w-[60px]">
                        <div class="text-2xl font-bold">{{ \Carbon\Carbon::parse($agenda->end_date)->format('d') }}</div>
                        <div class="text-xs uppercase">{{ \Carbon\Carbon::parse($agenda->end_date)->format('M') }}</div>
                    </div>
                    @endif
                </div>

                <h3 class="font-semibold text-gray-800 mb-2 line-clamp-2 group-hover:text-emerald-600 transition-colors">
                    <a href="{{ route('agendas.show', $agenda->slug) }}">{{ $agenda->title }}</a>
                </h3>

                @if($agenda->location)
                <p class="text-gray-600 text-sm flex items-start gap-2">
                    <svg class="w-4 h-4 mt-0.5 flex-shrink-0 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    {{ $agenda->location }}
                </p>
                @endif

                @if($agenda->event_time_text)
                <p class="text-gray-500 text-sm mt-2 flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ $agenda->event_time_text }}
                </p>
                @endif
            </article>
            @endforeach
        </div>
        @else
        <div class="text-center py-12 bg-gray-50 rounded-xl">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <p class="text-gray-500">Belum ada agenda yang akan datang</p>
        </div>
        @endif
    </div>
</section>

<!-- ===================== DOWNLOAD & LAYANAN SECTION ===================== -->
<section class="py-12 md:py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Download Section -->
            <div>
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                        <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Download
                    </h2>
                    <a href="{{ route('downloads.index') }}" class="text-emerald-600 hover:text-emerald-700 font-medium text-sm">Lihat Semua</a>
                </div>

                @if($latestDownloads->count() > 0)
                <div class="space-y-3">
                    @foreach($latestDownloads as $download)
                    <a href="{{ asset('storage/' . $download->file_path) }}" target="_blank" class="block bg-white rounded-lg p-4 hover:shadow-md transition-shadow border border-gray-100 group">
                        <div class="flex items-center gap-4">
                            <!-- File Icon -->
                            <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <span class="text-emerald-600 font-bold text-xs uppercase">{{ $download->extension ?? 'FILE' }}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="font-medium text-gray-800 truncate group-hover:text-emerald-600 transition-colors">{{ $download->title }}</h4>
                                <div class="flex items-center gap-3 text-xs text-gray-500 mt-1">
                                    @if($download->file_size)
                                    <span>{{ $download->file_size_formatted }}</span>
                                    @endif
                                    <span>{{ $download->published_at ? $download->published_at->format('d M Y') : '' }}</span>
                                </div>
                            </div>
                            <svg class="w-5 h-5 text-gray-400 group-hover:text-emerald-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                        </div>
                    </a>
                    @endforeach
                </div>
                @else
                <div class="text-center py-8 bg-white rounded-lg">
                    <p class="text-gray-500">Belum ada dokumen yang tersedia</p>
                </div>
                @endif
            </div>

            <!-- External Links Section -->
            <div>
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                        <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                        </svg>
                        Layanan Publik
                    </h2>
                </div>

                @if($externalLinks->count() > 0)
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    @foreach($externalLinks as $link)
                    <a
                        href="{{ $link->url }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="bg-white rounded-xl p-4 hover:shadow-lg transition-all border border-gray-100 text-center group"
                    >
                        <div class="w-14 h-14 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-3 group-hover:bg-emerald-600 transition-colors">
                            <svg class="w-7 h-7 text-emerald-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                            </svg>
                        </div>
                        <h4 class="font-medium text-gray-800 text-sm line-clamp-2 group-hover:text-emerald-600 transition-colors">{{ $link->title }}</h4>
                    </a>
                    @endforeach
                </div>
                @else
                <div class="text-center py-8 bg-white rounded-lg">
                    <p class="text-gray-500">Belum ada tautan layanan</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>

<!-- ===================== PROFIL SECTION ===================== -->
<section class="py-12 md:py-16 bg-emerald-700 relative overflow-hidden">
    <!-- Background Pattern -->
    <div class="absolute inset-0 opacity-10">
        <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
            <defs>
                <pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse">
                    <circle cx="1" cy="1" r="1" fill="white"/>
                </pattern>
            </defs>
            <rect width="100" height="100" fill="url(#grid)"/>
        </svg>
    </div>

    <div class="container mx-auto px-4 relative">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div>
                <span class="inline-block px-4 py-1 bg-white/20 text-white text-sm font-medium rounded-full mb-4">Tentang Kami</span>
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">
                    @if($profilePage)
                    {{ $profilePage->title }}
                    @else
                    Kantor Kementerian Agama<br>Kabupaten Nganjuk
                    @endif
                </h2>
                <div class="text-emerald-100 leading-relaxed mb-8">
                    @if($profilePage)
                    <p class="line-clamp-6">{{ strip_tags($profilePage->content) }}</p>
                    @else
                    <p>Kantor Kementerian Agama Kabupaten Nganjuk merupakan instansi pemerintah yang bertugas menangani urusan keagamaan di tingkat kabupaten. Kami berkomitmen untuk memberikan pelayanan terbaik kepada masyarakat dalam bidang keagamaan.</p>
                    <p class="mt-4">Portal ini menyajikan informasi terkini mengenai kegiatan, layanan, dan berbagai programme Kementerian Agama Kabupaten Nganjuk untuk masyarakat.</p>
                    @endif
                </div>
                @if($profilePage)
                <a href="{{ route('pages.show', $profilePage->slug) }}" class="inline-flex items-center gap-2 bg-white text-emerald-700 px-6 py-3 rounded-lg font-semibold hover:bg-emerald-50 transition-colors">
                    Selengkapnya
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
                @endif
            </div>

            <div class="grid grid-cols-2 gap-4">
                <!-- Visi -->
                <div class="bg-white/10 backdrop-blur rounded-xl p-6">
                    <div class="w-12 h-12 bg-emerald-500 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Visi</h3>
                    <p class="text-emerald-100 text-sm">Terwujudnya masyarakat beragama yang religius, sejahtera, dan toleran</p>
                </div>

                <!-- Misi -->
                <div class="bg-white/10 backdrop-blur rounded-xl p-6">
                    <div class="w-12 h-12 bg-emerald-500 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Misi</h3>
                    <p class="text-emerald-100 text-sm">Meningkatkan kualitas pelayanan keagamaan dan pendidikan agama</p>
                </div>

                <!-- Nilai -->
                <div class="bg-white/10 backdrop-blur rounded-xl p-6">
                    <div class="w-12 h-12 bg-emerald-500 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Nilai</h3>
                    <p class="text-emerald-100 text-sm">Integritas, profesional, transparan, dan akuntabel</p>
                </div>

                <!-- Komitmen -->
                <div class="bg-white/10 backdrop-blur rounded-xl p-6">
                    <div class="w-12 h-12 bg-emerald-500 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Komitmen</h3>
                    <p class="text-emerald-100 text-sm">Pelayanan prima untuk kepuasan masyarakat</p>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
    // Hero Slider
    function heroSlider() {
        return {
            active: 0,
            interval: null,
            init() {
                this.startAutoplay();
            },
            startAutoplay() {
                this.interval = setInterval(() => {
                    this.next();
                }, 5000);
            },
            stopAutoplay() {
                clearInterval(this.interval);
            },
            next() {
                const total = {{ $headlinePosts->count() }};
                this.active = (this.active + 1) % total;
            },
            prev() {
                const total = {{ $headlinePosts->count() }};
                this.active = (this.active - 1 + total) % total;
            },
            goTo(index) {
                this.active = index;
                this.stopAutoplay();
                this.startAutoplay();
            }
        }
    }
</script>
@endpush
