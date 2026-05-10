@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-12">
    <!-- Breadcrumb -->
    <nav class="mb-6">
        <ol class="flex items-center gap-2 text-sm">
            <li><a href="{{ route('home') }}" class="text-emerald-600 hover:text-emerald-700">Beranda</a></li>
            <li class="text-gray-400">/</li>
            <li class="text-gray-600">{{ $page->title }}</li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">{{ $page->title }}</h1>
        @if($page->excerpt)
        <p class="text-lg text-gray-600">{{ $page->excerpt }}</p>
        @endif
    </div>

    <!-- Page Content -->
    <article class="prose prose-lg max-w-none">
        {!! $page->content !!}
    </article>

    <!-- Meta Info -->
    @if($page->published_at)
    <div class="mt-8 pt-6 border-t border-gray-200 text-sm text-gray-500">
        <p>Terakhir diperbarui: {{ $page->published_at->format('d M Y') }}</p>
    </div>
    @endif
</div>
@endsection

@push('styles')
<style>
    .prose h2 {
        @apply text-2xl font-bold text-gray-800 mt-8 mb-4;
    }
    .prose h3 {
        @apply text-xl font-semibold text-gray-800 mt-6 mb-3;
    }
    .prose p {
        @apply text-gray-600 mb-4 leading-relaxed;
    }
    .prose ul, .prose ol {
        @apply mb-4 pl-6;
    }
    .prose li {
        @apply text-gray-600 mb-2;
    }
    .prose a {
        @apply text-emerald-600 hover:text-emerald-700 underline;
    }
    .prose img {
        @apply rounded-lg shadow-md my-6;
    }
    .prose blockquote {
        @apply border-l-4 border-emerald-500 pl-4 italic text-gray-600 my-6;
    }
</style>
@endpush
