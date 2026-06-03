@extends('layouts.app')

@section('title', 'Pernyataan Aksesibilitas - ' . ($settings->site_name ?? 'Kementerian Agama Kabupaten Nganjuk'))

@section('content')
<!-- Hero Section -->
<section class="bg-gradient-to-br from-emerald-700 to-emerald-900 text-white py-16 md:py-20">
    <div class="container mx-auto px-4">
        <nav class="flex mb-6" aria-label="Breadcrumb">
            <ol class="flex items-center gap-2 text-emerald-200 text-sm">
                <li><a href="{{ url('/') }}" class="hover:text-white transition-colors">Beranda</a></li>
                <li><span aria-hidden="true">/</span></li>
                <li class="text-white font-medium" aria-current="page">Aksesibilitas</li>
            </ol>
        </nav>
        <h1 class="text-3xl md:text-4xl font-bold mb-4">Pernyataan Aksesibilitas</h1>
        <p class="text-emerald-100 text-lg max-w-2xl">Komitmen kami untuk menyediakan website yang dapat diakses oleh semua orang</p>
    </div>
</section>

<!-- Content -->
<section class="py-12 md:py-16 bg-white">
    <div class="container mx-auto px-4">
        <div class="max-w-3xl mx-auto">
            
            <!-- Last Updated -->
            <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-4 mb-8">
                <p class="text-sm text-emerald-800">
                    <strong>Terakhir diperbarui:</strong> {{ now()->translatedFormat('d F Y') }}
                </p>
            </div>

            <!-- Statement -->
            <div class="prose prose-lg max-w-none">
                <p class="text-gray-600 leading-relaxed mb-6">
                    Portal Resmi {{ $settings->site_name ?? 'Kementerian Agama Kabupaten Nganjuk' }} berkomitmen untuk memastikan aksesibilitas digital bagi penyandang disabilitas. Kami terus meningkatkan kemudahan penggunaan dan aksesibilitas bagi semua pengguna, termasuk mereka yang menggunakan teknologi asisten.
                </p>

                <!-- WCAG Compliance -->
                <h2 class="text-2xl font-bold text-gray-800 mt-8 mb-4 flex items-center gap-2">
                    <span class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </span>
                    Kepatuhan terhadap Standar
                </h2>
                <p class="text-gray-600 leading-relaxed mb-4">
                    Kami strive untuk mematuhi <strong>Pedoman Aksesibilitas Konten Web (WCAG) 2.1 Level AA</strong>. Pedoman ini menjelaskan bagaimana membuat konten web lebih mudah diakses oleh penyandang disabilitas dan lebih ramah pengguna.
                </p>

                <!-- Features -->
                <h2 class="text-2xl font-bold text-gray-800 mt-8 mb-4 flex items-center gap-2">
                    <span class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                        </svg>
                    </span>
                    Fitur Aksesibilitas Kami
                </h2>
                
                <div class="grid gap-4 mb-8">
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
                        <h3 class="font-semibold text-gray-800 mb-2 flex items-center gap-2">
                            <span class="w-6 h-6 bg-emerald-600 text-white rounded-full flex items-center justify-center text-xs font-bold">1</span>
                            Skip Links
                        </h3>
                        <p class="text-gray-600 text-sm">Tautan "Langsung ke konten utama" tersedia di awal halaman untuk membantu navigasi keyboard.</p>
                    </div>
                    
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
                        <h3 class="font-semibold text-gray-800 mb-2 flex items-center gap-2">
                            <span class="w-6 h-6 bg-emerald-600 text-white rounded-full flex items-center justify-center text-xs font-bold">2</span>
                            Navigasi Keyboard
                        </h3>
                        <p class="text-gray-600 text-sm">Semua fungsionalitas dapat diakses menggunakan keyboard. Gunakan Tab untuk bergerak maju dan Shift+Tab untuk bergerak mundur.</p>
                    </div>
                    
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
                        <h3 class="font-semibold text-gray-800 mb-2 flex items-center gap-2">
                            <span class="w-6 h-6 bg-emerald-600 text-white rounded-full flex items-center justify-center text-xs font-bold">3</span>
                            Indikator Fokus yang Jelas
                        </h3>
                        <p class="text-gray-600 text-sm">Elemen interaktif memiliki outline fokus yang terlihat jelas untuk pengguna keyboard.</p>
                    </div>
                    
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
                        <h3 class="font-semibold text-gray-800 mb-2 flex items-center gap-2">
                            <span class="w-6 h-6 bg-emerald-600 text-white rounded-full flex items-center justify-center text-xs font-bold">4</span>
                            Teks Alternatif untuk Gambar
                        </h3>
                        <p class="text-gray-600 text-sm">Semua gambar memiliki teks alternatif yang menjelaskan isi dan fungsi gambar tersebut.</p>
                    </div>
                    
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
                        <h3 class="font-semibold text-gray-800 mb-2 flex items-center gap-2">
                            <span class="w-6 h-6 bg-emerald-600 text-white rounded-full flex items-center justify-center text-xs font-bold">5</span>
                            Struktur HTML Semantik
                        </h3>
                        <p class="text-gray-600 text-sm">Kami menggunakan markup HTML yang tepat (heading, list, landmarks) untuk membantu pembaca layar.</p>
                    </div>
                    
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
                        <h3 class="font-semibold text-gray-800 mb-2 flex items-center gap-2">
                            <span class="w-6 h-6 bg-emerald-600 text-white rounded-full flex items-center justify-center text-xs font-bold">6</span>
                            Dukungan untuk Preferensi Reduksi Gerakan
                        </h3>
                        <p class="text-gray-600 text-sm">Website ini menghormati preferensi "Reduce Motion" untuk pengguna yang sensitif terhadap animasi.</p>
                    </div>
                </div>

                <!-- Compatibility -->
                <h2 class="text-2xl font-bold text-gray-800 mt-8 mb-4 flex items-center gap-2">
                    <span class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                    </span>
                    Kompatibilitas dengan Browser dan Assistive Technology
                </h2>
                <p class="text-gray-600 leading-relaxed mb-4">
                    Website ini dirancang untuk kompatibel dengan browser modern dan pembaca layar seperti:
                </p>
                <ul class="list-disc list-inside text-gray-600 space-y-2 mb-6">
                    <li>NVDA (Windows)</li>
                    <li>JAWS (Windows)</li>
                    <li>VoiceOver (macOS/iOS)</li>
                    <li>ChromeVox (Chrome)</li>
                    <li>Microsoft Edge</li>
                    <li>Google Chrome</li>
                    <li>Mozilla Firefox</li>
                    <li>Safari</li>
                </ul>

                <!-- Technical Info -->
                <h2 class="text-2xl font-bold text-gray-800 mt-8 mb-4 flex items-center gap-2">
                    <span class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </span>
                    Informasi Teknis
                </h2>
                <p class="text-gray-600 leading-relaxed mb-4">
                    Aksesibilitas website ini bergantung pada teknologi berikut:
                </p>
                <ul class="list-disc list-inside text-gray-600 space-y-2 mb-6">
                    <li>HTML semantik</li>
                    <li>CSS (Cascading Style Sheets)</li>
                    <li>JavaScript (untuk interaktivitas)</li>
                    <li>ARIA (Accessible Rich Internet Applications)</li>
                </ul>

                <!-- Feedback -->
                <h2 class="text-2xl font-bold text-gray-800 mt-8 mb-4 flex items-center gap-2">
                    <span class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                        </svg>
                    </span>
                    Umpan Balik dan Informasi Kontak
                </h2>
                <p class="text-gray-600 leading-relaxed mb-4">
                    Kami sangat menghargai umpan balik Anda. Jika Anda mengalami hambatan dalam mengakses informasi di website ini, silakan hubungi kami:
                </p>
                
                <div class="bg-gray-50 rounded-lg p-6 border border-gray-200 mb-6">
                    <div class="space-y-3">
                        @if($settings->phone)
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                            </span>
                            <a href="tel:{{ $settings->phone }}" class="text-emerald-600 hover:text-emerald-700 font-medium">{{ $settings->phone }}</a>
                        </div>
                        @endif
                        
                        @if($settings->email)
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </span>
                            <a href="mailto:{{ $settings->email }}" class="text-emerald-600 hover:text-emerald-700 font-medium">{{ $settings->email }}</a>
                        </div>
                        @endif
                        
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                </svg>
                            </span>
                            <a href="https://wa.me/6282132339933" target="_blank" rel="noopener noreferrer" class="text-emerald-600 hover:text-emerald-700 font-medium">WhatsApp</a>
                        </div>
                    </div>
                </div>

                <p class="text-gray-600 leading-relaxed mb-6">
                    Kami akan berusaha semaksimal mungkin untuk menyediakan informasi dalam format yang dapat Anda akses.
                </p>

                <!-- Assessment -->
                <h2 class="text-2xl font-bold text-gray-800 mt-8 mb-4 flex items-center gap-2">
                    <span class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                    </span>
                    Penilaian dan Evaluasi
                </h2>
                <p class="text-gray-600 leading-relaxed mb-6">
                    Aksesibilitas website ini dievaluasi secara berkala menggunakan kombinasi metode otomatis dan manual. Kami menggunakan alat bantu aksesibilitas untuk mengidentifikasi potensi masalah dan melakukan perbaikan berkelanjutan.
                </p>

                <!-- Known Issues -->
                <h2 class="text-2xl font-bold text-gray-800 mt-8 mb-4 flex items-center gap-2">
                    <span class="w-8 h-8 bg-amber-100 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </span>
                    Keterbatasan yang Diketahui
                </h2>
                <p class="text-gray-600 leading-relaxed mb-4">
                    Meskipun kami berusaha untuk mencapai kepatuhan penuh, mungkin ada beberapa keterbatasan:
                </p>
                <ul class="list-disc list-inside text-gray-600 space-y-2 mb-6">
                    <li>Konten pihak ketiga (embed video, widget media sosial) mungkin tidak sepenuhnya dapat diakses</li>
                    <li>Beberapa dokumen PDF lama mungkin belum memiliki tag aksesibilitas</li>
                    <li>Gambar dekoratif mungkin tidak memiliki teks alternatif yang deskriptif</li>
                </ul>
                <p class="text-gray-600 leading-relaxed mb-6">
                    Kami terus bekerja untuk memperbaiki keterbatasan ini.
                </p>

                <!-- Enforcement -->
                <h2 class="text-2xl font-bold text-gray-800 mt-8 mb-4 flex items-center gap-2">
                    <span class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/>
                        </svg>
                    </span>
                    Prosedur Penegakan
                </h2>
                <p class="text-gray-600 leading-relaxed mb-6">
                    Jika Anda tidak puas dengan respons kami, Anda dapat menyampaikan keluhan melalui mekanisme pengaduan yang tersedia. Kami berkomitmen untuk merespons keluhan dalam waktu yang wajar.
                </p>

                <!-- Footer Note -->
                <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-4 mt-8">
                    <p class="text-sm text-emerald-800">
                        <strong>Catatan:</strong> Pernyataan aksesibilitas ini akan diperbarui secara berkala sesuai dengan pengembangan dan peningkatan website.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
