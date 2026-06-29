@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-12">
    <!-- Header -->
    <div class="text-center mb-8">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-2">Daftar Berita</h1>
        <p class="text-gray-600 max-w-2xl mx-auto text-sm md:text-base">Informasi terkini dari Kementerian Agama Kabupaten Nganjuk</p>
    </div>

    <!-- Search & Filter -->
    <div class="mb-6 bg-white rounded-xl shadow-sm p-4">
        <form action="{{ route('posts.index') }}" method="GET" class="flex flex-col md:flex-row gap-3">
            <!-- Search Input -->
            <div class="flex-1 relative">
                <input type="text" name="search" placeholder="Cari berita..."
                    value="{{ request('search') }}"
                    class="w-full px-4 py-2 pl-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>

            <!-- Category Dropdown -->
            @if(isset($categories) && $categories->count() > 0)
            <div class="relative min-w-[140px]">
                <select name="category"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 appearance-none bg-white cursor-pointer text-sm"
                        onchange="this.form.submit()">
                    <option value="">Kategori</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat->slug }}" {{ request('category') == $cat->slug ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                    @endforeach
                </select>
                <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </div>
            @endif

            <!-- Tag Dropdown -->
            @if(isset($tags) && $tags->count() > 0)
            <div class="relative min-w-[120px]">
                <select name="tag"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 appearance-none bg-white cursor-pointer text-sm"
                        onchange="this.form.submit()">
                    <option value="">Tag</option>
                    @foreach($tags as $tag)
                    <option value="{{ $tag->slug }}" {{ request('tag') == $tag->slug ? 'selected' : '' }}>
                        {{ $tag->name }}
                    </option>
                    @endforeach
                </select>
                <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </div>
            @endif

            <!-- Search Button -->
            <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors flex items-center gap-2 text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <span class="hidden sm:inline">Cari</span>
            </button>

            <!-- Reset Button -->
            @if(request('search') || request('category') || request('tag'))
            <a href="{{ route('posts.index') }}" class="px-3 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors flex items-center gap-2 text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </a>
            @endif
        </form>

        <!-- Active Filters Display -->
        @if(request('search') || request('category') || request('tag'))
        <div class="mt-3 pt-3 border-t border-gray-200">
            <div class="flex flex-wrap gap-2">
                @if(request('search'))
                <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-emerald-100 text-emerald-700 rounded-full text-xs">
                    "{{ request('search') }}"
                </span>
                @endif
                @if(request('category') && $categories)
                @php $catName = $categories->firstWhere('slug', request('category'))->name ?? request('category'); @endphp
                <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-blue-100 text-blue-700 rounded-full text-xs">
                    {{ $catName }}
                </span>
                @endif
                @if(request('tag') && $tags)
                @php $tagName = $tags->firstWhere('slug', request('tag'))->name ?? request('tag'); @endphp
                <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-purple-100 text-purple-700 rounded-full text-xs">
                    {{ $tagName }}
                </span>
                @endif
            </div>
        </div>
        @endif
    </div>

    <!-- Results Count -->
    @if(isset($posts) && $posts->count() > 0)
    <div class="mb-4">
        <p class="text-gray-600 text-sm">Menampilkan {{ $posts->count() }} dari {{ $posts->total() }} berita</p>
    </div>
    @endif

    <!-- Posts Grid - Mobile: List Horizontal | Desktop: Grid -->
    @if(isset($posts) && $posts->count() > 0)
    <div class="space-y-3 md:space-y-0 md:grid md:grid-cols-2 lg:grid-cols-3 md:gap-6">
        @foreach($posts as $post)
        <article class="bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-lg transition-shadow group">
            <!-- Mobile: flex-row thumbnail-left, Desktop: block stacked -->
            <a href="{{ route('posts.show', $post->slug) }}" class="flex flex-row sm:flex-col">
                <!-- Thumbnail - Square on mobile (left), Full width on desktop -->
                <div class="relative w-28 sm:w-full h-28 sm:h-44 flex-shrink-0 overflow-hidden bg-gradient-to-br from-emerald-100 to-emerald-200">
                    @if($post->thumbnail_url)
                    <img src="{{ $post->thumbnail_url }}" alt="{{ $post->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
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
                    <p class="text-gray-600 text-xs line-clamp-2 sm:text-sm sm:line-clamp-2">{{ Str::limit(html_entity_decode(strip_tags($post->subtitle ?? $post->content)), 80) }}</p>
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

    <!-- Pagination -->
    @if($posts->hasPages())
    <div class="mt-6 flex justify-center">
        <nav class="flex items-center gap-1">
            @if($posts->onFirstPage())
                <span class="px-3 py-2 text-sm text-gray-400 cursor-not-allowed">&laquo;</span>
            @else
                <a href="{{ $posts->previousPageUrl() }}" class="px-3 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg">&laquo;</a>
            @endif

            @foreach($posts->getUrlRange(1, $posts->lastPage()) as $page => $url)
                @if($page == $posts->currentPage())
                    <span class="px-3 py-2 text-sm bg-emerald-600 text-white rounded-lg">{{ $page }}</span>
                @elseif($page == 1 || $page == $posts->lastPage() || abs($page - $posts->currentPage()) <= 1)
                    <a href="{{ $url }}" class="px-3 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg">{{ $page }}</a>
                @elseif($page == 2 || $page == $posts->lastPage() - 1)
                    <span class="px-2 text-gray-400">...</span>
                @endif
            @endforeach

            @if($posts->hasMorePages())
                <a href="{{ $posts->nextPageUrl() }}" class="px-3 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg">&raquo;</a>
            @else
                <span class="px-3 py-2 text-sm text-gray-400 cursor-not-allowed">&raquo;</span>
            @endif
        </nav>
    </div>
    @endif

    @else
    <!-- Empty State -->
    <div class="text-center py-12 md:py-16 bg-white rounded-xl">
        <svg class="w-16 h-16 md:w-20 md:h-20 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
        </svg>
        <h3 class="text-lg md:text-xl font-semibold text-gray-700 mb-2">Belum Ada Berita</h3>
        <p class="text-gray-500 text-sm">Tidak ada berita yang sesuai dengan pencarian Anda.</p>
        @if(request('search') || request('category') || request('tag'))
        <a href="{{ route('posts.index') }}" class="inline-block mt-4 px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 text-sm">
            Lihat Semua Berita
        </a>
        @endif
    </div>
    @endif
</div>
@endsection
