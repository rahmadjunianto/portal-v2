<!-- Accessibility Widget - Preferensi Aksesibilitas Portal -->
<link rel="stylesheet" href="{{ asset('assets/accessibility/accessibility.css') }}">

<!-- FAB Button -->
<div class="a11y-widget-fab" id="a11y-fab" role="button" aria-label="Buka pengaturan aksesibilitas" tabindex="0">
    <button class="a11y-fab-button" aria-label="Pengaturan Aksesibilitas" title="Aksesibilitas">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
            <path d="M8 4.754a3.246 3.246 0 1 0 0 6.492 3.246 3.246 0 0 0 0-6.492zM5.754 8a2.246 2.246 0 1 1 4.492 0 2.246 2.246 0 0 1-4.492 0z"/>
            <path d="M9.796 1.343c-.527-1.79-3.065-1.79-3.592 0l-.094.319a.873.873 0 0 1-1.255.52l-.292-.16c-1.64-.892-3.433.902-2.54 2.541l.159.292a.873.873 0 0 1-.52 1.255l-.319.094c-1.79.527-1.79 3.065 0 3.592l.319.094a.873.873 0 0 1 .52 1.255l-.16.292c-.892 1.64.901 3.434 2.541 2.54l.292-.159a.873.873 0 0 1 1.255.52l.094.319c.527 1.79 3.065 1.79 3.592 0l.094-.319a.873.873 0 0 1 1.255-.52l.292.16c1.64.893 3.434-.902 2.54-2.541l-.159-.292a.873.873 0 0 1 .52-1.255l.319-.094c1.79-.527 1.79-3.065 0-3.592l-.319-.094a.873.873 0 0 1-.52-1.255l.16-.292c.893-1.64-.902-3.433-2.541-2.54l-.292.159a.873.873 0 0 1-1.255-.52l-.094-.319zm-2.633.283c.246-.835 1.428-.835 1.674 0l.094.319a1.873 1.873 0 0 0 2.693 1.115l.291-.16c.764-.415 1.6.42 1.184 1.185l-.159.292a1.873 1.873 0 0 0 1.116 2.692l.318.094c.835.246.835 1.428 0 1.674l-.319.094a1.873 1.873 0 0 0-1.115 2.693l.16.291c.415.764-.42 1.6-1.185 1.184l-.291-.159a1.873 1.873 0 0 0-2.693 1.116l-.094.318c-.246.835-1.428.835-1.674 0l-.094-.319a1.873 1.873 0 0 0-2.692-1.115l-.292.16c-.764.415-1.6-.42-1.184-1.185l.159-.291A1.873 1.873 0 0 0 1.945 8.93l-.319-.094c-.835-.246-.835-1.428 0-1.674l.319-.094A1.873 1.873 0 0 0 3.06 4.377l-.16-.292c-.415-.764.42-1.6 1.185-1.184l.292.159a1.873 1.873 0 0 0 2.692-1.115l.094-.319z"/>
        </svg>
    </button>
    <span class="a11y-tooltip">Aksesibilitas</span>
</div>

<!-- Overlay Background -->
<div class="a11y-overlay" id="a11y-overlay"></div>

