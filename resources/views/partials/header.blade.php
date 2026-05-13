<header class="bg-white shadow-md sticky top-0 z-40">
    <!-- Top Bar -->
    <div class="bg-emerald-700 text-white">
        <div class="container mx-auto px-4 py-1.5 sm:py-2">
            <div class="flex justify-between items-center text-xs sm:text-sm">
                <!-- Contact Info - Mobile: Icons only -->
                <div class="flex items-center gap-1 sm:gap-3">
                    @if($settings->email)
                    <a href="mailto:{{ $settings->email }}" class="p-1.5 sm:p-0 hover:bg-emerald-600 sm:hover:bg-transparent rounded sm:rounded-none transition-colors flex items-center gap-1" title="{{ $settings->email }}">
                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <span class="hidden sm:inline truncate max-w-[150px] lg:max-w-none">{{ $settings->email }}</span>
                    </a>
                    @endif
                    @if($settings->phone)
                    <a href="tel:{{ $settings->phone }}" class="p-1.5 sm:p-0 hover:bg-emerald-600 sm:hover:bg-transparent rounded sm:rounded-none transition-colors flex items-center gap-1" title="{{ $settings->phone }}">
                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        <span class="hidden sm:inline">{{ $settings->phone }}</span>
                    </a>
                    @endif
                </div>

                <!-- Social & Date -->
                <div class="flex items-center gap-2 sm:gap-3">
                    <!-- Social Icons -->
                    <div class="flex items-center gap-1">
                        <!-- Facebook -->
                        @if($settings->facebook_url)
                        <a href="{{ $settings->facebook_url }}" target="_blank" rel="noopener noreferrer" class="p-1 sm:p-1.5 hover:bg-emerald-600 rounded transition-colors" aria-label="Facebook">
                            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        </a>
                        @endif
                        <!-- Instagram -->
                        @if($settings->instagram_url)
                        <a href="{{ $settings->instagram_url }}" target="_blank" rel="noopener noreferrer" class="p-1 sm:p-1.5 hover:bg-emerald-600 rounded transition-colors" aria-label="Instagram">
                            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                            </svg>
                        </a>
                        @endif
                        <!-- YouTube -->
                        @if($settings->youtube_url)
                        <a href="{{ $settings->youtube_url }}" target="_blank" rel="noopener noreferrer" class="p-1 sm:p-1.5 hover:bg-emerald-600 rounded transition-colors" aria-label="YouTube">
                            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                            </svg>
                        </a>
                        @endif
                        <!-- Twitter/X -->
                        @if($settings->twitter_url)
                        <a href="{{ $settings->twitter_url }}" target="_blank" rel="noopener noreferrer" class="p-1 sm:p-1.5 hover:bg-emerald-600 rounded transition-colors" aria-label="Twitter">
                            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                            </svg>
                        </a>
                        @endif
                        <!-- WhatsApp -->
                        <a href="https://wa.me/6282132339933" target="_blank" rel="noopener noreferrer" class="p-1 sm:p-1.5 hover:bg-emerald-600 rounded transition-colors" aria-label="WhatsApp">
                            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                            </svg>
                        </a>
                    </div>

                    <!-- Date - hidden on mobile -->
                    <span class="hidden md:inline text-xs border-l border-emerald-500 pl-2 sm:pl-3">
                        {{\Carbon\Carbon::now()->locale('id')->translatedFormat('d M Y')}}
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
                    <h1 class="text-sm sm:text-base md:text-lg font-bold text-emerald-800 leading-tight truncate sm:whitespace-normal">{{ $settings->site_name ?? 'Kementerian Agama' }}</h1>
                    <p class="text-xs text-gray-800 leading-tight">Kabupaten Nganjuk</p>
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
                                               @if($child->open_in_new_tab) target="_blank" rel="noopener noreferrer" @endif
                                               class="block text-gray-700 hover:text-emerald-700">
                                                {{ $child->title }}
                                            </a>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        @else
                            <a href="{{ $menuItem->url ?? '#' }}"
                               @if($menuItem->open_in_new_tab) target="_blank" rel="noopener noreferrer" @endif
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
