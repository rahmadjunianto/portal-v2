{{-- resources/views/components/card-post.blade.php --}}
{{-- Reusable Post Card Component --}}

@props([
    'post',
    'showCategory' => true,
    'showExcerpt' => true,
    'size' => 'normal' // normal, large
])

@php
$imageSize = $size === 'large' ? 'h-64' : 'h-48';
$textSize = $size === 'large' ? 'text-xl' : 'text-base';
@endphp

<article class="bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-lg transition-shadow group">
    {{-- Thumbnail --}}
    <a href="{{ route('posts.show', $post->slug) }}" class="block relative overflow-hidden">
        @if($post->thumbnail_url)
        <img
            src="{{ $post->thumbnail_url }}"
            alt="{{ $post->title }}"
            class="w-full {{ $imageSize }} object-cover group-hover:scale-105 transition-transform duration-500"
            loading="lazy"
        >
        @else
        <div class="w-full {{ $imageSize }} bg-gradient-to-br from-emerald-100 to-emerald-200 flex items-center justify-center">
            <svg class="w-16 h-16 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
        </div>
        @endif

        @if($showCategory && $post->category)
        <span class="absolute top-3 left-3 px-3 py-1 bg-emerald-600 text-white text-xs font-medium rounded-full">
            {{ $post->category->name }}
        </span>
        @endif
    </a>

    {{-- Content --}}
    <div class="p-5">
        <h3 class="{{ $textSize }} font-semibold text-gray-800 mb-2 line-clamp-2 group-hover:text-emerald-600 transition-colors">
            <a href="{{ route('posts.show', $post->slug) }}">{{ $post->title }}</a>
        </h3>

        @if($showExcerpt)
        <p class="text-gray-600 text-sm mb-3 line-clamp-2">
            {{ $post->subtitle ?? strip_tags(Str::limit($post->content, 100)) }}
        </p>
        @endif

        <div class="flex items-center justify-between text-xs text-gray-500">
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
                {{ $post->published_at ? $post->published_at->diffForHumans() : '' }}
            </span>
        </div>
    </div>
</article>
