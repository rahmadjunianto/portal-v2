@extends('layouts.app')

@section('title', $meta_title ?? 'Regulasi - ' . ($settings->site_name ?? 'Kementerian Agama Kabupaten Nganjuk'))

@section('content')
<!-- Hero Section -->
<section class="bg-gradient-to-br from-emerald-700 to-emerald-900 text-white py-16 md:py-20">
    <div class="container mx-auto px-4">
        <nav class="flex mb-6" aria-label="Breadcrumb">
            <ol class="flex items-center gap-2 text-emerald-200 text-sm">
                <li><a href="{{ url('/') }}" class="hover:text-white transition-colors">Beranda</a></li>
                <li><span aria-hidden="true">/</span></li>
                <li class="text-white font-medium" aria-current="page">Regulasi</li>
            </ol>
        </nav>
        <h1 class="text-3xl md:text-4xl font-bold mb-4">Regulasi</h1>
        <p class="text-emerald-100 text-lg max-w-2xl">Kumpulan peraturan perundang-undangan yang berkaitan dengan Kementerian Agama</p>
    </div>
</section>

<!-- Content -->
<section class="py-12 md:py-16 bg-white">
    <div class="container mx-auto px-4">
        <!-- Search and Filter -->
        <div class="max-w-4xl mx-auto mb-8">
            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                <div class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1">
                        <label for="search" class="sr-only">Cari Regulasi</label>
                        <div class="relative">
                            <input 
                                type="text" 
                                id="search" 
                                placeholder="Cari regulasi..." 
                                class="w-full px-4 py-3 pl-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                onkeyup="filterRegulasi()"
                            >
                            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="md:w-48">
                        <label for="category-filter" class="sr-only">Filter Kategori</label>
                        <select 
                            id="category-filter" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-white"
                            onchange="filterRegulasi()"
                        >
                            <option value="">Semua Kategori</option>
                            <option value="Undang-Undang">Undang-Undang</option>
                            <option value="Peraturan Pemerintah">Peraturan Pemerintah</option>
                            <option value="Peraturan Presiden">Peraturan Presiden</option>
                            <option value="PMA">PMA</option>
                            <option value="KMA">KMA</option>
                            <option value="Surat Edaran">Surat Edaran</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <!-- Stats -->
            <div class="flex flex-wrap gap-4 mt-4 text-sm text-gray-600">
                <span class="bg-emerald-100 text-emerald-800 px-3 py-1 rounded-full">
                    Total: <strong id="total-count">{{ count($regulasi['Undang-Undang']) + count($regulasi['Peraturan Pemerintah']) + count($regulasi['Peraturan Presiden']) + count($regulasi['PMA']) + count($regulasi['KMA']) + count($regulasi['Surat Edaran']) }}</strong> dokumen
                </span>
            </div>
        </div>

        <!-- Regulation List by Category -->
        <div class="max-w-4xl mx-auto space-y-6" id="regulasi-list">
            @foreach($regulasi as $category => $items)
            <div class="regulasi-category" data-category="{{ $category }}">
                <!-- Category Header -->
                <div class="bg-emerald-700 text-white rounded-t-lg">
                    <button 
                        class="w-full px-6 py-4 flex items-center justify-between text-left hover:bg-emerald-600 transition-colors rounded-t-lg"
                        onclick="toggleCategory(this)"
                        aria-expanded="true"
                    >
                        <div class="flex items-center gap-3">
                            @if($category === 'Undang-Undang')
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            @elseif($category === 'Peraturan Pemerintah')
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            @elseif($category === 'Peraturan Presiden')
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/>
                                </svg>
                            @elseif($category === 'PMA')
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                                </svg>
                            @elseif($category === 'KMA')
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                                </svg>
                            @else
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            @endif
                            <span class="font-semibold text-lg">{{ $category }}</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="bg-emerald-600 text-white text-sm px-3 py-1 rounded-full">
                                {{ count($items) }} dokumen
                            </span>
                            <svg class="w-5 h-5 transform transition-transform category-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                    </button>
                </div>
                
                <!-- Category Content -->
                <div class="category-content bg-gray-50 rounded-b-lg border border-t-0 border-gray-200">
                    <ul class="divide-y divide-gray-200">
                        @foreach($items as $index => $item)
                        <li class="regulasi-item hover:bg-white transition-colors">
                            <a 
                                href="{{ $item['url'] }}" 
                                target="_blank" 
                                rel="noopener noreferrer"
                                class="block px-6 py-4 flex items-start gap-4"
                            >
                                <span class="flex-shrink-0 w-8 h-8 bg-emerald-100 text-emerald-700 rounded-full flex items-center justify-center text-sm font-medium">
                                    {{ $index + 1 }}
                                </span>
                                <div class="flex-1 min-w-0">
                                    <p class="text-gray-800 font-medium hover:text-emerald-600 transition-colors">
                                        {{ $item['title'] }}
                                    </p>
                                </div>
                                <svg class="flex-shrink-0 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                </svg>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endforeach
        </div>

        <!-- No Results Message -->
        <div id="no-results" class="hidden max-w-4xl mx-auto">
            <div class="bg-gray-50 rounded-lg p-8 text-center">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada hasil</h3>
                <p class="text-gray-500">Coba ubah kata kunci pencarian atau filter kategori</p>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
    function toggleCategory(button) {
        const content = button.nextElementSibling;
        const icon = button.querySelector('.category-icon');
        const isExpanded = button.getAttribute('aria-expanded') === 'true';
        
        if (isExpanded) {
            content.style.display = 'none';
            icon.classList.remove('rotate-180');
            button.setAttribute('aria-expanded', 'false');
        } else {
            content.style.display = 'block';
            icon.classList.add('rotate-180');
            button.setAttribute('aria-expanded', 'true');
        }
    }

    function filterRegulasi() {
        const searchQuery = document.getElementById('search').value.toLowerCase();
        const categoryFilter = document.getElementById('category-filter').value;
        const categories = document.querySelectorAll('.regulasi-category');
        let totalVisible = 0;
        
        categories.forEach(category => {
            const categoryName = category.getAttribute('data-category');
            const items = category.querySelectorAll('.regulasi-item');
            let categoryVisible = 0;
            
            items.forEach(item => {
                const title = item.querySelector('p').textContent.toLowerCase();
                const matchesSearch = title.includes(searchQuery);
                const matchesCategory = categoryFilter === '' || categoryName === categoryFilter;
                
                if (matchesSearch && matchesCategory) {
                    item.style.display = 'block';
                    categoryVisible++;
                    totalVisible++;
                } else {
                    item.style.display = 'none';
                }
            });
            
            // Show/hide entire category if no visible items
            const header = category.querySelector('.bg-emerald-700');
            const content = category.querySelector('.category-content');
            const countBadge = header.querySelector('.bg-emerald-600');
            
            if (categoryVisible > 0) {
                category.style.display = 'block';
                content.style.display = 'block';
                countBadge.textContent = categoryVisible + ' dokumen';
            } else {
                // Check if category matches filter
                if (categoryFilter === '' || categoryName === categoryFilter) {
                    category.style.display = 'block';
                    content.style.display = 'none';
                    countBadge.textContent = '0 dokumen';
                } else {
                    category.style.display = 'none';
                }
            }
        });
        
        // Update total count
        document.getElementById('total-count').textContent = totalVisible;
        
        // Show/hide no results message
        const noResults = document.getElementById('no-results');
        if (totalVisible === 0) {
            noResults.classList.remove('hidden');
        } else {
            noResults.classList.add('hidden');
        }
    }

    // Initialize - expand all categories
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.category-content').forEach(content => {
            content.style.display = 'block';
        });
        document.querySelectorAll('.category-icon').forEach(icon => {
            icon.classList.add('rotate-180');
        });
    });
</script>
@endpush
