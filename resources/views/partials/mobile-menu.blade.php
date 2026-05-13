<!-- Mobile Menu Overlay -->
<div id="mobile-menu" class="fixed inset-0 bg-black/50 z-50 opacity-0 invisible transition-opacity duration-300 lg:hidden">
    <div class="absolute right-0 top-0 h-full w-80 max-w-full bg-white shadow-2xl transform translate-x-full transition-transform duration-300" id="mobile-menu-panel">
        <!-- Mobile Menu Header -->
        <div class="flex items-center justify-between p-4 border-b bg-emerald-700 text-white">
            <div class="flex items-center gap-2">
                <img src="{{ asset('logo-kemenag.png') }}" alt="Logo Kementerian Agama Kabupaten Nganjuk" class="h-8 w-auto object-contain">
                <div class="flex flex-col">
                    <span class="font-semibold text-sm leading-tight">Menu</span>
                    <span class="text-xs text-emerald-200 leading-tight hidden sm:block">Kementerian Agama Kabupaten Nganjuk</span>
                </div>
            </div>
            <button id="mobile-menu-close" class="p-2 hover:bg-emerald-600 rounded-lg transition-colors" aria-label="Tutup menu">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Mobile Menu Content -->
        <nav class="overflow-y-auto h-[calc(100%-64px)]">
            <ul class="py-2">
                <!-- Home Link -->
                {{-- <li>
                    <a href="{{ url('/') }}" class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-emerald-50 hover:text-emerald-700 transition-colors {{ request()->is('/') ? 'bg-emerald-50 text-emerald-700 border-r-4 border-emerald-600' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        <span class="font-medium">Beranda</span>
                    </a>
                </li> --}}

                @forelse($headerMenuItems as $menuItem)
                <li x-data="{ open: false }">
                    <button
                        @click="open = !open"
                        class="w-full flex items-center justify-between gap-3 px-4 py-3 text-gray-700 hover:bg-emerald-50 hover:text-emerald-700 transition-colors"
                    >
                        <span class="flex items-center gap-3 font-medium">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                            {{ $menuItem->title }}
                        </span>
                        @if($menuItem->children->count() > 0)
                        <svg class="w-5 h-5 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                        @endif
                    </button>

                    @if($menuItem->children->count() > 0)
                    <ul x-show="open" x-collapse class="bg-gray-50 border-l-4 border-emerald-200">
                        @foreach($menuItem->children as $child)
                        <li x-data="{ open2: false }">
                            @if($child->children->count() > 0)
                            <button
                                @click="open2 = !open2"
                                class="w-full flex items-center justify-between gap-3 px-4 py-3 pl-12 text-gray-600 hover:bg-emerald-100 hover:text-emerald-700 transition-colors"
                            >
                                <span class="flex items-center gap-2 font-medium">
                                    {{ $child->title }}
                                </span>
                                <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': open2 }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <ul x-show="open2" x-collapse class="bg-white border-l-4 border-emerald-300">
                                @foreach($child->children as $grandChild)
                                <li>
                                    <a
                                        href="{{ $grandChild->url ?? '#' }}"
                                        @if($grandChild->open_in_new_tab) target="_blank" rel="noopener noreferrer" @endif
                                        class="flex items-center gap-3 px-4 py-3 pl-16 text-gray-500 hover:bg-emerald-50 hover:text-emerald-700 transition-colors"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                        {{ $grandChild->title }}
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                            @else
                            <a
                                href="{{ $child->url ?? '#' }}"
                                @if($child->open_in_new_tab) target="_blank" rel="noopener noreferrer" @endif
                                class="flex items-center gap-3 px-4 py-3 pl-12 text-gray-600 hover:bg-emerald-50 hover:text-emerald-700 transition-colors"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                                {{ $child->title }}
                            </a>
                            @endif
                        </li>
                        @endforeach
                    </ul>
                    @endif
                </li>
                @empty
                <!-- Default menu items -->
                <li>
                    <a href="#" class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-emerald-50 hover:text-emerald-700 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                        <span class="font-medium">Profil</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-emerald-50 hover:text-emerald-700 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                        <span class="font-medium">Berita</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-emerald-50 hover:text-emerald-700 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                        <span class="font-medium">Layanan</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-emerald-50 hover:text-emerald-700 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                        <span class="font-medium">Unduh</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-emerald-50 hover:text-emerald-700 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                        <span class="font-medium">Kontak</span>
                    </a>
                </li>
                @endforelse
            </ul>

            <!-- Mobile Menu Footer -->
            <div class="border-t mt-4 pt-4 px-4 pb-4">
                <div class="space-y-2 text-sm text-gray-600">
                    @if($settings->email)
                    <a href="mailto:{{ $settings->email }}" class="flex items-center gap-2 hover:text-emerald-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        {{ $settings->email }}
                    </a>
                    @endif
                    @if($settings->phone)
                    <a href="tel:{{ $settings->phone }}" class="flex items-center gap-2 hover:text-emerald-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        {{ $settings->phone }}
                    </a>
                    @endif
                </div>
            </div>
        </nav>
    </div>
</div>

@push('scripts')
<script>
    const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
    const mobileMenuClose = document.getElementById('mobile-menu-close');
    const mobileMenu = document.getElementById('mobile-menu');
    const mobileMenuPanel = document.getElementById('mobile-menu-panel');

    function openMobileMenu() {
        mobileMenu.classList.remove('opacity-0', 'invisible');
        mobileMenu.classList.add('opacity-100', 'visible');
        mobileMenuPanel.classList.remove('translate-x-full');
        document.body.style.overflow = 'hidden';
    }

    function closeMobileMenu() {
        mobileMenu.classList.add('opacity-0', 'invisible');
        mobileMenu.classList.remove('opacity-100', 'visible');
        mobileMenuPanel.classList.add('translate-x-full');
        document.body.style.overflow = '';
    }

    mobileMenuToggle.addEventListener('click', openMobileMenu);
    mobileMenuClose.addEventListener('click', closeMobileMenu);

    // Close when clicking overlay
    mobileMenu.addEventListener('click', (e) => {
        if (e.target === mobileMenu) {
            closeMobileMenu();
        }
    });

    // Close on escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeMobileMenu();
        }
    });
</script>
@endpush
