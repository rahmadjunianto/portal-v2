@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-12">
    <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-6 sm:mb-8">Download</h1>

    @if($downloads->count() > 0)
    <!-- Mobile: Card View | Desktop: Table View -->
    <div class="hidden sm:block bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama File</th>
                        <th class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hits</th>
                        <th class="px-4 lg:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($downloads as $index => $download)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 lg:px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 }}</td>
                        <td class="px-4 lg:px-6 py-4 text-sm text-gray-900">{{ $download->title }}</td>
                        <td class="px-4 lg:px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $download->downloads_count }} Kali</td>
                        <td class="px-4 lg:px-6 py-4 whitespace-nowrap text-center">
                            <a href="{{ asset('storage/' . $download->file_path) }}"
                               target="_blank"
                               class="inline-flex items-center px-3 py-1 bg-emerald-600 text-white text-xs font-medium rounded hover:bg-emerald-700 transition-colors">
                                Download
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Mobile Card View -->
    <div class="sm:hidden space-y-3">
        @foreach($downloads as $index => $download)
        <div class="bg-white rounded-lg shadow-sm p-4">
            <div class="flex items-start justify-between gap-3">
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900">{{ $download->title }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $download->downloads_count }} Kali diunduh</p>
                </div>
                <a href="{{ asset('storage/' . $download->file_path) }}"
                   target="_blank"
                   class="flex-shrink-0 inline-flex items-center px-3 py-2 bg-emerald-600 text-white text-xs font-medium rounded-lg hover:bg-emerald-700 transition-colors">
                    Download
                </a>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="mt-6 flex justify-center">
        <nav class="flex items-center gap-1">
            @if($downloads->onFirstPage())
                <span class="px-3 py-2 text-sm text-gray-400 cursor-not-allowed">&laquo;</span>
            @else
                <a href="{{ $downloads->previousPageUrl() }}" class="px-3 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg">&laquo;</a>
            @endif

            @foreach($downloads->getUrlRange(1, $downloads->lastPage()) as $page => $url)
                @if($page == $downloads->currentPage())
                    <span class="px-3 py-2 text-sm bg-emerald-600 text-white rounded-lg">{{ $page }}</span>
                @elseif($page == 1 || $page == $downloads->lastPage() || abs($page - $downloads->currentPage()) <= 1)
                    <a href="{{ $url }}" class="px-3 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg">{{ $page }}</a>
                @elseif($page == 2 || $page == $downloads->lastPage() - 1)
                    <span class="px-2 text-gray-400">...</span>
                @endif
            @endforeach

            @if($downloads->hasMorePages())
                <a href="{{ $downloads->nextPageUrl() }}" class="px-3 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg">&raquo;</a>
            @else
                <span class="px-3 py-2 text-sm text-gray-400 cursor-not-allowed">&raquo;</span>
            @endif
        </nav>
    </div>
    @else
    <div class="text-center py-12 bg-white rounded-xl">
        <p class="text-gray-500">Belum ada file download.</p>
    </div>
    @endif
</div>
@endsection
