<footer class="bg-emerald-900 text-gray-300">
    <!-- Main Footer -->
    <div class="container mx-auto px-4 py-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- About Section -->
            <div class="lg:col-span-2">
                <div class="flex items-center gap-3 mb-4">
                    <img
                        src="{{ asset('logo-kemenag.png') }}"
                        alt="Logo Kementerian Agama Kabupaten Nganjuk"
                        class="h-12 w-auto object-contain"
                    >
                    <div>
                        <h3 class="text-white font-bold text-lg">Kementerian Agama </h3>
                        <p class="text-sm text-emerald-400">Kabupaten Nganjuk</p>
                    </div>
                </div>
                <p class="text-gray-400 mb-4 leading-relaxed">
                    {{ $settings->footer_description ?? 'Portal resmi Kantor Kementerian Agama Kabupaten Nganjuk. Menyajikan informasi seputar kegiatan, layanan, dan berita keagamaan di Kabupaten Nganjuk.' }}
                </p>
                @if($settings->facebook_url)
                <a href="{{ $settings->facebook_url }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 text-emerald-400 hover:text-emerald-300 transition-colors">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                    </svg>
                    Ikuti di Facebook
                </a>
                @endif
            </div>

            <!-- Quick Links -->
            <div>
                <h4 class="text-white font-semibold mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                    </svg>
                    Tautan Cepat
                </h4>
                <ul class="space-y-2">
                    @forelse($footerMenuItems as $menuItem)
                    <li>
                        <a
                            href="{{ $menuItem->url ?? '#' }}"
                            @if($menuItem->target_blank) target="_blank" rel="noopener noreferrer" @endif
                            class="hover:text-emerald-400 transition-colors flex items-center gap-1"
                        >
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                            </svg>
                            {{ $menuItem->title }}
                        </a>
                    </li>
                    @empty
                    <li><a href="{{ url('/') }}" class="hover:text-emerald-400 transition-colors">Beranda</a></li>
                    <li><a href="#" class="hover:text-emerald-400 transition-colors">Profil</a></li>
                    <li><a href="#" class="hover:text-emerald-400 transition-colors">Berita</a></li>
                    <li><a href="#" class="hover:text-emerald-400 transition-colors">Agenda</a></li>
                    <li><a href="#" class="hover:text-emerald-400 transition-colors">Unduh</a></li>
                    <li><a href="#" class="hover:text-emerald-400 transition-colors">Kontak</a></li>
                    @endforelse
                </ul>
            </div>

            <!-- Contact Info -->
            <div>
                <h4 class="text-white font-semibold mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    Hubungi Kami
                </h4>
                <ul class="space-y-3">
                    @if($settings->email)
                    <li class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-emerald-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <a href="mailto:{{ $settings->email }}" class="hover:text-emerald-400 transition-colors">{{ $settings->email }}</a>
                    </li>
                    @endif
                    @if($settings->phone)
                    <li class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-emerald-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        <a href="tel:{{ $settings->phone }}" class="hover:text-emerald-400 transition-colors">{{ $settings->phone }}</a>
                    </li>
                    @endif
                    @if($settings->site_url)
                    <li class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-emerald-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                        </svg>
                        <span>{{ $settings->site_url }}</span>
                    </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>

    <!-- Bottom Bar -->
    <div class="border-t border-emerald-800">
        <div class="container mx-auto px-4 py-4">
            <div class="flex flex-col md:flex-row justify-between items-center gap-2 text-sm text-gray-400">
                <p>&copy; {{ date('Y') }} {{ $settings->site_name }}. Hak Cipta Dilindungi.</p>
                <p class="flex items-center gap-1">
                    <span>Dibuat dengan</span>
                    <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                    </svg>
                    <span>di Nganjuk</span>
                </p>
            </div>
        </div>
    </div>
</footer>
