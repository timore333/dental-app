/**
 * Theme Switcher & Language Manager
 * Handles Dark/Light mode and RTL/LTR switching
 * Persists to localStorage and database
 */

class ThemeSwitcher {
    constructor(options = {}) {
        this.storageKeyTheme = options.storageKeyTheme || 'dental-app-theme';
        this.storageKeyLang = options.storageKeyLang || 'dental-app-lang';
        this.htmlElement = document.documentElement;
        this.csrfToken = this.getCSRFToken();

        this.init();
    }

    /**
     * Initialize theme switcher
     */
    init() {
        this.loadSavedTheme();
        this.loadSavedLanguage();
        this.setupEventListeners();
        this.watchSystemPreference();
    }

    /**
     * Load theme from localStorage
     */
    loadSavedTheme() {
        const saved = localStorage.getItem(this.storageKeyTheme);
        const theme = saved || this.getSystemPreference();
        this.setTheme(theme, false);
    }

    /**
     * Load language from localStorage
     */
    loadSavedLanguage() {
        const saved = localStorage.getItem(this.storageKeyLang);
        if (saved) {
            this.setLanguage(saved, false);
        }
    }

    /**
     * Get system color scheme preference
     */
    getSystemPreference() {
        if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
            return 'dark';
        }
        return 'light';
    }

    /**
     * Set theme (light or dark)
     */
    setTheme(theme, sync = true) {
        if (theme === 'dark') {
            this.htmlElement.classList.add('dark');
        } else {
            this.htmlElement.classList.remove('dark');
        }

        localStorage.setItem(this.storageKeyTheme, theme);
        this.updateThemeToggle(theme);

        if (sync) {
            this.syncThemeWithServer(theme);
        }

        // Dispatch custom event
        window.dispatchEvent(new CustomEvent('themeChanged', {
            detail: { theme }
        }));
    }

    /**
     * Toggle between light and dark
     */
    toggleTheme() {
        const current = this.htmlElement.classList.contains('dark') ? 'dark' : 'light';
        const next = current === 'dark' ? 'light' : 'dark';
        this.setTheme(next);
    }

    /**
     * Set language (en or ar)
     */
    setLanguage(lang, sync = true) {
        const dir = lang === 'ar' ? 'rtl' : 'ltr';

        this.htmlElement.setAttribute('lang', lang);
        this.htmlElement.setAttribute('dir', dir);
        localStorage.setItem(this.storageKeyLang, lang);

        if (sync) {
            this.syncLanguageWithServer(lang);
        }

        // Dispatch custom event
        window.dispatchEvent(new CustomEvent('languageChanged', {
            detail: { language: lang, direction: dir }
        }));
    }

    /**
     * Update theme toggle button UI
     */
    updateThemeToggle(theme) {
        const toggles = document.querySelectorAll('[data-theme-toggle]');
        toggles.forEach(toggle => {
            if (theme === 'dark') {
                toggle.innerHTML = '<i class="fas fa-sun"></i>';
                toggle.setAttribute('title', 'Switch to light mode');
            } else {
                toggle.innerHTML = '<i class="fas fa-moon"></i>';
                toggle.setAttribute('title', 'Switch to dark mode');
            }
        });
    }

    /**
     * Sync theme to server
     */
    async syncThemeWithServer(theme) {
        try {
            await fetch('/api/user/theme', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken
                },
                body: JSON.stringify({ theme })
            });
        } catch (error) {
            console.error('Failed to sync theme:', error);
        }
    }

    /**
     * Sync language to server
     */
    async syncLanguageWithServer(language) {
        try {
            await fetch('/api/user/language', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken
                },
                body: JSON.stringify({ language })
            });
        } catch (error) {
            console.error('Failed to sync language:', error);
        }
    }

    /**
     * Watch for system preference changes
     */
    watchSystemPreference() {
        if (!window.matchMedia) return;

        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
            if (!localStorage.getItem(this.storageKeyTheme)) {
                this.setTheme(e.matches ? 'dark' : 'light');
            }
        });
    }

    /**
     * Setup event listeners
     */
    setupEventListeners() {
        // Theme toggle buttons
        document.addEventListener('click', (e) => {
            if (e.target.closest('[data-theme-toggle]')) {
                this.toggleTheme();
            }

            if (e.target.closest('[data-language-switch]')) {
                const lang = e.target.closest('[data-language-switch]').getAttribute('data-language');
                if (lang) {
                    this.setLanguage(lang);
                }
            }
        });

        // Livewire theme change event
        if (window.Livewire) {
            window.addEventListener('themeChanged', (e) => {
                this.setTheme(e.detail.theme, false);
            });

            window.addEventListener('languageChanged', (e) => {
                this.setLanguage(e.detail.language, false);
            });
        }
    }

    /**
     * Get CSRF token from meta tag
     */
    getCSRFToken() {
        const token = document.querySelector('meta[name="csrf-token"]');
        return token ? token.getAttribute('content') : '';
    }

    /**
     * Get current theme
     */
    getCurrentTheme() {
        return this.htmlElement.classList.contains('dark') ? 'dark' : 'light';
    }

    /**
     * Get current language
     */
    getCurrentLanguage() {
        return this.htmlElement.getAttribute('lang') || 'en';
    }

    /**
     * Get current direction
     */
    getCurrentDirection() {
        return this.htmlElement.getAttribute('dir') || 'ltr';
    }
}

// Auto-initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        window.themeManager = new ThemeSwitcher();
    });
} else {
    window.themeManager = new ThemeSwitcher();
}
