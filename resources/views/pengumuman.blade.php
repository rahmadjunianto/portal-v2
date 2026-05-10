@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="text-center mb-6">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-2">Pengumuman</h1>
        <p class="text-gray-600 text-sm">Pengumuman resmi Kementerian Agama Kabupaten Nganjuk</p>
    </div>

    <!-- Announcements List -->
    @if(isset($posts) && $posts->count() > 0)
    <div class="space-y-4">
        @foreach($posts as $post)
        <article class="bg-white rounded-lg shadow-sm p-4 md:p-6 hover:shadow-md transition-shadow border-l-4 border-red-500">
            <div class="flex items-start gap-4">
                <!-- Icon -->
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                        </svg>
                    </div>
                </div>

                <!-- Content -->
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="inline-block px-2 py-0.5 text-xs font-medium text-red-600 bg-red-100 rounded">
                            PENGUMUMAN
                        </span>
                        @if($post->is_headline)
                        <span class="inline-block px-2 py-0.5 text-xs font-medium text-amber-600 bg-amber-100 rounded">
                            PENTING
                        </span>
                        @endif
                    </div>

                    <h2 class="font-semibold text-gray-800 text-base md:text-lg mb-2 line-clamp-2 hover:text-emerald-600">
                        <a href="{{ route('posts.show', $post->slug) }}">{{ $post->title }}</a>
                    </h2>

                    <p class="text-gray-600 text-sm mb-3 line-clamp-2">
                        {{ $post->excerpt ? Str::limit(strip_tags($post->excerpt), 150) : Str::limit(strip_tags($post->content), 150) }}
                    </p>

                    <!-- Meta Info -->
                    <div class="flex flex-wrap items-center gap-3 text-xs text-gray-500">
                        <div class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span>{{ $post->published_at->format('d M Y') }}</span>
                        </div>
                        <div class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <span>{{ $post->author->usernamee ?? 'Administrator' }}</span>
                        </div>
                        @if($post->category)
                        <span class="px-2 py-0.5 bg-gray-100 rounded">{{ $post->category->name }}</span>
                        @endif
                    </div>
                </div>

                <!-- Action -->
                <div class="flex-shrink-0">
                    <a href="{{ route('posts.show', $post->slug) }}" class="inline-flex items-center justify-center w-10 h-10 bg-emerald-600 text-white rounded-full hover:bg-emerald-700 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </div>
        </article>
        @endforeach
    </div>

    <!-- Pagination -->
    @if(method_exists($posts, 'links'))
    <div class="mt-6">
        {{ $posts->links() }}
    </div>
    @endif

    @else
    <!-- Empty State -->
    <div class="text-center py-12 bg-white rounded-xl">
        <svg class="w-16 h-16 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
        </svg>
        <h3 class="text-lg font-semibold text-gray-700 mb-1">Belum Ada Pengumuman</h3>
        <p class="text-gray-500 text-sm">Tidak ada pengumuman yang tersedia.</p>
    </div>
    @endif
</div>
@endsection
