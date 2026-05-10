<!-- Chat Widget Container -->
<div x-data="chatWidget()" x-init="init()"
     class="fixed z-50 {{ config('chatbot.ui.position', 'bottom-right') === 'bottom-right' ? 'right-4 md:right-6' : 'left-4 md:left-6' }} bottom-4 md:bottom-6"
     @keydown.escape.window="isOpen = false">

    <!-- Chat Button (Always visible) -->
    <button
            @click="openChat()"
            class="group relative w-16 h-16 md:w-18 md:h-18 bg-gradient-to-br from-emerald-500 to-emerald-700 hover:from-emerald-600 hover:to-emerald-800 rounded-full shadow-2xl hover:shadow-emerald-500/40 transition-all duration-300 flex items-center justify-center ring-4 ring-emerald-300/30 hover:ring-emerald-400/50 hover:scale-110"
            :class="{'animate-bounce': hasUnread}">
        <!-- Unread Badge -->
        <span x-show="hasUnread"
              x-transition:enter="transition ease-out duration-300"
              x-transition:enter-start="opacity-0 scale-0"
              x-transition:enter-end="opacity-100 scale-100"
              class="absolute -top-1 -right-1 w-6 h-6 bg-gradient-to-br from-red-500 to-red-600 text-white text-xs rounded-full flex items-center justify-center font-bold shadow-lg"
              x-text="unreadCount"></span>

        <!-- Chat Icon -->
        <svg x-show="!isOpen" class="w-8 h-8 text-white drop-shadow-lg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
        </svg>
    </button>

    <!-- Chat Window -->
    <div x-show="isOpen"
         x-transition
         @click.outside="closeOnOutside && closeChat()"
         class="bg-white rounded-2xl shadow-2xl w-[calc(100vw-2rem)] md:w-96 max-h-[calc(100vh-8rem)] md:max-h-[600px] flex flex-col overflow-hidden"
         :class="{{ config('chatbot.ui.position', 'bottom-right') === 'bottom-right' ? 'origin-bottom-right' : 'origin-bottom-left' }}">

        <!-- Header -->
        <div class="bg-emerald-700 text-white px-4 py-3 md:px-6 md:py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-emerald-600 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-sm md:text-base">AI Assistant</h3>
                    <p class="text-xs text-emerald-200 flex items-center gap-1">
                        <span class="w-2 h-2 bg-emerald-300 rounded-full animate-pulse"></span>
                        Online
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <button @click="clearChat()"
                        class="p-2 hover:bg-emerald-600 rounded-full transition-colors"
                        title="Hapus riwayat chat">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </button>
                <button @click="closeChat()"
                        class="p-2 hover:bg-emerald-600 rounded-full transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Chat Messages -->
        <div class="flex-1 overflow-y-auto p-4 space-y-4 bg-gray-50"
             x-ref="messagesContainer"
             id="chat-messages">

            <!-- Welcome Message -->
            <template x-if="messages.length === 0">
                <div class="text-center py-4">
                    <div class="w-16 h-16 mx-auto mb-3 bg-emerald-100 rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h4 class="font-semibold text-gray-800 mb-2">Selamat datang!</h4>
                    <p class="text-sm text-gray-600 mb-4">Saya asisten virtual Kemenag Nganjuk. Silakan tanyakan tentang layanan kami.</p>
                    <!-- Suggestions only show after user info submitted -->
                    <template x-if="userInfoSubmitted">
                        <div class="flex flex-wrap gap-2 justify-center">
                            <template x-for="suggestion in suggestions" :key="suggestion">
                                <button @click="sendSuggestion(suggestion)"
                                        class="px-3 py-1.5 text-xs bg-white border border-emerald-200 text-emerald-700 rounded-full hover:bg-emerald-50 transition-colors">
                                    <span x-text="suggestion"></span>
                                </button>
                            </template>
                        </div>
                    </template>
                </div>
            </template>

            <!-- Messages -->
            <template x-for="(msg, index) in messages" :key="index">
                <div class="flex animate-fadeIn"
                     :class="msg.role === 'user' ? 'justify-end' : 'justify-start'">

                    <!-- AI Avatar -->
                    <div x-show="msg.role !== 'user'"
                         class="w-8 h-8 mr-2 flex-shrink-0 bg-emerald-600 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>

                    <!-- Message Bubble -->
                    <div class="max-w-[80%] md:max-w-[75%] rounded-2xl px-4 py-2.5 text-sm"
                         :class="msg.role === 'user'
                             ? 'bg-emerald-600 text-white rounded-br-md'
                             : 'bg-white text-gray-800 rounded-bl-md shadow-sm border border-gray-100'">

                        <!-- Text Content -->
                        <div x-html="formatMessage(msg.content)"></div>

                        <!-- Timestamp -->
                        <p class="text-[10px] mt-1 opacity-60"
                           :class="msg.role === 'user' ? 'text-emerald-200' : 'text-gray-400'"
                           x-text="msg.time"></p>
                    </div>
                </div>
            </template>

            <!-- Typing Indicator -->
            <div x-show="isTyping" class="flex justify-start animate-fadeIn">
                <div class="w-8 h-8 mr-2 flex-shrink-0 bg-emerald-600 rounded-full flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div class="bg-white rounded-2xl rounded-bl-md shadow-sm border border-gray-100 px-4 py-3">
                    <div class="flex gap-1">
                        <div class="w-2 h-2 bg-emerald-400 rounded-full animate-bounce" style="animation-delay: 0ms"></div>
                        <div class="w-2 h-2 bg-emerald-400 rounded-full animate-bounce" style="animation-delay: 150ms"></div>
                        <div class="w-2 h-2 bg-emerald-400 rounded-full animate-bounce" style="animation-delay: 300ms"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Input Area -->
        <div class="p-3 md:p-4 bg-white border-t border-gray-100">
            <!-- User Info Form (Required - must submit before chat) -->
            <div x-show="!userInfoSubmitted" class="mb-3 p-3 bg-amber-50 border border-amber-200 rounded-lg">
                <p class="text-xs text-amber-700 mb-2 font-medium">⚠️ Wajib diisi sebelum chat:</p>
                <div class="grid grid-cols-1 gap-2">
                    <div>
                        <input type="text" x-model="userInfo.name" placeholder="Nama lengkap *"
                               class="px-3 py-2 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 w-full"
                               :class="nameError ? 'border-red-500 bg-red-50' : 'border-gray-300'">
                        <p x-show="nameError" x-text="nameError" class="text-xs text-red-500 mt-1"></p>
                    </div>
                    <div>
                        <input type="email" x-model="userInfo.email" placeholder="Email *"
                               class="px-3 py-2 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 w-full"
                               :class="emailError ? 'border-red-500 bg-red-50' : 'border-gray-300'">
                        <p x-show="emailError" x-text="emailError" class="text-xs text-red-500 mt-1"></p>
                    </div>
                    <div>
                        <input type="tel" x-model="userInfo.phone" placeholder="No. WhatsApp * (08xxxxxxxxxx)"
                               class="px-3 py-2 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 w-full"
                               :class="phoneError ? 'border-red-500 bg-red-50' : 'border-gray-300'">
                        <p x-show="phoneError" x-text="phoneError" class="text-xs text-red-500 mt-1"></p>
                    </div>
                </div>
                <button type="button" @click="submitUserInfo()"
                        class="mt-3 w-full py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg transition-colors">
                    Lanjutkan Chat
                </button>
            </div>

            <!-- Error Message -->
            <div x-show="error"
                 x-transition
                 class="mb-2 px-3 py-2 bg-red-50 border border-red-200 rounded-lg text-xs text-red-600">
                <span x-text="error"></span>
            </div>

            <!-- Chat Input (only show after user info submitted) -->
            <template x-if="userInfoSubmitted">
                <form @submit.prevent="sendMessage()" class="flex gap-2">
                    <input type="text"
                           x-model="newMessage"
                           @input="error = ''"
                           :placeholder="placeholder"
                           :disabled="isTyping"
                           maxlength="500"
                           class="flex-1 px-4 py-2.5 text-sm border border-gray-200 rounded-full focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent disabled:bg-gray-100 disabled:cursor-not-allowed transition-all">
                    <button type="submit"
                            :disabled="!newMessage.trim() || isTyping"
                            class="w-10 h-10 bg-emerald-600 hover:bg-emerald-700 disabled:bg-gray-300 disabled:cursor-not-allowed rounded-full flex items-center justify-center transition-colors flex-shrink-0">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                    </button>
                </form>
            </template>

            <p class="text-[10px] text-gray-400 mt-2 text-center" x-show="userInfoSubmitted">
                AI Assistant • Jawaban AI mungkin tidak selalu 100% akurat
            </p>
        </div>
    </div>
