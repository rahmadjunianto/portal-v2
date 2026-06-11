/**
 * Accessibility Widget JavaScript
 * Preferensi Aksesibilitas Portal Kemenag
 */

(function() {
    'use strict';

    // ============================================
    // Configuration
    // ============================================
    const STORAGE_KEY = 'a11y_preferences';
    
    const DEFAULT_PREFERENCES = {
        // Toggle features (boolean)
        widgetEnlarge: false,
        readingMask: false,
        highContrast: false,
        dyslexicFont: false,
        textRight: false,
        textCenter: false,
        highlightLinks: false,
        highlightTitles: false,
        largeCursor: false,
        stopAnimations: false,
        
        // Numeric features
        fontSize: 100,      // 80-200%
        lineHeight: 160,    // in % (e.g., 160 = 1.6)
        letterSpacing: 0,    // in px
        contentScale: 100,  // 80-200%
        
        // Reader state
        readerActive: false,
        readerPaused: false
    };

    // ============================================
    // State Management
    // ============================================
    let preferences = { ...DEFAULT_PREFERENCES };
    let speechSynthesis = window.speechSynthesis;
    let currentUtterance = null;

    // ============================================
    // Storage Functions
    // ============================================
    function loadPreferences() {
        try {
            const stored = localStorage.getItem(STORAGE_KEY);
            if (stored) {
                preferences = { ...DEFAULT_PREFERENCES, ...JSON.parse(stored) };
            }
        } catch (e) {
            console.warn('Failed to load accessibility preferences:', e);
        }
    }

    function savePreferences() {
        try {
            localStorage.setItem(STORAGE_KEY, JSON.stringify(preferences));
        } catch (e) {
            console.warn('Failed to save accessibility preferences:', e);
        }
    }

    // ============================================
    // Apply Functions
    // ============================================
    function applyAllPreferences() {
        // Toggle features
        applyToggle('widgetEnlarge', 'a11y-widget-enlarge');
        // NOTE: readingMask disabled - causes blank page issues
        applyToggle('highContrast', 'a11y-high-contrast');
        applyToggle('dyslexicFont', 'a11y-dyslexic-font');
        applyToggle('textRight', 'a11y-text-right');
        applyToggle('textCenter', 'a11y-text-center');
        applyToggle('highlightLinks', 'a11y-highlight-links');
        applyToggle('highlightTitles', 'a11y-highlight-titles');
        applyToggle('largeCursor', 'a11y-large-cursor');
        applyToggle('stopAnimations', 'a11y-stop-animations');

        // Numeric features
        applyFontSize();
        applyLineHeight();
        applyLetterSpacing();
        applyContentScale();

        // Update UI
        updateUI();
    }

    function applyToggle(prefName, className) {
        if (preferences[prefName]) {
            document.body.classList.add(className);
        } else {
            document.body.classList.remove(className);
        }
    }

    function applyFontSize() {
        // Apply font size directly to body for better compatibility
        if (preferences.fontSize !== 100) {
            document.body.style.fontSize = preferences.fontSize + '%';
        } else {
            document.body.style.fontSize = '';
        }
    }

    function applyLineHeight() {
        document.documentElement.style.setProperty('--a11y-line-height', (preferences.lineHeight / 100));
        if (preferences.lineHeight !== 160) {
            document.body.classList.add('a11y-line-height');
        } else {
            document.body.classList.remove('a11y-line-height');
        }
    }

    function applyLetterSpacing() {
        document.documentElement.style.setProperty('--a11y-letter-spacing', preferences.letterSpacing + 'px');
        if (preferences.letterSpacing !== 0) {
            document.body.classList.add('a11y-letter-spacing');
        } else {
            document.body.classList.remove('a11y-letter-spacing');
        }
    }

    function applyContentScale() {
        const scale = preferences.contentScale / 100;
        document.documentElement.style.setProperty('--a11y-content-scale', scale);
        if (scale !== 1) {
            document.body.classList.add('a11y-content-scale');
        } else {
            document.body.classList.remove('a11y-content-scale');
        }
    }

    // ============================================
    // UI Update Functions
    // ============================================
    function updateUI() {
        // Update toggles
        Object.keys(DEFAULT_PREFERENCES).forEach(key => {
            const toggle = document.getElementById('a11y-' + key);
            if (toggle) {
                toggle.checked = preferences[key];
            }
            
            const card = document.querySelector(`[data-feature="${key}"]`);
            if (card) {
                if (preferences[key]) {
                    card.classList.add('a11y-feature-card--active');
                } else {
                    card.classList.remove('a11y-feature-card--active');
                }
            }
        });

        // Update numeric values
        updateNumericDisplay('fontSize', preferences.fontSize, '%');
        updateNumericDisplay('lineHeight', preferences.lineHeight, '%');
        updateNumericDisplay('letterSpacing', preferences.letterSpacing, 'px');
        updateNumericDisplay('contentScale', preferences.contentScale, '%');

        // Update button states
        updateControlButtons();
    }

    function updateNumericDisplay(name, value, unit) {
        const display = document.getElementById('a11y-' + name + '-value');
        if (display) {
            display.textContent = value + unit;
        }
    }

    function updateControlButtons() {
        // Font size buttons
        const fsMinus = document.getElementById('a11y-fontSize-minus');
        const fsPlus = document.getElementById('a11y-fontSize-plus');
        if (fsMinus) fsMinus.disabled = preferences.fontSize <= 80;
        if (fsPlus) fsPlus.disabled = preferences.fontSize >= 200;

        // Line height buttons
        const lhMinus = document.getElementById('a11y-lineHeight-minus');
        const lhPlus = document.getElementById('a11y-lineHeight-plus');
        if (lhMinus) lhMinus.disabled = preferences.lineHeight <= 100;
        if (lhPlus) lhPlus.disabled = preferences.lineHeight >= 250;

        // Letter spacing buttons
        const lsMinus = document.getElementById('a11y-letterSpacing-minus');
        const lsPlus = document.getElementById('a11y-letterSpacing-plus');
        if (lsMinus) lsMinus.disabled = preferences.letterSpacing <= -5;
        if (lsPlus) lsPlus.disabled = preferences.letterSpacing >= 10;

        // Content scale buttons
        const csMinus = document.getElementById('a11y-contentScale-minus');
        const csPlus = document.getElementById('a11y-contentScale-plus');
        if (csMinus) csMinus.disabled = preferences.contentScale <= 80;
        if (csPlus) csPlus.disabled = preferences.contentScale >= 200;
    }

    // ============================================
    // Event Handlers
    // ============================================
    function handleToggleChange(key) {
        preferences[key] = !preferences[key];
        
        // Mutually exclusive text alignment
        if (key === 'textRight' && preferences[key]) {
            preferences.textCenter = false;
            document.getElementById('a11y-textCenter').checked = false;
        } else if (key === 'textCenter' && preferences[key]) {
            preferences.textRight = false;
            document.getElementById('a11y-textRight').checked = false;
        }

        savePreferences();
        applyAllPreferences();
    }

    function handleNumericChange(key, delta) {
        const limits = {
            fontSize: { min: 80, max: 200, step: 10 },
            lineHeight: { min: 100, max: 250, step: 10 },
            letterSpacing: { min: -5, max: 10, step: 1 },
            contentScale: { min: 80, max: 200, step: 10 }
        };

        const limit = limits[key];
        preferences[key] = Math.max(limit.min, Math.min(limit.max, preferences[key] + delta));
        
        savePreferences();
        applyAllPreferences();
    }

    // ============================================
    // Page Reader Functions
    // ============================================
    function startReader() {
        if (!speechSynthesis) {
            alert('Browser Anda tidak mendukung Text-to-Speech');
            return;
        }

        // Stop any ongoing speech
        stopReader();

        // Get main content
        const content = document.getElementById('main-content') || document.body;
        const text = content.innerText || content.textContent;

        if (!text.trim()) {
            alert('Tidak ada teks untuk dibaca');
            return;
        }

        currentUtterance = new SpeechSynthesisUtterance(text);
        currentUtterance.lang = 'id-ID';
        currentUtterance.rate = 1;
        currentUtterance.pitch = 1;

        currentUtterance.onstart = () => {
            preferences.readerActive = true;
            preferences.readerPaused = false;
            updateReaderButtons();
        };

        currentUtterance.onend = () => {
            preferences.readerActive = false;
            preferences.readerPaused = false;
            updateReaderButtons();
        };

        currentUtterance.onerror = () => {
            preferences.readerActive = false;
            preferences.readerPaused = false;
            updateReaderButtons();
        };

        speechSynthesis.speak(currentUtterance);
        preferences.readerActive = true;
        savePreferences();
    }

    function pauseReader() {
        if (speechSynthesis) {
            if (preferences.readerPaused) {
                speechSynthesis.resume();
                preferences.readerPaused = false;
            } else {
                speechSynthesis.pause();
                preferences.readerPaused = true;
            }
            updateReaderButtons();
        }
    }

    function stopReader() {
        if (speechSynthesis) {
            speechSynthesis.cancel();
        }
        preferences.readerActive = false;
        preferences.readerPaused = false;
        updateReaderButtons();
    }

    function updateReaderButtons() {
        const playBtn = document.getElementById('a11y-reader-play');
        const pauseBtn = document.getElementById('a11y-reader-pause');
        const stopBtn = document.getElementById('a11y-reader-stop');

        if (playBtn) {
            playBtn.classList.toggle('a11y-active', preferences.readerActive && !preferences.readerPaused);
        }
        if (pauseBtn) {
            pauseBtn.classList.toggle('a11y-active', preferences.readerPaused);
        }
        if (stopBtn) {
            stopBtn.classList.toggle('a11y-active', preferences.readerActive);
        }
    }

    // ============================================
    // Reset Function
    // ============================================
    function resetPreferences() {
        // Stop reader if active
        stopReader();

        // Remove all a11y classes
        document.body.classList.remove(
            'a11y-widget-enlarge',
            'a11y-reading-mask',
            'a11y-high-contrast',
            'a11y-dyslexic-font',
            'a11y-text-right',
            'a11y-text-center',
            'a11y-highlight-links',
            'a11y-highlight-titles',
            'a11y-large-cursor',
            'a11y-stop-animations',
            'a11y-line-height',
            'a11y-letter-spacing',
            'a11y-content-scale'
        );

        // Reset CSS variables
        document.body.style.fontSize = '';
        document.documentElement.style.removeProperty('--a11y-line-height');
        document.documentElement.style.removeProperty('--a11y-letter-spacing');
        document.documentElement.style.removeProperty('--a11y-content-scale');

        // Reset preferences
        preferences = { ...DEFAULT_PREFERENCES };

        // Clear storage
        localStorage.removeItem(STORAGE_KEY);

        // Update UI
        updateUI();
    }

    // ============================================
    // Panel Toggle
    // ============================================
    function openPanel() {
        document.body.classList.add('a11y-panel-open');
        document.getElementById('a11y-panel')?.classList.add('a11y-panel--open');
        document.getElementById('a11y-overlay')?.classList.add('a11y-overlay--active');
    }

    function closePanel() {
        document.body.classList.remove('a11y-panel-open');
        document.getElementById('a11y-panel')?.classList.remove('a11y-panel--open');
        document.getElementById('a11y-overlay')?.classList.remove('a11y-overlay--active');
    }

    // ============================================
    // Event Binding
    // ============================================
    function bindEvents() {
        // FAB button
        document.getElementById('a11y-fab')?.addEventListener('click', openPanel);

        // Close button
        document.getElementById('a11y-close')?.addEventListener('click', closePanel);

        // Overlay click
        document.getElementById('a11y-overlay')?.addEventListener('click', closePanel);

        // Toggle switches
        Object.keys(DEFAULT_PREFERENCES).forEach(key => {
            const toggle = document.getElementById('a11y-' + key);
            if (toggle) {
                toggle.addEventListener('change', () => handleToggleChange(key));
            }
        });

        // Numeric controls
        ['fontSize', 'lineHeight', 'letterSpacing', 'contentScale'].forEach(key => {
            document.getElementById('a11y-' + key + '-minus')?.addEventListener('click', () => handleNumericChange(key, -DEFAULT_PREFERENCES[key.replace(/([A-Z])/g, (m) => m[0] === key[0] ? '-' + m.toLowerCase() : m).replace(/^-/, '')]?.step || -10));
            document.getElementById('a11y-' + key + '-plus')?.addEventListener('click', () => handleNumericChange(key, DEFAULT_PREFERENCES[key.replace(/([A-Z])/g, (m) => m[0] === key[0] ? '-' + m.toLowerCase() : m).replace(/^-/, '')]?.step || 10));
        });

        // Fixed step values
        document.getElementById('a11y-fontSize-minus')?.addEventListener('click', () => handleNumericChange('fontSize', -10));
        document.getElementById('a11y-fontSize-plus')?.addEventListener('click', () => handleNumericChange('fontSize', 10));
        document.getElementById('a11y-lineHeight-minus')?.addEventListener('click', () => handleNumericChange('lineHeight', -10));
        document.getElementById('a11y-lineHeight-plus')?.addEventListener('click', () => handleNumericChange('lineHeight', 10));
        document.getElementById('a11y-letterSpacing-minus')?.addEventListener('click', () => handleNumericChange('letterSpacing', -1));
        document.getElementById('a11y-letterSpacing-plus')?.addEventListener('click', () => handleNumericChange('letterSpacing', 1));
        document.getElementById('a11y-contentScale-minus')?.addEventListener('click', () => handleNumericChange('contentScale', -10));
        document.getElementById('a11y-contentScale-plus')?.addEventListener('click', () => handleNumericChange('contentScale', 10));

        // Reader controls
        document.getElementById('a11y-reader-play')?.addEventListener('click', startReader);
        document.getElementById('a11y-reader-pause')?.addEventListener('click', pauseReader);
        document.getElementById('a11y-reader-stop')?.addEventListener('click', stopReader);

        // Reset button
        document.getElementById('a11y-reset')?.addEventListener('click', () => {
            if (confirm('Apakah Anda yakin ingin reset semua pengaturan aksesibilitas?')) {
                resetPreferences();
            }
        });

        // Keyboard navigation
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                closePanel();
            }
        });
    }

    // ============================================
    // Initialize
    // ============================================
    function init() {
        loadPreferences();
        applyAllPreferences();
        bindEvents();
    }

    // Run on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})();
