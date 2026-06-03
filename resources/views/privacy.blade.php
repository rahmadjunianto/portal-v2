@extends('layouts.app')

@section('title', $meta_title ?? 'Kebijakan Privasi - ' . ($settings->site_name ?? 'Kementerian Agama Kabupaten Nganjuk'))

@section('content')
<!-- Hero Section -->
<section class="bg-gradient-to-br from-emerald-700 to-emerald-900 text-white py-16 md:py-20">
    <div class="container mx-auto px-4">
        <nav class="flex mb-6" aria-label="Breadcrumb">
            <ol class="flex items-center gap-2 text-emerald-200 text-sm">
                <li><a href="{{ url('/') }}" class="hover:text-white transition-colors">Beranda</a></li>
                <li><span aria-hidden="true">/</span></li>
                <li class="text-white font-medium" aria-current="page">Kebijakan Privasi</li>
            </ol>
        </nav>
        <h1 class="text-3xl md:text-4xl font-bold mb-4">Kebijakan Privasi</h1>
        <p class="text-emerald-100 text-lg max-w-2xl">Kebijakan pengelolaan data pribadi dan privasi pengguna website ini</p>
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

            <!-- Introduction -->
            <div class="prose prose-lg max-w-none">
                <p class="text-gray-600 leading-relaxed mb-6">
                    Portal Resmi {{ $settings->site_name ?? 'Kementerian Agama Kabupaten Nganjuk' }} (“Kami” atau “Portal”) menghargai privasi Anda dan berkomitmen untuk melindungi data pribadi Anda. Kebijakan Privasi ini menjelaskan bagaimana kami mengumpulkan, menggunakan, mengungkapkan, dan melindungi informasi Anda.
                </p>

                <!-- Legal Basis -->
                <h2 class="text-2xl font-bold text-gray-800 mt-8 mb-4 flex items-center gap-2">
                    <span class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </span>
                    Dasar Hukum
                </h2>
                <p class="text-gray-600 leading-relaxed mb-4">
                    Kebijakan Privasi ini disusun berdasarkan dan sesuai dengan:
                </p>
                <ul class="list-disc list-inside text-gray-600 space-y-2 mb-6">
                    <li>Undang-Undang Nomor 27 Tahun 2022 tentang Perlindungan Data Pribadi (UU PDP)</li>
                    <li>Peraturan Pemerintah Nomor 71 Tahun 2019 tentang Penyelenggaraan Sistem dan Transaksi Elektronik</li>
                    <li>Peraturan Menteri Agama Nomor 72 Tahun 2022 tentang Organisasi dan Tata Kerja Instansi Vertikal Kementerian Agama</li>
                    <li>Standar Aksesibilitas Situs Web dan Aplikasi Siedawai instansi pemerintah</li>
                </ul>

                <!-- Data Collection -->
                <h2 class="text-2xl font-bold text-gray-800 mt-8 mb-4 flex items-center gap-2">
                    <span class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"/>
                        </svg>
                    </span>
                    Data yang Dikumpulkan
                </h2>
                <p class="text-gray-600 leading-relaxed mb-4">
                    Kami dapat mengumpulkan data pribadi dari Anda dalam berbagai cara, termasuk namun tidak terbatas pada:
                </p>
                
                <div class="grid gap-4 mb-8">
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
                        <h3 class="font-semibold text-gray-800 mb-2">Data yang Anda Berikan</h3>
                        <ul class="list-disc list-inside text-gray-600 text-sm space-y-1">
                            <li>Nama lengkap</li>
                            <li>Alamat email</li>
                            <li>Nomor telepon</li>
                            <li>Alamat IP</li>
                            <li>Informasi kontak lainnya yang Anda berikan secara sukarela</li>
                        </ul>
                    </div>
                    
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
                        <h3 class="font-semibold text-gray-800 mb-2">Data yang Dikumpulkan Otomatis</h3>
                        <ul class="list-disc list-inside text-gray-600 text-sm space-y-1">
                            <li>Jenis browser dan versi</li>
                            <li>Sistem operasi</li>
                            <li>Halaman yang dikunjungi</li>
                            <li>Waktu dan tanggal kunjungan</li>
                            <li>Cookie dan teknologi pelacakan serupa</li>
                        </ul>
                    </div>
                </div>

                <!-- Cookie Usage -->
                <h2 class="text-2xl font-bold text-gray-800 mt-8 mb-4 flex items-center gap-2">
                    <span class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </span>
                    Penggunaan Cookie
                </h2>
                <p class="text-gray-600 leading-relaxed mb-4">
                    Kami menggunakan cookie dan teknologi serupa untuk:
                </p>
                <ul class="list-disc list-inside text-gray-600 space-y-2 mb-6">
                    <li><strong>Cookie Esensial:</strong> Diperlukan agar website dapat berfungsi dengan baik</li>
                    <li><strong>Cookie Analitik:</strong> Membantu kami memahami bagaimana pengunjung menggunakan website</li>
                    <li><strong>Cookie Preferensi:</strong> Menyimpan pengaturan dan preferensi Anda</li>
                    <li><strong>Cookie Keamanan:</strong> Mendeteksi aktivitas mencurigakan dan ancaman keamanan</li>
                </ul>

                <!-- Data Usage -->
                <h2 class="text-2xl font-bold text-gray-800 mt-8 mb-4 flex items-center gap-2">
                    <span class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </span>
                    Tujuan Penggunaan Data
                </h2>
                <p class="text-gray-600 leading-relaxed mb-4">
                    Data pribadi yang kami kumpulkan digunakan untuk:
                </p>
                <ul class="list-disc list-inside text-gray-600 space-y-2 mb-6">
                    <li>Menyediakan layanan informasi publik</li>
                    <li>Meningkatkan pengalaman pengguna</li>
                    <li>Menyediakan konten yang relevan</li>
                    <li>Menganalisis traffic dan penggunaan website</li>
                    <li>Menanggapi pertanyaan dan permintaan pengguna</li>
                    <li>Mematuhi kewajiban hukum dan regulasi</li>
                </ul>

                <!-- Data Protection -->
                <h2 class="text-2xl font-bold text-gray-800 mt-8 mb-4 flex items-center gap-2">
                    <span class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </span>
                    Perlindungan Data
                </h2>
                <p class="text-gray-600 leading-relaxed mb-4">
                    Kami menerapkan langkah-langkah keamanan yang tepat untuk melindungi data pribadi Anda, termasuk:
                </p>
                <ul class="list-disc list-inside text-gray-600 space-y-2 mb-6">
                    <li>Enkripsi data menggunakan protokol HTTPS (TLS 1.2/1.3)</li>
                    <li>Penyimpanan data yang aman dengan akses terbatas</li>
                    <li>Regular security audits dan vulnerability assessments</li>
                    <li>Penerapan prinsip least privilege untuk akses data</li>
                    <li>Pelatihan kesadaran keamanan untuk petugas</li>
                </ul>

                <!-- User Rights -->
                <h2 class="text-2xl font-bold text-gray-800 mt-8 mb-4 flex items-center gap-2">
                    <span class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </span>
                    Hak Anda
                </h2>
                <p class="text-gray-600 leading-relaxed mb-4">
                    Sesuai dengan UU PDP, Anda memiliki hak untuk:
                </p>
                <ul class="list-disc list-inside text-gray-600 space-y-2 mb-6">
                    <li><strong>Hak akses:</strong> Mendapatkan salinan data pribadi Anda</li>
                    <li><strong>Hak perbaikan:</strong> Memperbaiki data yang tidak akurat</li>
                    <li><strong>Hak penghapusan:</strong> Meminta penghapusan data Anda</li>
                    <li><strong>Hak pembatasan:</strong> Membatasi pemrosesan data Anda</li>
                    <li><strong>Hak portabilitas:</strong> Memindahkan data ke pengendali lain</li>
                    <li><strong>Hak objeksi:</strong> Menolak pemrosesan data tertentu</li>
                </ul>

                <!-- Data Retention -->
                <h2 class="text-2xl font-bold text-gray-800 mt-8 mb-4 flex items-center gap-2">
                    <span class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </span>
                    Retensi Data
                </h2>
                <p class="text-gray-600 leading-relaxed mb-6">
                    Kami akan menyimpan data pribadi Anda hanya selama diperlukan untuk memenuhi tujuan pengumpulan atau sesuai dengan persyaratan hukum. Data yang tidak lagi diperlukan akan dihapus atau dianonimkan secara aman.
                </p>

                <!-- Third Party -->
                <h2 class="text-2xl font-bold text-gray-800 mt-8 mb-4 flex items-center gap-2">
                    <span class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </span>
                    Pihak Ketiga
                </h2>
                <p class="text-gray-600 leading-relaxed mb-4">
                    Kami dapat membagikan data Anda kepada:
                </p>
                <ul class="list-disc list-inside text-gray-600 space-y-2 mb-6">
                    <li>Instansi pemerintah terkait untuk kewajiban hukum</li>
                    <li>Penyedia layanan pihak ketiga yang membantu operasional website</li>
                    <li>Pihak berwenang jika diperlukan oleh hukum</li>
                </ul>

                <!-- Contact -->
                <h2 class="text-2xl font-bold text-gray-800 mt-8 mb-4 flex items-center gap-2">
                    <span class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </span>
                    Kontak Kami
                </h2>
                <p class="text-gray-600 leading-relaxed mb-4">
                    Jika Anda memiliki pertanyaan tentang Kebijakan Privasi ini atau ingin menggunakan hak Anda terkait data pribadi, silakan hubungi kami:
                </p>
                
                <div class="bg-gray-50 rounded-lg p-6 border border-gray-200 mb-6">
                    <div class="space-y-3">
                        <div class="flex items-start gap-3">
                            <span class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </span>
                            <div>
                                <p class="font-medium text-gray-800">Kementerian Agama Kabupaten Nganjuk</p>
                                <p class="text-gray-600 text-sm">Jl. Dermojoyo 22, Payaman, Kec. Nganjuk</p>
                                <p class="text-gray-600 text-sm">Kabupaten Nganjuk, Jawa Timur 64412</p>
                            </div>
                        </div>
                        
                        @if($settings->phone)
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                            </span>
                            <a href="tel:{{ $settings->phone }}" class="text-emerald-600 hover:text-emerald-700 font-medium">{{ $settings->phone }}</a>
                        </div>
                        @endif
                        
                        @if($settings->email)
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </span>
                            <a href="mailto:{{ $settings->email }}" class="text-emerald-600 hover:text-emerald-700 font-medium">{{ $settings->email }}</a>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Policy Updates -->
                <h2 class="text-2xl font-bold text-gray-800 mt-8 mb-4 flex items-center gap-2">
                    <span class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                    </span>
                    Perubahan Kebijakan
                </h2>
                <p class="text-gray-600 leading-relaxed mb-6">
                    Kami dapat memperbarui Kebijakan Privasi ini dari waktu ke waktu. Setiap perubahan akan diumumkan melalui website ini. Kami mendorong Anda untuk secara berkala meninjau kebijakan ini.
                </p>

                <!-- Footer Note -->
                <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 mt-8">
                    <p class="text-sm text-amber-800">
                        <strong>Catatan:</strong> Kebijakan Privasi ini merupakan bagian dari komitmen kami dalam perlindungan data pribadi sesuai dengan Undang-Undang Nomor 27 Tahun 2022 tentang Perlindungan Data Pribadi.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