</div>

<style>
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}
.animate-fadeIn {
    animation: fadeIn 0.3s ease-out;
}
</style>

<script>
function chatWidget() {
    return {
        isOpen: false,
        isTyping: false,
        hasUnread: false,
        unreadCount: 0,
        newMessage: '',
        messages: [],
        error: '',
        placeholder: 'Ketik pertanyaan Anda...',
        suggestions: ['Jam pelayanan?', 'Layanan yg Tersedia', 'Alamat kantor'],
        closeOnOutside: false,
        userInfo: { name: '', email: '', phone: '' },
        userInfoSubmitted: false,
        userInfoExpiry: 4 * 60 * 60 * 1000, // 4 hours in milliseconds
        nameError: '',
        emailError: '',
        phoneError: '',

        init() {
            // Load user info from localStorage if exists and not expired
            const savedUserInfo = localStorage.getItem('chatbot_user_info');
            const userSubmitted = localStorage.getItem('chatbot_user_submitted');
            const savedTime = localStorage.getItem('chatbot_user_time');

            if (savedUserInfo && userSubmitted === 'true' && savedTime) {
                const elapsed = Date.now() - parseInt(savedTime);
                if (elapsed < this.userInfoExpiry) {
                    this.userInfo = JSON.parse(savedUserInfo);
                    this.userInfoSubmitted = true;
                } else {
                    // Expired - clear storage
                    localStorage.removeItem('chatbot_user_info');
                    localStorage.removeItem('chatbot_user_submitted');
                    localStorage.removeItem('chatbot_user_time');
                }
            }

            // Load messages from localStorage
            const saved = localStorage.getItem('chatbot_messages');
            if (saved) {
                this.messages = JSON.parse(saved);
            }

            // Get placeholder from config
            this.placeholder = '{{ config("chatbot.ui.placeholder", "Ketik pertanyaan Anda...") }}';
        },

        openChat() {
            this.isOpen = true;
            this.hasUnread = false;
            this.unreadCount = 0;
            this.$nextTick(() => {
                this.scrollToBottom();
            });
        },

        closeChat() {
            this.isOpen = false;
        },

        clearChat() {
            this.messages = [];
            localStorage.removeItem('chatbot_messages');
        },

        validateName() {
            this.nameError = '';
            if (!this.userInfo.name.trim()) {
                this.nameError = 'Nama lengkap wajib diisi';
            } else if (this.userInfo.name.trim().length < 2) {
                this.nameError = 'Nama minimal 2 karakter';
            } else if (!/^[a-zA-Z\s\'-]+$/.test(this.userInfo.name.trim())) {
                this.nameError = 'Nama hanya boleh huruf, spasi, petik, dan strip';
            }
            return !this.nameError;
        },

        validateEmail() {
            this.emailError = '';
            if (!this.userInfo.email.trim()) {
                this.emailError = 'Email wajib diisi';
            } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.userInfo.email.trim())) {
                this.emailError = 'Format email tidak valid';
            }
            return !this.emailError;
        },

        validatePhone() {
            this.phoneError = '';
            if (!this.userInfo.phone.trim()) {
                this.phoneError = 'No. WhatsApp wajib diisi';
            } else {
                // Remove all non-digit characters for validation
                const cleanPhone = this.userInfo.phone.replace(/\D/g, '');
                if (cleanPhone.length < 10 || cleanPhone.length > 15) {
                    this.phoneError = 'No. WhatsApp harus 10-15 digit';
                } else if (!/^0\d+$/.test(cleanPhone)) {
                    this.phoneError = 'No. WhatsApp harus dimulai dengan 0';
                }
            }
            return !this.phoneError;
        },

        submitUserInfo() {
            // Validate all fields
            const isNameValid = this.validateName();
            const isEmailValid = this.validateEmail();
            const isPhoneValid = this.validatePhone();

            if (isNameValid && isEmailValid && isPhoneValid) {
                // Set submitted state to true - this will hide the form
                this.userInfoSubmitted = true;
                // Clear error states
                this.nameError = '';
                this.emailError = '';
                this.phoneError = '';
                // Save to localStorage with timestamp
                localStorage.setItem('chatbot_user_info', JSON.stringify(this.userInfo));
                localStorage.setItem('chatbot_user_submitted', 'true');
                localStorage.setItem('chatbot_user_time', Date.now().toString());
                // Scroll to bottom to show chat input
                this.$nextTick(() => {
                    this.scrollToBottom();
                });
            }
        },

        async sendMessage() {
            // Check if user info is filled
            if (!this.userInfo.name || !this.userInfo.email || !this.userInfo.phone) {
                this.error = 'Mohon isi nama, email, dan WhatsApp terlebih dahulu.';
                return;
            }
            if (!this.newMessage.trim() || this.isTyping) return;

            const userMessage = this.newMessage.trim();
            this.newMessage = '';
            this.error = '';

            // Add user message
            this.messages.push({
                role: 'user',
                content: userMessage,
                time: this.formatTime(new Date())
            });
            this.scrollToBottom();

            // Show typing indicator
            this.isTyping = true;

            try {
                const response = await fetch('{{ route("chatbot.chat") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        message: userMessage,
                        conversation: this.getConversationHistory(),
                        user_name: this.userInfo.name || null,
                        user_email: this.userInfo.email || null,
                        user_phone: this.userInfo.phone || null
                    })
                });

                const data = await response.json();

                if (data.success) {
                    this.messages.push({
                        role: 'assistant',
                        content: data.message,
                        time: this.formatTime(new Date())
                    });
                } else {
                    this.error = data.message || 'Terjadi kesalahan. Silakan coba lagi.';
                }
            } catch (err) {
                console.error('Chat error:', err);
                this.error = 'Koneksi gagal. Periksa internet Anda.';
            } finally {
                this.isTyping = false;
                this.scrollToBottom();
                this.saveMessages();
            }
        },

        sendSuggestion(text) {
            this.newMessage = text;
            this.sendMessage();
        },

        getConversationHistory() {
            return this.messages.slice(-10).map(m => ({
                role: m.role,
                content: m.content
            }));
        },

        saveMessages() {
            localStorage.setItem('chatbot_messages', JSON.stringify(this.messages));
        },

        scrollToBottom() {
            this.$nextTick(() => {
                const container = document.getElementById('chat-messages');
                if (container) {
                    container.scrollTop = container.scrollHeight;
                }
            });
        },

        formatMessage(text) {
            // Simple markdown-like formatting
            return text
                .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
                .replace(/\*(.*?)\*/g, '<em>$1</em>')
                .replace(/\n/g, '<br>');
        },

        formatTime(date) {
            // Format date in Jakarta timezone (GMT+7) with dots separator
            // Convert local date to UTC then add 7 hours for WIB
            const utcDate = new Date(date.getTime() + (7 * 60 * 60 * 1000));
            const hours = String(utcDate.getUTCHours()).padStart(2, '0');
            const minutes = String(utcDate.getUTCMinutes()).padStart(2, '0');
            const seconds = String(utcDate.getUTCSeconds()).padStart(2, '0');
            return `${hours}.${minutes}.${seconds}`;
        }
    };
}
</script>