<!-- Accessibility Panel -->
<div class="a11y-panel" id="a11y-panel" role="dialog" aria-label="Pengaturan Aksesibilitas" aria-hidden="true">
    <!-- Header -->
    <div class="a11y-panel-header">
        <h2 class="a11y-panel-title">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                <path d="M8 4.754a3.246 3.246 0 1 0 0 6.492 3.246 3.246 0 0 0 0-6.492zM5.754 8a2.246 2.246 0 1 1 4.492 0 2.246 2.246 0 0 1-4.492 0z"/>
                <path d="M9.796 1.343c-.527-1.79-3.065-1.79-3.592 0l-.094.319a.873.873 0 0 1-1.255.52l-.292-.16c-1.64-.892-3.433.902-2.54 2.541l.159.292a.873.873 0 0 1-.52 1.255l-.319.094c-1.79.527-1.79 3.065 0 3.592l.319.094a.873.873 0 0 1 .52 1.255l-.16.292c-.892 1.64.901 3.434 2.541 2.54l.292-.159a.873.873 0 0 1 1.255.52l.094.319c.527 1.79 3.065 1.79 3.592 0l.094-.319a.873.873 0 0 1 1.255-.52l.292.16c1.64.893 3.434-.902 2.54-2.541l-.159-.292a.873.873 0 0 1 .52-1.255l.319-.094c1.79-.527 1.79-3.065 0-3.592l-.319-.094a.873.873 0 0 1-.52-1.255l.16-.292c.893-1.64-.902-3.433-2.541-2.54l-.292.159a.873.873 0 0 1-1.255-.52l-.094-.319zm-2.633.283c.246-.835 1.428-.835 1.674 0l.094.319a1.873 1.873 0 0 0 2.693 1.115l.291-.16c.764-.415 1.6.42 1.184 1.185l-.159.292a1.873 1.873 0 0 0 1.116 2.692l.318.094c.835.246.835 1.428 0 1.674l-.319.094a1.873 1.873 0 0 0-1.115 2.693l.16.291c.415.764-.42 1.6-1.185 1.184l-.291-.159a1.873 1.873 0 0 0-2.693 1.116l-.094.318c-.246.835-1.428.835-1.674 0l-.094-.319a1.873 1.873 0 0 0-2.692-1.115l-.292.16c-.764.415-1.6-.42-1.184-1.185l.159-.291A1.873 1.873 0 0 0 1.945 8.93l-.319-.094c-.835-.246-.835-1.428 0-1.674l.319-.094A1.873 1.873 0 0 0 3.06 4.377l-.16-.292c-.415-.764.42-1.6 1.185-1.184l.292.159a1.873 1.873 0 0 0 2.692-1.115l.094-.319z"/>
            </svg>
            Preferensi Aksesibilitas
        </h2>
        <button class="a11y-close-btn" id="a11y-close" aria-label="Tutup panel">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
            </svg>
        </button>
    </div>

    <!-- Content -->
    <div class="a11y-panel-content">
        <!-- Accessibility Statement Card -->
        <a href="{{ route('accessibility') }}" class="a11y-statement-card">
            <div class="a11y-statement-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M5.338 1.59a61.44 61.44 0 0 0-2.837.856.48.48 0 0 0-.328.392c-.483.82-1.3 2.758-1.073 4.784a7.47 7.47 0 0 0 2.138 3.34c.43.307.956.64 1.679.877a.48.48 0 0 0 .477-.365c.09-.28.14-.567.14-.848 0-1.427-.87-2.74-2.54-3.609-.19-.1-.41-.164-.64-.164-.22 0-.44.065-.627.18-.55.343-.99 1.07-1.19 1.857-.06.267-.082.52.008.776.09.25.22.475.395.655.172.18.374.32.59.48a.48.48 0 0 0 .195-.392c.21-.42.32-.898.32-1.422 0-.52-.11-1.03-.32-1.47a.48.48 0 0 0-.19-.396c-.24-.14-.53-.26-.8-.39-.24-.12-.49-.2-.75-.2-.09 0-.18.02-.27.05-.27.1-.54.24-.8.4a.48.48 0 0 0-.18.44c0 .35.13.68.32.93.09.12.2.22.33.32.12.1.24.18.37.24.12.06.25.11.37.15.12.04.24.06.37.07.12.01.25.02.37.02.15 0 .3-.01.45-.03.27-.04.54-.1.81-.2.14-.06.28-.13.42-.21.14-.09.28-.19.41-.31.14-.12.27-.26.4-.41.13-.15.25-.31.37-.48.12-.18.23-.36.34-.56.11-.2.21-.42.31-.64.09-.22.18-.45.26-.69.08-.23.15-.48.22-.73.07-.25.13-.51.18-.78.05-.27.09-.55.13-.83.04-.28.07-.57.1-.86.03-.3.05-.6.07-.9.02-.3.03-.61.04-.92.01-.31.02-.62.02-.94 0-.32-.01-.64-.02-.96a8.08 8.08 0 0 0-.07-.98c-.03-.27-.08-.54-.13-.81-.05-.27-.11-.54-.18-.8-.07-.26-.14-.52-.23-.78-.08-.26-.17-.52-.27-.77-.1-.25-.21-.5-.33-.74-.12-.24-.25-.48-.39-.71-.14-.23-.29-.45-.45-.66-.16-.21-.33-.42-.51-.61-.18-.19-.36-.37-.56-.55-.19-.17-.39-.34-.6-.49-.21-.15-.43-.29-.65-.42-.22-.13-.45-.25-.68-.36-.23-.11-.47-.2-.71-.29-.24-.08-.49-.16-.74-.22-.25-.06-.51-.11-.77-.16-.26-.04-.53-.08-.8-.11-.27-.02-.54-.04-.82-.05-.28-.01-.56-.02-.85-.02-.29 0-.58 0-.87.01-.29.01-.58.03-.87.05-.29.03-.58.06-.87.1-.29.04-.58.09-.87.15-.29.05-.58.12-.87.19-.29.07-.57.15-.86.24-.29.09-.57.19-.85.29-.28.11-.56.22-.84.34-.28.12-.55.25-.82.39-.27.14-.54.29-.8.45-.26.16-.52.33-.77.51-.25.18-.5.37-.74.57-.24.2-.48.41-.71.63-.23.22-.45.45-.67.69-.22.24-.43.49-.63.74-.2.25-.39.51-.57.78-.18.27-.35.54-.51.82-.16.28-.31.57-.46.86-.14.29-.28.59-.41.89-.13.3-.25.61-.36.92-.11.31-.21.63-.31.95-.1.32-.18.64-.26.97-.08.33-.15.66-.21 1-.06.34-.11.68-.15 1.02-.04.34-.08.68-.11 1.03-.03.35-.05.7-.06 1.05-.01.35-.02.71-.02 1.06 0 .36 0 .71.01 1.07 0 .36.02.71.04 1.07.02.36.04.71.07 1.07.03.36.06.71.1 1.06.04.35.08.71.13 1.06.05.35.1.7.16 1.05.06.35.12.69.19 1.03.07.34.14.68.22 1.01.08.33.16.66.25.98.09.32.18.64.28.96.1.32.2.63.31.93.11.3.22.6.34.89.12.29.24.57.37.85.13.28.26.55.4.81.14.26.28.52.43.77.15.25.3.49.46.72.16.23.32.45.49.66.17.21.34.41.52.6.18.19.36.37.55.54.19.17.38.33.58.48.2.15.4.29.61.42.21.13.42.25.64.36.22.11.44.21.66.3.22.09.45.17.68.24.23.07.46.13.7.19.24.05.48.1.73.14.24.04.49.07.74.1.25.02.5.04.75.06.25.01.5.02.76.02.25 0 .5 0 .76-.01.25-.01.5-.02.75-.04.25-.02.5-.04.75-.07.25-.03.5-.06.75-.09.25-.04.49-.08.74-.12.25-.05.49-.1.74-.15.25-.06.49-.12.73-.18.24-.06.48-.13.72-.2.24-.07.48-.15.71-.23.24-.08.47-.16.7-.25.23-.09.46-.18.68-.28.23-.1.45-.2.67-.3.22-.11.44-.22.65-.33.21-.12.42-.24.63-.36.21-.12.41-.25.61-.38.2-.13.4-.27.59-.41.19-.14.38-.28.57-.43.19-.15.37-.3.55-.45.18-.15.35-.31.52-.47.17-.16.33-.32.49-.49.16-.17.31-.34.46-.51.15-.17.29-.35.43-.53.14-.18.27-.37.39-.56.12-.19.24-.38.35-.58.11-.2.21-.4.31-.6.1-.2.19-.41.27-.62.08-.21.16-.42.23-.63.07-.21.13-.43.19-.65.06-.22.11-.44.16-.66.05-.22.09-.45.12-.67.03-.22.06-.45.08-.68.02-.23.04-.46.05-.69.01-.23.02-.46.02-.7 0-.23 0-.46-.01-.69-.01-.23-.02-.46-.04-.69-.02-.23-.04-.46-.07-.69-.03-.23-.06-.45-.09-.68-.04-.23-.08-.45-.12-.67-.04-.22-.09-.45-.14-.67-.05-.22-.1-.44-.16-.66-.06-.22-.12-.43-.18-.65-.06-.22-.13-.43-.2-.64-.07-.21-.14-.42-.22-.63-.08-.21-.16-.41-.24-.61-.08-.2-.17-.4-.26-.59-.09-.19-.18-.38-.28-.57-.1-.19-.2-.37-.31-.55-.11-.18-.22-.36-.34-.54-.12-.17-.24-.34-.36-.51-.12-.17-.25-.33-.38-.49-.13-.16-.27-.31-.41-.46-.14-.15-.28-.29-.43-.43-.15-.14-.3-.27-.45-.4-.15-.13-.31-.25-.46-.37-.16-.12-.31-.23-.47-.34-.16-.11-.32-.21-.48-.31-.16-.1-.33-.19-.49-.28-.17-.09-.33-.17-.5-.25-.17-.08-.34-.15-.51-.22-.17-.07-.35-.13-.52-.19-.18-.06-.35-.11-.53-.16-.18-.05-.36-.09-.54-.13-.18-.04-.36-.07-.55-.1-.19-.03-.37-.05-.56-.07-.19-.02-.38-.03-.57-.04-.19-.01-.38-.01-.58-.01-.19 0-.38 0-.57.01-.19.01-.38.02-.57.04-.19.02-.38.04-.57.07-.19.03-.37.06-.56.1-.19.04-.37.08-.55.13-.18.05-.36.1-.54.16-.18.06-.35.12-.53.19-.17.07-.34.14-.51.22-.17.08-.33.16-.5.25-.16.09-.33.18-.49.28-.16.1-.32.2-.48.31-.16.11-.31.22-.47.34-.15.12-.3.24-.45.37-.15.13-.3.27-.45.4-.14.14-.28.29-.43.43-.14.15-.27.31-.41.46-.13.16-.26.33-.38.49-.12.17-.24.34-.36.51-.12.18-.23.36-.34.54-.11.18-.22.36-.31.55-.1.19-.19.38-.28.57-.09.19-.18.39-.26.59-.08.2-.16.4-.24.61-.08.21-.15.42-.22.63-.07.21-.13.43-.2.64-.06.22-.12.43-.16.66-.06.22-.11.44-.14.67-.04.22-.08.45-.12.67-.03.23-.06.45-.09.68-.02.23-.04.46-.07.69-.02.23-.03.46-.04.69-.01.23-.01.46-.01.69 0 .24 0 .47.01.7 0 .23.02.46.04.69.02.23.04.46.07.69.03.23.06.45.1.68.04.23.08.45.13.68.05.23.1.45.15.67.06.22.12.44.18.66.06.22.13.43.2.65.07.21.15.42.22.63.08.21.16.41.25.61.09.2.18.4.27.6.1.2.2.4.3.59.1.19.21.38.32.57.11.19.22.37.34.55.12.18.24.36.36.53.12.17.25.34.37.51.13.17.26.33.39.49.13.16.27.31.4.46.14.15.28.29.42.43.14.14.28.28.43.41.15.13.3.26.45.39.15.13.3.25.45.37.15.12.31.23.46.34.15.11.31.21.46.31.16.1.31.19.47.28.16.09.32.17.48.25.16.08.32.15.49.22.16.07.33.13.49.19.16.06.33.11.5.16.16.05.33.09.5.13.16.04.33.07.5.1.16.03.33.05.5.07.16.02.33.03.5.04.17.01.33.01.5.01.17 0 .34 0 .5-.01.17-.01.33-.02.5-.04.17-.02.33-.04.5-.07.17-.03.33-.06.5-.1.17-.04.33-.08.5-.13.17-.05.33-.1.5-.16.17-.06.33-.12.5-.19.17-.07.33-.14.49-.22.17-.08.33-.16.49-.25.16-.09.32-.18.48-.28.16-.1.31-.2.47-.31.16-.11.31-.22.46-.34.15-.12.3-.24.45-.37.15-.13.3-.26.45-.39.14-.14.28-.28.42-.43.14-.15.27-.31.4-.46.13-.16.26-.33.39-.49.13-.17.25-.34.37-.51.12-.17.24-.35.36-.53.12-.18.23-.36.34-.55.11-.19.21-.38.3-.59.09-.2.18-.4.27-.6.09-.2.17-.4.25-.61.08-.21.15-.42.22-.63.07-.22.13-.43.2-.65.07-.22.12-.44.18-.66.06-.22.11-.44.15-.67.04-.23.08-.45.13-.68.04-.23.07-.45.1-.68.03-.23.05-.46.07-.69.02-.23.03-.46.04-.69.01-.23.01-.46.01-.7z"/>
                </svg>
            </div>
            <div class="a11y-statement-text">
                <h3> Pernyataan Aksesibilitas</h3>
                <p>Lihat pernyataan aksesibilitas kami</p>
            </div>
        </a>

        <!-- Features Grid -->
        <div class="a11y-features-grid">
            <!-- Widget Kebesaran -->
            <div class="a11y-feature-card" data-feature="widgetEnlarge">
                <div class="a11y-feature-header">
                    <div class="a11y-feature-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M1 2.828c.885-.37 2.154-.769 3.388-.893 1.33-.134 2.458.063 3.112.752v9.746c-.935-.53-2.12-.603-3.213-.493-1.18.12-2.37.461-3.287.811V2.828zm7.5-.141c.654-.689 1.782-.886 3.112-.752 1.234.124 2.503.523 3.388.893v9.923c-.918-.35-1.837-.636-2.954-.704a4.983 4.983 0 0 0-2.556-.394V2.687zM8 1.783C7.015.936 5.587.81 4.287.94c-1.514.153-3.042.672-3.994 1.105A.5.5 0 0 0 0 2.5v11a.5.5 0 0 0 .707.455c.882-.4 2.303-.881 3.68-1.02 1.409-.142 2.59.087 3.223.877a.5.5 0 0 0 .78 0c.633-.79 1.814-1.019 3.222-.877 1.378.139 2.8.62 3.681 1.02A.5.5 0 0 0 16 13.5v-11a.5.5 0 0 0-.293-.455c-.952-.433-2.48-.952-3.994-1.105C10.413.809 8.985.936 8 1.783z"/>
                        </svg>
                    </div>
                    <span class="a11y-feature-label">Widget Kebesaran</span>
                </div>
                <label class="a11y-toggle">
                    <input type="checkbox" id="a11y-widgetEnlarge">
                    <span class="a11y-toggle-slider"></span>
                </label>
            </div>

            <!-- Topeng Baca - DISABLED -->
            <div class="a11y-feature-card" data-feature="readingMask" style="opacity: 0.5; pointer-events: none;">
                <div class="a11y-feature-header">
                    <div class="a11y-feature-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M6 12.5a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 1 0v11a.5.5 0 0 1-.5.5z"/>
                            <path d="M.146 8.354a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L1.707 7.5H10.5a.5.5 0 0 1 0 1H1.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3z"/>
                        </svg>
                    </div>
                    <span class="a11y-feature-label">Topeng Baca (Nonaktif)</span>
                </div>
                <label class="a11y-toggle">
                    <input type="checkbox" id="a11y-readingMask" disabled>
                    <span class="a11y-toggle-slider"></span>
                </label>
            </div>

            <!-- Kontras Tinggi -->
            <div class="a11y-feature-card" data-feature="highContrast">
                <div class="a11y-feature-header">
                    <div class="a11y-feature-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                            <path d="M8 4a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-1 0v-4A.5.5 0 0 1 8 4z"/>
                        </svg>
                    </div>
                    <span class="a11y-feature-label">Kontras Tinggi</span>
                </div>
                <label class="a11y-toggle">
                    <input type="checkbox" id="a11y-highContrast">
                    <span class="a11y-toggle-slider"></span>
                </label>
            </div>

            <!-- Font Dislexia -->
            <div class="a11y-feature-card" data-feature="dyslexicFont">
                <div class="a11y-feature-header">
                    <div class="a11y-feature-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4zm2-1a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4a1 1 0 0 0-1-1H2z"/>
                            <path d="M5.5 10a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1H6a.5.5 0 0 1-.5-.5zm-1.496-.854a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 1 1 .708-.708l.146.147 1.146-1.147a.5.5 0 0 1 .708 0z"/>
                        </svg>
                    </div>
                    <span class="a11y-feature-label">Font Disleksia</span>
                </div>
                <label class="a11y-toggle">
                    <input type="checkbox" id="a11y-dyslexicFont">
                    <span class="a11y-toggle-slider"></span>
                </label>
            </div>

            <!-- Rata Kanan -->
            <div class="a11y-feature-card" data-feature="textRight">
                <div class="a11y-feature-header">
                    <div class="a11y-feature-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M12.5 15a.5.5 0 0 1-.5-.5v-13a.5.5 0 0 1 1 0v13a.5.5 0 0 1-.5.5z"/>
                            <path d="M11 2.5a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 0 1h-2a.5.5 0 0 1-.5-.5z"/>
                            <path d="M2 9.5a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-11a.5.5 0 0 1-.5-.5zm4.5.5a.5.5 0 0 0 0-1H7a.5.5 0 0 0 0 1h2.5z"/>
                        </svg>
                    </div>
                    <span class="a11y-feature-label">Rata Kanan</span>
                </div>
                <label class="a11y-toggle">
                    <input type="checkbox" id="a11y-textRight">
                    <span class="a11y-toggle-slider"></span>
                </label>
            </div>

            <!-- Rata Tengah -->
            <div class="a11y-feature-card" data-feature="textCenter">
                <div class="a11y-feature-header">
                    <div class="a11y-feature-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M12.5 15a.5.5 0 0 1-.5-.5v-13a.5.5 0 0 1 1 0v13a.5.5 0 0 1-.5.5z"/>
                            <path d="M11 10H5a.5.5 0 0 1 0-1h6a.5.5 0 0 1 0 1z"/>
                        </svg>
                    </div>
                    <span class="a11y-feature-label">Rata Tengah</span>
                </div>
                <label class="a11y-toggle">
                    <input type="checkbox" id="a11y-textCenter">
                    <span class="a11y-toggle-slider"></span>
                </label>
            </div>

            <!-- Sorot Tautan -->
            <div class="a11y-feature-card" data-feature="highlightLinks">
                <div class="a11y-feature-header">
                    <div class="a11y-feature-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M4.715 6.542 3.343 7.914a3 3 0 1 0 4.243 4.243l1.828-1.829A3 3 0 0 0 8.586 5.5L8 6.086a1.002 1.002 0 0 0-.154.199 2 2 0 0 1 .861 3.337L6.88 11.45a2 2 0 1 1-2.83-2.83l.793-.792a4.018 4.018 0 0 1-.128-1.287z"/>
                            <path d="M6.586 4.672A3 3 0 0 0 7.414 9.5l.775-.776a2 2 0 0 1-.896-3.346L9.12 3.55a2 2 0 1 1 2.83 2.83l-.793.792c.112.42.155.855.128 1.287l1.372-1.372a3 3 0 1 0-4.243-4.243L6.586 4.672z"/>
                        </svg>
                    </div>
                    <span class="a11y-feature-label">Sorot Tautan</span>
                </div>
                <label class="a11y-toggle">
                    <input type="checkbox" id="a11y-highlightLinks">
                    <span class="a11y-toggle-slider"></span>
                </label>
            </div>

            <!-- Sorot Judul -->
            <div class="a11y-feature-card" data-feature="highlightTitles">
                <div class="a11y-feature-header">
                    <div class="a11y-feature-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M8.637 13V3.669H7.379V7.62H2.758V3.67H1.5V13h1.258V8.728h4.62V13h1.259zm5.329 0V3.669h-1.244V10.5c0 1.93-.703 3.026-2.09 3.026-1.177 0-1.815-.764-1.835-1.718V3.669h-1.258V8.7l1.258.781 1.258-.781V10.5c0 .696.402 1.114 1.141 1.114.903 0 1.517-.632 1.517-1.659V3.669h-1.244zm-3.673 1.984c0 .922-.594 1.366-1.441 1.366-.82 0-1.373-.402-1.373-1.366 0-.922.553-1.37 1.373-1.37.847 0 1.441.45 1.441 1.37z"/>
                        </svg>
                    </div>
                    <span class="a11y-feature-label">Sorot Judul</span>
                </div>
                <label class="a11y-toggle">
                    <input type="checkbox" id="a11y-highlightTitles">
                    <span class="a11y-toggle-slider"></span>
                </label>
            </div>

            <!-- Cursor Besar -->
            <div class="a11y-feature-card" data-feature="largeCursor">
                <div class="a11y-feature-header">
                    <div class="a11y-feature-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M0 0h4v4H0V0zm6.88 8.808c-.193.166-.44.275-.712.275A1.104 1.104 0 0 1 5 8.104c0-.42.234-.808.612-1.088.37-.273.882-.432 1.44-.432h.152c1.108 0 2 .896 2 2.004 0 1.113-.892 2.02-2 2.02-1.113 0-2.02-.907-2.02-2.02 0-.58.247-1.097.624-1.47A2.12 2.12 0 0 1 8 4.636c.584 0 1.128.236 1.535.656.394.424.624.99.624 1.61 0 1.11-.904 2.012-2.02 2.012-.559 0-1.079-.224-1.475-.612A.995.995 0 0 1 6 8.104c0-.278.112-.52.293-.707.186-.191.44-.308.72-.308.285 0 .554.12.752.328.392.415.624 1.02.624 1.64 0 1.11-.904 2.012-2.02 2.012-.559 0-1.079-.224-1.475-.612A.995.995 0 0 1 4 8.104c0-.278.112-.52.293-.707.186-.191.44-.308.72-.308.285 0 .554.12.752.328.392.415.624 1.02.624 1.64 0 1.11-.904 2.012-2.02 2.012-.559 0-1.079-.224-1.475-.612A.995.995 0 0 1 0 8.104c0-.278.112-.52.293-.707.186-.191.44-.308.72-.308h6.04z"/>
                        </svg>
                    </div>
                    <span class="a11y-feature-label">Cursor Besar</span>
                </div>
                <label class="a11y-toggle">
                    <input type="checkbox" id="a11y-largeCursor">
                    <span class="a11y-toggle-slider"></span>
                </label>
            </div>

            <!-- Hentikan Animasi -->
            <div class="a11y-feature-card" data-feature="stopAnimations">
                <div class="a11y-feature-header">
                    <div class="a11y-feature-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M5 3.5h6A1.5 1.5 0 0 1 12.5 5v6a1.5 1.5 0 0 1-1.5 1.5H5A1.5 1.5 0 0 1 3.5 11V5A1.5 1.5 0 0 1 5 3.5z"/>
                        </svg>
                    </div>
                    <span class="a11y-feature-label">Henti Animasi</span>
                </div>
                <label class="a11y-toggle">
                    <input type="checkbox" id="a11y-stopAnimations">
                    <span class="a11y-toggle-slider"></span>
                </label>
            </div>
        </div>

        <!-- Ukuran Huruf -->
        <div class="a11y-feature-card" data-feature="fontSize">
            <div class="a11y-feature-header">
                <div class="a11y-feature-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M12.258 3h-8.51l-.083 2.46h.479c.26-1.544-.758-2.44-2.076-2.46h-.791l-.11-2.46h-1.341V3H1v11.11h.656c1.17 0 2.03-.78 2.252-1.98.16-1.13.77-1.91 1.984-1.91h.739l.084 2.46h1.298V14H6.5V3h5.758z"/>
                        <path d="M12.258 3h-8.51l-.083 2.46h.479c.26-1.544-.758-2.44-2.076-2.46h-.791l-.11-2.46h-1.341V3H1v11.11h.656c1.17 0 2.03-.78 2.252-1.98.16-1.13.77-1.91 1.984-1.91h.739l.084 2.46h1.298V14H6.5V3h5.758z"/>
                    </svg>
                </div>
                <span class="a11y-feature-label">Ukuran Huruf</span>
            </div>
            <div class="a11y-control-group">
                <button class="a11y-btn-control" id="a11y-fontSize-minus" aria-label="Kurangi ukuran huruf">−</button>
                <span class="a11y-value-display" id="a11y-fontSize-value">100%</span>
                <button class="a11y-btn-control" id="a11y-fontSize-plus" aria-label="Tambah ukuran huruf">+</button>
            </div>
        </div>

        <!-- Tinggi Garis -->
        <div class="a11y-feature-card" data-feature="lineHeight">
            <div class="a11y-feature-header">
                <div class="a11y-feature-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M1.5 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3A1.5 1.5 0 0 1 1.5 0h3a.5.5 0 0 1 0 1h-3a.5.5 0 0 1-.5-.5zm0 6a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3a.5.5 0 0 1 .5-.5zm0 6a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3a.5.5 0 0 1 .5-.5z"/>
                        <path d="M3 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3A1.5 1.5 0 0 1 2.5 0h3a.5.5 0 0 1 0 1h-3a.5.5 0 0 1-.5-.5zm0 6a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3a.5.5 0 0 1 .5-.5zm0 6a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3a.5.5 0 0 1 .5-.5z"/>
                    </svg>
                </div>
                <span class="a11y-feature-label">Tinggi Garis</span>
            </div>
            <div class="a11y-control-group">
                <button class="a11y-btn-control" id="a11y-lineHeight-minus" aria-label="Kurangi tinggi garis">−</button>
                <span class="a11y-value-display" id="a11y-lineHeight-value">160%</span>
                <button class="a11y-btn-control" id="a11y-lineHeight-plus" aria-label="Tambah tinggi garis">+</button>
            </div>
        </div>

        <!-- Spasi Huruf -->
        <div class="a11y-feature-card" data-feature="letterSpacing">
            <div class="a11y-feature-header">
                <div class="a11y-feature-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M2 4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V4zm9 0a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1h-4a1 1 0 0 1-1-1V4zm0 9a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-4a1 1 0 0 1-1-1v-3z"/>
                    </svg>
                </div>
                <span class="a11y-feature-label">Spasi Huruf</span>
            </div>
            <div class="a11y-control-group">
                <button class="a11y-btn-control" id="a11y-letterSpacing-minus" aria-label="Kurangi spasi huruf">−</button>
                <span class="a11y-value-display" id="a11y-letterSpacing-value">0px</span>
                <button class="a11y-btn-control" id="a11y-letterSpacing-plus" aria-label="Tambah spasi huruf">+</button>
            </div>
        </div>

        <!-- Penskalaan Konten -->
        <div class="a11y-feature-card" data-feature="contentScale">
            <div class="a11y-feature-header">
                <div class="a11y-feature-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M1 2.828c.885-.37 2.154-.769 3.388-.893 1.33-.134 2.458.063 3.112.752v9.746c-.935-.53-2.12-.603-3.213-.493-1.18.12-2.37.461-3.287.811V2.828zm7.5-.141c.654-.689 1.782-.886 3.112-.752 1.234.124 2.503.523 3.388.893v9.923c-.918-.35-1.837-.636-2.954-.704a4.983 4.983 0 0 0-2.556-.394V2.687zM8 1.783C7.015.936 5.587.81 4.287.94c-1.514.153-3.042.672-3.994 1.105A.5.5 0 0 0 0 2.5v11a.5.5 0 0 0 .707.455c.882-.4 2.303-.881 3.68-1.02 1.409-.142 2.59.087 3.223.877a.5.5 0 0 0 .78 0c.633-.79 1.814-1.019 3.222-.877 1.378.139 2.8.62 3.681 1.02A.5.5 0 0 0 16 13.5v-11a.5.5 0 0 0-.293-.455c-.952-.433-2.48-.952-3.994-1.105C10.413.809 8.985.936 8 1.783z"/>
                    </svg>
                </div>
                <span class="a11y-feature-label">Penskalaan Konten</span>
            </div>
            <div class="a11y-control-group">
                <button class="a11y-btn-control" id="a11y-contentScale-minus" aria-label="Kurangi penskalaan">−</button>
                <span class="a11y-value-display" id="a11y-contentScale-value">100%</span>
                <button class="a11y-btn-control" id="a11y-contentScale-plus" aria-label="Tambah penskalaan">+</button>
            </div>
        </div>

        <!-- Pembaca Halaman -->
        <div class="a11y-feature-card">
            <div class="a11y-feature-header">
                <div class="a11y-feature-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M3.654 1.328a.678.678 0 0 0-1.015-.063L1.605 2.3c-.483.484-.661 1.169-.45 1.77a17.568 17.568 0 0 0 4.168 6.608 17.569 17.569 0 0 0 6.608 4.168c.601.211 1.286.033 1.77-.45l1.034-1.034a.678.678 0 0 0-.063-1.015l-2.307-1.794a.678.678 0 0 0-.58-.122l-2.19.547a1.745 1.745 0 0 1-1.657-.459L5.482 8.062a1.745 1.745 0 0 1-.46-1.657l.548-2.19a.678.678 0 0 0-.122-.58L3.654 1.328zM1.884.511a1.745 1.745 0 0 1 2.612.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.678.678 0 0 0 .178.643l2.457 2.457a.678.678 0 0 0 .644.178l2.189-.547a1.745 1.745 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.634 18.634 0 0 1-7.01-4.42 18.636 18.636 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877L1.885.511z"/>
                    </svg>
                </div>
                <span class="a11y-feature-label">Pembaca Halaman</span>
            </div>
            <div class="a11y-reader-controls">
                <button class="a11y-reader-btn" id="a11y-reader-play" aria-label="Mulai pembaca">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M11.596 8.697l-6.363 3.692c-.54.313-1.233-.066-1.233-.697V4.308c0-.63.692-1.01 1.233-.696l6.363 3.692a.802.802 0 0 1 0 1.393z"/>
                    </svg>
                    Play
                </button>
                <button class="a11y-reader-btn" id="a11y-reader-pause" aria-label="Jeda pembaca">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M5.5 3.5A1.5 1.5 0 0 1 7 5v6a1.5 1.5 0 0 1-3 0V5a1.5 1.5 0 0 1 1.5-1.5zm5 0A1.5 1.5 0 0 1 12 5v6a1.5 1.5 0 0 1-3 0V5a1.5 1.5 0 0 1 1.5-1.5z"/>
                    </svg>
                    Pause
                </button>
                <button class="a11y-reader-btn" id="a11y-reader-stop" aria-label="Hentikan pembaca">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M5 3.5h6A1.5 1.5 0 0 1 12.5 5v6a1.5 1.5 0 0 1-1.5 1.5H5A1.5 1.5 0 0 1 3.5 11V5A1.5 1.5 0 0 1 5 3.5z"/>
                    </svg>
                    Stop
                </button>
            </div>
        </div>

        <!-- Reset Button -->
        <button class="a11y-reset-btn" id="a11y-reset">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2v1z"/>
                <path d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466z"/>
            </svg>
            Reset Pengaturan
        </button>
    </div>
</div>

<!-- Reading Mask Overlay - using CSS only, no extra HTML needed -->

<!-- Scripts -->
<script src="{{ asset('assets/accessibility/accessibility.js') }}"></script>
