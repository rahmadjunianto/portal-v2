@extends('layouts.app')

@section('title', $meta_title ?? 'Regulasi - ' . ($settings->site_name ?? 'Kementerian Agama Kabupaten Nganjuk'))

@section('content')
<!-- Hero Section -->
<section class="bg-gradient-to-br from-green-800 to-green-900 text-white py-12">
    <div class="container mx-auto px-4">
        <nav class="flex mb-4" aria-label="Breadcrumb">
            <ol class="flex items-center gap-2 text-green-200 text-sm">
                <li><a href="{{ url('/') }}" class="hover:text-white transition-colors">Beranda</a></li>
                <li><span aria-hidden="true">/</span></li>
                <li class="text-white font-medium" aria-current="page">Regulasi</li>
            </ol>
        </nav>
        <h1 class="text-4xl font-bold mb-2">Regulasi</h1>
        <p class="text-green-200">Kumpulan Peraturan Perundang-undangan Kementerian Agama</p>
    </div>
</section>

<!-- Content -->
<section class="py-8">
    <div class="container mx-auto px-4">
        <!-- Search and Filter -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <div class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <input type="text" id="searchInput" placeholder="Cari regulasi..." 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition-all">
                </div>
                <div class="flex gap-2 flex-wrap">
                    <button onclick="filterCategory('all')" class="filter-btn active px-4 py-2 rounded-lg border border-green-600 text-green-700 hover:bg-green-50 transition-colors" data-category="all">Semua</button>
                    <button onclick="filterCategory('Undang-Undang')" class="filter-btn px-4 py-2 rounded-lg border border-gray-300 text-gray-600 hover:bg-gray-50 transition-colors" data-category="Undang-Undang">UU</button>
                    <button onclick="filterCategory('Peraturan Pemerintah')" class="filter-btn px-4 py-2 rounded-lg border border-gray-300 text-gray-600 hover:bg-gray-50 transition-colors" data-category="Peraturan Pemerintah">PP</button>
                    <button onclick="filterCategory('Peraturan Presiden')" class="filter-btn px-4 py-2 rounded-lg border border-gray-300 text-gray-600 hover:bg-gray-50 transition-colors" data-category="Peraturan Presiden">Perpres</button>
                    <button onclick="filterCategory('PMA')" class="filter-btn px-4 py-2 rounded-lg border border-gray-300 text-gray-600 hover:bg-gray-50 transition-colors" data-category="PMA">PMA</button>
                    <button onclick="filterCategory('KMA')" class="filter-btn px-4 py-2 rounded-lg border border-gray-300 text-gray-600 hover:bg-gray-50 transition-colors" data-category="KMA">KMA</button>
                    <button onclick="filterCategory('Surat Edaran')" class="filter-btn px-4 py-2 rounded-lg border border-gray-300 text-gray-600 hover:bg-gray-50 transition-colors" data-category="Surat Edaran">SE</button>
                </div>
            </div>
        </div>

        <!-- Total Count -->
        <div class="mb-6">
            <p class="text-gray-600">Menampilkan <span id="totalCount" class="font-semibold text-green-700">0</span> regulasi</p>
        </div>

        <!-- Regulation Accordion -->
        <div class="space-y-4" id="regulasiAccordion">
            @php
            $categoryIcons = [
                'Undang-Undang' => '📜',
                'Peraturan Pemerintah' => '📋',
                'Peraturan Presiden' => '📌',
                'PMA' => '⚖️',
                'KMA' => '📝',
                'Surat Edaran' => '📨'
            ];
            $categoryColors = [
                'Undang-Undang' => 'from-blue-600 to-blue-700',
                'Peraturan Pemerintah' => 'from-green-600 to-green-700',
                'Peraturan Presiden' => 'from-purple-600 to-purple-700',
                'PMA' => 'from-orange-600 to-orange-700',
                'KMA' => 'from-teal-600 to-teal-700',
                'Surat Edaran' => 'from-gray-600 to-gray-700'
            ];
            @endphp

            @foreach($regulasi as $category => $items)
            <div class="regulasi-category" data-category="{{ $category }}">
                <button onclick="toggleAccordion(this)" 
                        class="w-full flex items-center justify-between bg-gradient-to-r {{ $categoryColors[$category] }} text-white p-4 rounded-lg shadow-md hover:shadow-lg transition-all">
                    <div class="flex items-center gap-3">
                        <span class="text-2xl">{{ $categoryIcons[$category] }}</span>
                        <div class="text-left">
                            <h3 class="font-bold text-lg">{{ $category }}</h3>
                            <p class="text-sm text-white/80">{{ count($items) }} dokumen</p>
                        </div>
                    </div>
                    <svg class="w-6 h-6 transform transition-transform accordion-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                
                <div class="accordion-content hidden mt-2 bg-white rounded-lg shadow-inner border border-gray-200 overflow-hidden">
                    <div class="divide-y divide-gray-100">
                        @foreach($items as $index => $item)
                        <div class="regulasi-item p-4 hover:bg-gray-50 transition-colors" data-title="{{ strtolower($item['title']) }}">
                            <div class="flex flex-col md:flex-row md:items-center gap-4">
                                <div class="flex-1">
                                    <div class="flex items-start gap-3">
                                        <span class="flex-shrink-0 w-8 h-8 bg-green-100 text-green-700 rounded-full flex items-center justify-center font-semibold text-sm mt-0.5">
                                            {{ $index + 1 }}
                                        </span>
                                        <div class="flex-1 min-w-0">
                                            <h4 class="font-semibold text-gray-800 mb-1">{{ $item['title'] }}</h4>
                                            <div class="flex flex-wrap items-center gap-2 text-sm">
                                                @if($item['nomor'] !== '-')
                                                <span class="inline-flex items-center gap-1 bg-blue-100 text-blue-800 px-2 py-1 rounded">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                                                    </svg>
                                                    No. {{ $item['nomor'] }}
                                                </span>
                                                @endif
                                                @if($item['tahun'] !== '-')
                                                <span class="inline-flex items-center gap-1 bg-purple-100 text-purple-800 px-2 py-1 rounded">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                    </svg>
                                                    {{ $item['tahun'] }}
                                                </span>
                                                @endif
                                                @php
                                                $extension = pathinfo($item['url'], PATHINFO_EXTENSION);
                                                $extUpper = strtoupper($extension);
                                                $fileIcon = match($extension) {
                                                    'pdf' => '📕',
                                                    'doc', 'docx' => '📘',
                                                    'xls', 'xlsx' => '📗',
                                                    'zip', 'rar' => '📦',
                                                    default => '📄'
                                                };
                                                $fileColor = match($extension) {
                                                    'pdf' => 'bg-red-100 text-red-700',
                                                    'doc', 'docx' => 'bg-blue-100 text-blue-700',
                                                    'xls', 'xlsx' => 'bg-green-100 text-green-700',
                                                    'zip', 'rar' => 'bg-yellow-100 text-yellow-700',
                                                    default => 'bg-gray-100 text-gray-700'
                                                };
                                                @endphp
                                                <span class="inline-flex items-center gap-1 {{ $fileColor }} px-2 py-1 rounded">
                                                    {{ $fileIcon }} {{ $extUpper }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <a href="{{ $item['url'] }}" target="_blank" 
                                   class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors font-medium">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                    </svg>
                                    Unduh
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Empty State -->
        <div id="emptyState" class="hidden text-center py-12">
            <div class="text-6xl mb-4">🔍</div>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">Tidak ditemukan</h3>
            <p class="text-gray-500">Coba kata kunci lain atau pilih kategori yang berbeda</p>
        </div>
    </div>
</section>

@push('scripts')
<script>
    // Accordion functionality
    function toggleAccordion(button) {
        const content = button.nextElementSibling;
        const icon = button.querySelector('.accordion-icon');
        
        content.classList.toggle('hidden');
        icon.classList.toggle('rotate-180');
    }

    // Category filter
    function filterCategory(category) {
        // Update button styles
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.classList.remove('active', 'bg-green-600', 'text-white', 'border-green-600');
            btn.classList.add('border-gray-300', 'text-gray-600');
        });
        
        const activeBtn = document.querySelector(`[data-category="${category}"]`);
        activeBtn.classList.remove('border-gray-300', 'text-gray-600');
        activeBtn.classList.add('active', 'bg-green-600', 'text-white', 'border-green-600');

        // Filter categories
        document.querySelectorAll('.regulasi-category').forEach(cat => {
            if (category === 'all' || cat.dataset.category === category) {
                cat.classList.remove('hidden');
            } else {
                cat.classList.add('hidden');
            }
        });

        updateTotalCount();
    }

    // Search functionality
    document.getElementById('searchInput').addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        
        document.querySelectorAll('.regulasi-item').forEach(item => {
            const title = item.dataset.title;
            if (title.includes(searchTerm)) {
                item.classList.remove('hidden');
            } else {
                item.classList.add('hidden');
            }
        });

        // Show/hide empty categories
        document.querySelectorAll('.regulasi-category').forEach(cat => {
            const visibleItems = cat.querySelectorAll('.regulasi-item:not(.hidden)');
            if (visibleItems.length === 0) {
                cat.classList.add('hidden');
            } else {
                cat.classList.remove('hidden');
            }
        });

        updateTotalCount();
    });

    // Update total count
    function updateTotalCount() {
        const visibleItems = document.querySelectorAll('.regulasi-item:not(.hidden)').length;
        document.getElementById('totalCount').textContent = visibleItems;
        
        const emptyState = document.getElementById('emptyState');
        if (visibleItems === 0) {
            emptyState.classList.remove('hidden');
        } else {
            emptyState.classList.add('hidden');
        }
    }

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        updateTotalCount();
        
        // Open first category by default
        const firstCategory = document.querySelector('.regulasi-category');
        if (firstCategory) {
            const firstButton = firstCategory.querySelector('button');
            const firstContent = firstCategory.querySelector('.accordion-content');
            const firstIcon = firstCategory.querySelector('.accordion-icon');
            firstContent.classList.remove('hidden');
            firstIcon.classList.add('rotate-180');
        }
    });
</script>
@endpush
@endsection
