@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-12">
    @if(isset($post) && $post)
    <!-- Breadcrumb -->
    <nav class="mb-6">
        <ol class="flex items-center gap-2 text-sm">
            <li><a href="{{ route('home') }}" class="text-emerald-600 hover:underline">Beranda</a></li>
            <li class="text-gray-400">/</li>
            <li><a href="{{ route('posts.index') }}" class="text-emerald-600 hover:underline">Berita</a></li>
            <li class="text-gray-400">/</li>
            <li class="text-gray-500 truncate max-w-[200px]">{{ $post->title }}</li>
        </ol>
    </nav>

    <!-- Article Header -->
    <article class="bg-white rounded-xl shadow-sm overflow-hidden">
        @if($post->thumbnail)
        <!-- Featured Image -->
        <div class="relative">
            @if(file_exists(public_path('storage/' . $post->thumbnail)))
            <img src="{{ asset('storage/' . $post->thumbnail) }}" alt="{{ $post->title }}" class="w-full aspect-video md:aspect-[21/9] object-cover">
            @else
            <img src="{{ asset('images/placeholder-news.jpg') }}" alt="Placeholder" class="w-full aspect-video md:aspect-[21/9] object-cover"
                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
            <div class="w-full aspect-video md:aspect-[21/9] bg-gradient-to-br from-emerald-100 to-emerald-200 flex items-center justify-center hidden">
                <svg class="w-24 h-24 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            @endif
        </div>
        @endif

        <div class="p-6 md:p-10">
            <!-- Category & Date -->
            <div class="flex flex-wrap items-center gap-4 mb-4">
                @if($post->category)
                <span class="px-3 py-1 bg-emerald-100 text-emerald-700 text-sm font-medium rounded-full">
                    {{ $post->category->name }}
                </span>
                @endif
                <span class="flex items-center gap-2 text-gray-500 text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    {{ $post->published_at ? $post->published_at->format('d F Y') : '' }}
                </span>
                <span class="flex items-center gap-2 text-gray-500 text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    {{ $post->views ?? 0 }} views
                </span>
            </div>

            <!-- Title -->
            <h1 class="text-2xl md:text-4xl font-bold text-gray-800 mb-4">{{ $post->title }}</h1>

            <!-- Author -->
            @if($post->author)
            <div class="flex items-center gap-3 mb-6 pb-6 border-b">
                <div class="w-10 h-10 bg-emerald-100 rounded-full flex items-center justify-center">
                    <span class="text-emerald-700 font-semibold">{{ substr($post->author->username, 0, 1) }}</span>
                </div>
                <div>
                    <p class="font-medium text-gray-800">{{ $post->author->username }}</p>
                    <p class="text-sm text-gray-500">{{ $post->author->email }}</p>
                </div>
            </div>
            @endif

            <!-- Content -->
            <div class="prose prose-emerald max-w-none
                prose-headings:text-gray-900 prose-headings:font-bold prose-headings:tracking-tight
                prose-h2:text-2xl prose-h2:mt-10 prose-h2:mb-4 prose-h2:border-b prose-h2:border-gray-200 prose-h2:pb-3
                prose-h3:text-xl prose-h3:mt-8 prose-h3:mb-3
                prose-h4:text-lg prose-h4:mt-6 prose-h4:mb-2
                prose-p:text-gray-700 prose-p:leading-7 prose-p:mb-5
                prose-a:text-emerald-600 prose-a:font-medium prose-a:no-underline hover:prose-a:underline
                prose-img:rounded-xl prose-img:shadow-lg prose-img:mx-auto prose-img:my-6
                prose-ul:my-4 prose-ul:space-y-2
                prose-ol:my-4 prose-ol:space-y-2
                prose-li:text-gray-700 prose-li:leading-relaxed
                prose-blockquote:border-l-4 prose-blockquote:border-emerald-500 prose-blockquote:bg-emerald-50 prose-blockquote:py-4 prose-blockquote:px-6 prose-blockquote:rounded-r-xl prose-blockquote:not-italic prose-blockquote:text-gray-700 prose-blockquote:my-6
                prose-code:bg-gray-100 prose-code:px-2 prose-code:py-1 prose-code:rounded prose-code:text-sm prose-code:text-emerald-700 prose-code:font-mono
                prose-pre:bg-gray-900 prose-pre:text-gray-100 prose-pre:rounded-xl prose-pre:overflow-x-auto prose-pre:my-6
                prose-pre code:bg-transparent prose-pre code:text-inherit
                prose-strong:text-gray-900 prose-strong:font-semibold
                prose-hr:border-gray-200 prose-hr:my-10
                prose-table:text-sm prose-table:overflow-hidden prose-table:rounded-lg prose-table:shadow
                prose-th:bg-emerald-600 prose-th:text-white prose-th:px-4 prose-th:py-3 prose-th:font-semibold
                prose-td:px-4 prose-td:py-3 prose-td:border-b prose-td:border-gray-200
                prose-tr:hover:bg-gray-50">
                {!! $post->content !!}
            </div>

            <!-- Tags -->
            @if($post->tags && $post->tags->count() > 0)
            <div class="mt-8 pt-6 border-t">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="text-gray-600 text-sm">Tags:</span>
                    @foreach($post->tags as $tag)
                    <a href="{{ route('posts.index', ['tag' => $tag->slug]) }}"
                       class="px-3 py-1 bg-gray-100 text-gray-600 text-sm rounded-full hover:bg-emerald-100 hover:text-emerald-700 transition-colors">
                        {{ $tag->name }}
                    </a>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </article>

    <!-- Share Buttons -->
    <div class="mt-8 flex items-center gap-4">
        <span class="text-gray-600 font-medium">Bagikan:</span>
        <a href="https://wa.me/?text={{ urlencode($post->title . ' ' . url()->current()) }}"
           target="_blank" rel="noopener noreferrer"
           class="w-10 h-10 bg-green-500 text-white rounded-full flex items-center justify-center hover:bg-green-600 transition-colors">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
        </a>
        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"
           target="_blank" rel="noopener noreferrer"
           class="w-10 h-10 bg-blue-600 text-white rounded-full flex items-center justify-center hover:bg-blue-700 transition-colors">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
        </a>
        <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($post->title) }}"
           target="_blank" rel="noopener noreferrer"
           class="w-10 h-10 bg-sky-500 text-white rounded-full flex items-center justify-center hover:bg-sky-600 transition-colors">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
        </a>
    </div>

    @else
    <!-- Empty State -->
    <div class="text-center py-16 bg-white rounded-xl">
        <svg class="w-20 h-20 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
        </svg>
        <h3 class="text-xl font-semibold text-gray-700 mb-2">Berita Tidak Ditemukan</h3>
        <p class="text-gray-500 mb-6">Maaf, berita yang Anda cari tidak tersedia.</p>
        <a href="{{ route('posts.index') }}" class="inline-flex items-center gap-2 bg-emerald-600 text-white px-6 py-3 rounded-lg hover:bg-emerald-700 transition-colors">
            Kembali ke Daftar Berita
        </a>
    </div>
    @endif
</div>
@endsection
