@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-12">
    <!-- Header -->
    <div class="text-center mb-12">
        <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Daftar Berita</h1>
        <p class="text-gray-600 max-w-2xl mx-auto">Informasi terkini dari Kementerian Agama Kabupaten Nganjuk</p>
    </div>

    <!-- Search & Filter -->
    <div class="mb-8 flex flex-col md:flex-row gap-4 items-center justify-between">
        <form action="{{ route('posts.index') }}" method="GET" class="flex-1 max-w-md">
            <div class="relative">
                <input type="text" name="search" placeholder="Cari berita..."
                    value="{{ request('search') }}"
                    class="w-full px-4 py-2 pl-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
        </form>

        @if(isset($categories) && $categories->count() > 0)
        <div class="flex gap-2 flex-wrap">
            <a href="{{ route('posts.index') }}"
               class="px-4 py-2 rounded-lg {{ !request('category') ? 'bg-emerald-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Semua
            </a>
            @foreach($categories as $cat)
            <a href="{{ route('posts.index', ['category' => $cat->slug]) }}"
               class="px-4 py-2 rounded-lg {{ request('category') == $cat->slug ? 'bg-emerald-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                {{ $cat->name }}
            </a>
            @endforeach
        </div>
        @endif
    </div>

    <!-- Posts Grid -->
    @if(isset($posts) && $posts->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($posts as $post)
        <article class="bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-lg transition-shadow group">
            <!-- Thumbnail -->
            <a href="{{ route('posts.show', $post->slug) }}" class="block relative overflow-hidden">
                @if($post->thumbnail && file_exists(public_path('storage/' . $post->thumbnail)))
                <img src="{{ asset('storage/' . $post->thumbnail) }}" alt="{{ $post->title }}"
                     class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-500">
                @else
                <img src="{{ asset('images/placeholder-news.jpg') }}" alt="Placeholder"
                     class="w-full h-48 object-cover"
                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                <div class="w-full h-48 bg-gradient-to-br from-emerald-100 to-emerald-200 flex items-center justify-center hidden">
                    <svg class="w-12 h-12 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                <h2 class="font-semibold text-gray-800 mb-2 line-clamp-2 group-hover:text-emerald-600 transition-colors">
                    <a href="{{ route('posts.show', $post->slug) }}">{{ $post->title }}</a>
                </h2>
                <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ $post->subtitle ?? strip_tags($post->content) }}</p>
                <div class="flex items-center justify-between text-xs text-gray-500">
                    <span class="flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        {{ $post->published_at ? $post->published_at->format('d M Y') : '' }}
                    </span>
                    <span class="flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        {{ $post->views ?? 0 }} views
                    </span>
                </div>
            </div>
        </article>
        @endforeach
    </div>

    <!-- Pagination -->
    @if(method_exists($posts, 'links'))
    <div class="mt-8">
        {{ $posts->links() }}
    </div>
    @endif

    @else
    <!-- Empty State -->
    <div class="text-center py-16 bg-white rounded-xl">
        <svg class="w-20 h-20 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
        </svg>
        <h3 class="text-xl font-semibold text-gray-700 mb-2">Belum Ada Berita</h3>
        <p class="text-gray-500">Tidak ada berita yang sesuai dengan pencarian Anda.</p>
    </div>
    @endif
</div>
@endsection
