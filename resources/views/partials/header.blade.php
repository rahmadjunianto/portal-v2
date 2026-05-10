<header class="bg-white shadow-md sticky top-0 z-40">
    <!-- Top Bar -->
    <div class="bg-emerald-700 text-white">
        <div class="container mx-auto px-4 py-2">
            <div class="flex flex-col sm:flex-row justify-between items-center text-sm gap-2">
                <div class="flex items-center gap-4">
                    @if($settings->email)
                    <a href="mailto:{{ $settings->email }}" class="hover:text-emerald-200 transition-colors flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <span class="hidden sm:inline">{{ $settings->email }}</span>
                    </a>
                    @endif
                    @if($settings->phone)
                    <a href="tel:{{ $settings->phone }}" class="hover:text-emerald-200 transition-colors flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        <span class="hidden sm:inline">{{ $settings->phone }}</span>
                    </a>
                    @endif
                </div>
                <div class="flex items-center gap-3">
                    @if($settings->facebook_url)
                    <a href="{{ $settings->facebook_url }}" target="_blank" rel="noopener noreferrer" class="hover:text-emerald-200 transition-colors" aria-label="Facebook">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                    </a>
                    @endif
                    <span class="hidden md:inline border-l border-emerald-500 pl-3">
                        {{\Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y')}}
                    </span>
                </div>
            </div>
        </div>
    </div>

            <!-- Logo & Navigation -->
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between py-4">
            <!-- Logo -->
            <a href="{{ url('/') }}" class="flex items-center gap-3 group flex-shrink-0">
                <img
                    src="{{ asset('logo-kemenag.png') }}"
                    alt="Logo Kementerian Agama Kabupaten Nganjuk"
                    class="h-10 sm:h-12 md:h-14 w-auto object-contain transition-transform group-hover:scale-105"
                    style="max-width: 70px;"
                >
                <div class="min-w-0 flex-1 sm:flex-none">
                    <h1 class="text-sm sm:text-base md:text-lg font-bold text-emerald-800 leading-tight truncate sm:whitespace-normal">Kementerian Agama</h1>
                    <p class="text-xs text-gray-800 leading-tight hidden sm:block">Kabupaten Nganjuk</p>
                </div>
            </a>

            <!-- Desktop Navigation -->
            <nav class="hidden lg:block">
                <ul class="flex items-center gap-1">
                    {{-- Menu dari database --}}
                    @forelse($headerMenuItems as $menuItem)
                    <li class="relative group/menu">
                        @php $menuChildren = $menuItem->children_collection; @endphp
                        @if($menuChildren->count() > 0)
                            <div class="px-4 py-2 rounded-lg text-gray-700 hover:bg-emerald-50 hover:text-emerald-700 font-medium transition-colors flex items-center gap-1 cursor-pointer">
                                {{ $menuItem->title }}
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                            {{-- Dropdown Level 1 - hover dengan group-hover --}}
                            <ul class="absolute left-0 top-full pt-2 min-w-[220px] z-50 hidden group-hover/menu:flex flex-col bg-white rounded-lg shadow-xl border border-gray-100 py-2">
                                @foreach($menuChildren as $child)
                                    @php $grandChildren = $child->children_collection ?? collect(); @endphp
                                    @if($grandChildren->count() > 0)
                                        <li class="relative px-4 py-2 hover:bg-emerald-50 group/sub">
                                            <div class="flex items-center justify-between text-gray-700 hover:text-emerald-700">
                                                <a href="{{ $child->url ?? '#' }}" class="flex-1">{{ $child->title }}</a>
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                </svg>
                                            </div>
                                            {{-- Dropdown Level 2 - muncul saat hover parent --}}
                                            <ul class="absolute left-full top-0 ml-0 min-w-[200px] hidden group-hover/sub:block flex-col bg-white rounded-lg shadow-xl border border-gray-100 py-2">
                                                @foreach($grandChildren as $grandChild)
                                                <li class="px-4 py-2 hover:bg-emerald-50">
                                                    <a href="{{ $grandChild->url ?? '#' }}"
                                                       class="block text-gray-700 hover:text-emerald-700">
                                                        {{ $grandChild->title }}
                                                    </a>
                                                </li>
                                                @endforeach
                                            </ul>
                                        </li>
                                    @else
                                        <li class="px-4 py-2 hover:bg-emerald-50">
                                            <a href="{{ $child->url ?? '#' }}"
                                               class="block text-gray-700 hover:text-emerald-700">
                                                {{ $child->title }}
                                            </a>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        @else
                            <a href="{{ $menuItem->url ?? '#' }}"
                               class="px-4 py-2 rounded-lg text-gray-700 hover:bg-emerald-50 hover:text-emerald-700 font-medium transition-colors block">
                                {{ $menuItem->title }}
                            </a>
                        @endif
                    </li>
                    @empty
                    <li><a href="#" class="px-4 py-2 text-gray-700">No menu</a></li>
                    @endforelse
                </ul>
            </nav>

            <!-- Mobile Menu Toggle -->
            <button
                id="mobile-menu-toggle"
                class="lg:hidden p-2 rounded-lg hover:bg-gray-100 transition-colors"
                aria-label="Toggle menu"
            >
                <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>
    </div>
</header>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle submenu hover
    document.querySelectorAll('.submenu-trigger').forEach(function(trigger) {
        trigger.addEventListener('mouseenter', function() {
            const panel = this.querySelector('.submenu-panel');
            if (panel) panel.classList.remove('hidden');
        });
        trigger.addEventListener('mouseleave', function() {
            const panel = this.querySelector('.submenu-panel');
            if (panel) panel.classList.add('hidden');
        });
    });
});
</script>
@endpush
