@extends('layouts.app')

@section('content')
<!-- Page Header -->
<section class="bg-gradient-to-br from-emerald-700 to-emerald-900 py-16 md:py-20">
    <div class="container mx-auto px-4">
        <div class="text-center">
            <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-white mb-4">Hubungi Kami</h1>
            <p class="text-emerald-100 text-lg max-w-2xl mx-auto">
                Silakan sampaikan pertanyaan, saran, atau keluhan Anda melalui formulir di bawah ini
            </p>
        </div>
    </div>
</section>

<!-- Contact Form Section -->
<section class="py-12 md:py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Contact Information -->
            <div class="lg:col-span-4">
                <div class="bg-white rounded-2xl shadow-lg p-6 md:p-8 h-full">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">Informasi Kontak</h2>

                    <div class="space-y-6">
                        <!-- Address -->
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-800 mb-1">Alamat</h3>
                                <p class="text-gray-600 text-sm">
                                    Jalan Dermojoyo 22, Payaman,<br>
                                    Kec. Nganjuk, Kabupaten Nganjuk,<br>
                                    Jawa Timur 64412
                                </p>
                            </div>
                        </div>

                        <!-- Phone -->
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-800 mb-1">Telepon</h3>
                                <p class="text-gray-600 text-sm">
                                    (0358) 321066
                                </p>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-800 mb-1">Email</h3>
                                <p class="text-gray-600 text-sm">
                                    {{ $settings->contact_email ?? 'kemenag.nganjuk@gmail.com' }}
                                </p>
                            </div>
                        </div>

                        <!-- Office Hours -->
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-800 mb-1">Jam Pelayanan</h3>
                                <p class="text-gray-600 text-sm">
                                    Senin - Jumat: 08.00 - 16.00 WIB<br>
                                    Sabtu - Minggu: Tutup
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Map -->
                    <div class="mt-8">
                        <h3 class="font-semibold text-gray-800 mb-3">Lokasi Kantor</h3>
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3954.6677388334288!2d111.8991191768781!3d-7.6110870752418815!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e784b0a4b1c6983%3A0xcac034becf05e4ff!2sKementerian%20Agama%20Kabupaten%20Nganjuk!5e0!3m2!1sid!2sid!4v1778387725628!5m2!1sid!2sid"
                            width="100%"
                            height="200"
                            style="border:0; border-radius: 12px;"
                            allowfullscreen=""
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"
                            title="Lokasi Kantor Kemenag Nganjuk"
                        ></iframe>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="lg:col-span-8">
                <div class="bg-white rounded-2xl shadow-lg p-6 md:p-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">Kirim Pesan</h2>
                    <p class="text-gray-600 mb-6">Isi formulir di bawah ini untuk menghubungi kami</p>

                    <!-- Success Message -->
                    <div id="success-message" class="hidden mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-xl">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-emerald-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-emerald-800">Pesan Berhasil Terkirim!</h4>
                                <p class="text-emerald-600 text-sm">Terima kasih telah menghubungi kami. Kami akan segera merespons pesan Anda.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Error Message -->
                    <div id="error-message" class="hidden mb-6 p-4 bg-red-50 border border-red-200 rounded-xl">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-red-800">Terjadi Kesalahan</h4>
                                <p id="error-text" class="text-red-600 text-sm"></p>
                            </div>
                        </div>
                    </div>

                    <form id="contact-form" action="{{ route('contact.submit') }}" method="POST" @submit.prevent="submitForm">
                        @csrf

                        <!-- Honeypot Field - Hidden from users, bots will fill it -->
                        <div class="hidden" aria-hidden="true">
                            <label for="website">Website</label>
                            <input type="text" name="website" id="website" tabindex="-1" autocomplete="off">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Name -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nama Lengkap <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    id="name"
                                    name="name"
                                    required
                                    minlength="2"
                                    maxlength="100"
                                    placeholder="Masukkan nama lengkap Anda"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors @error('name') border-red-500 @enderror"
                                    value="{{ old('name') }}"
                                >
                                @error('name')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                    Email <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="email"
                                    id="email"
                                    name="email"
                                    required
                                    maxlength="255"
                                    placeholder="contoh@email.com"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors @error('email') border-red-500 @enderror"
                                    value="{{ old('email') }}"
                                >
                                @error('email')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Phone -->
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                                    No. WhatsApp <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="tel"
                                    id="phone"
                                    name="phone"
                                    required
                                    minlength="10"
                                    maxlength="20"
                                    placeholder="08xxxxxxxxxx"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors @error('phone') border-red-500 @enderror"
                                    value="{{ old('phone') }}"
                                >
                                <p class="mt-1 text-xs text-gray-500">Contoh: 081234567890</p>
                                @error('phone')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Subject -->
                            <div>
                                <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">
                                    Subjek <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    id="subject"
                                    name="subject"
                                    required
                                    minlength="5"
                                    maxlength="200"
                                    placeholder="Judul pesan Anda"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors @error('subject') border-red-500 @enderror"
                                    value="{{ old('subject') }}"
                                >
                                @error('subject')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Message -->
                        <div class="mb-6">
                            <label for="message" class="block text-sm font-medium text-gray-700 mb-2">
                                Isi Pesan <span class="text-red-500">*</span>
                            </label>
                            <textarea
                                id="message"
                                name="message"
                                required
                                minlength="20"
                                maxlength="5000"
                                rows="6"
                                placeholder="Tulis pesan Anda di sini... (minimal 20 karakter)"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors resize-none @error('message') border-red-500 @enderror"
                            >{{ old('message') }}</textarea>
                            <div class="flex justify-between mt-1">
                                <p class="text-xs text-gray-500">Minimal 20 karakter</p>
                                <p id="char-count" class="text-xs text-gray-500">0/5000</p>
                            </div>
                            @error('message')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- reCAPTCHA Notice -->
                        <div class="mb-6 p-4 bg-amber-50 border border-amber-200 rounded-xl">
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-amber-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div class="text-sm">
                                    <p class="font-medium text-amber-800">Perlindungan Keamanan</p>
                                    <p class="text-amber-700 mt-1">
                                        Formulir ini dilindungi dengan CSRF token dan honeypot anti-spam untuk keamanan data Anda.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button
                            type="submit"
                            id="submit-btn"
                            class="w-full md:w-auto px-8 py-3 bg-emerald-600 hover:bg-emerald-700 disabled:bg-emerald-300 text-white font-semibold rounded-xl transition-colors flex items-center justify-center gap-2"
                        >
                            <span id="btn-text">Kirim Pesan</span>
                            <svg id="btn-spinner" class="hidden w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('contact-form');
    const submitBtn = document.getElementById('submit-btn');
    const btnText = document.getElementById('btn-text');
    const btnSpinner = document.getElementById('btn-spinner');
    const successMessage = document.getElementById('success-message');
    const errorMessage = document.getElementById('error-message');
    const errorText = document.getElementById('error-text');
    const charCount = document.getElementById('char-count');
    const messageTextarea = document.getElementById('message');

    // Character counter
    messageTextarea.addEventListener('input', function() {
        const count = this.value.length;
        charCount.textContent = count + '/5000';

        if (count < 20) {
            charCount.classList.add('text-red-500');
            charCount.classList.remove('text-gray-500');
        } else {
            charCount.classList.remove('text-red-500');
            charCount.classList.add('text-gray-500');
        }
    });

    // Initial count
    charCount.textContent = messageTextarea.value.length + '/5000';

    // Form submission
    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        // Reset messages
        successMessage.classList.add('hidden');
        errorMessage.classList.add('hidden');

        // Validate minimum message length
        if (messageTextarea.value.length < 20) {
            errorMessage.classList.remove('hidden');
            errorText.textContent = 'Pesan minimal harus 20 karakter.';
            messageTextarea.focus();
            return;
        }

        // Show loading state
        submitBtn.disabled = true;
        btnText.textContent = 'Mengirim...';
        btnSpinner.classList.remove('hidden');

        try {
            const formData = new FormData(form);

            const response = await fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                }
            });

            const data = await response.json();

            if (data.success) {
                // Show success message
                successMessage.classList.remove('hidden');
                form.reset();
                charCount.textContent = '0/5000';

                // Scroll to success message
                successMessage.scrollIntoView({ behavior: 'smooth', block: 'center' });

                // Hide success message after 10 seconds
                setTimeout(() => {
                    successMessage.classList.add('hidden');
                }, 10000);
            } else {
                // Show error message
                errorMessage.classList.remove('hidden');
                errorText.textContent = data.message || 'Terjadi kesalahan. Silakan coba lagi.';

                // Scroll to error message
                errorMessage.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        } catch (error) {
            console.error('Form submission error:', error);
            errorMessage.classList.remove('hidden');
            errorText.textContent = 'Terjadi kesalahan koneksi. Silakan coba lagi.';
        } finally {
            // Reset button state
            submitBtn.disabled = false;
            btnText.textContent = 'Kirim Pesan';
            btnSpinner.classList.add('hidden');
        }
    });
});
</script>
@endpush
