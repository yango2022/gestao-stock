// public/js/color-modes.js
// Purpose: Controlar dark / light / auto mode usando dropdown Bootstrap 5

(() => {
    'use strict';

    const STORAGE_KEY = 'bs-theme';
    const html = document.documentElement;

    const getStoredTheme = () => localStorage.getItem(STORAGE_KEY);

    const getPreferredTheme = () => {
        const storedTheme = getStoredTheme();
        if (storedTheme) return storedTheme;

        return window.matchMedia('(prefers-color-scheme: dark)').matches
            ? 'dark'
            : 'light';
    };

    const setTheme = (theme) => {
        if (theme === 'auto') {
            html.setAttribute(
                'data-bs-theme',
                window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'
            );
        } else {
            html.setAttribute('data-bs-theme', theme);
        }
    };

    const showActiveTheme = (theme) => {
        const themeSwitcher = document.querySelector('#bd-theme');
        const themeSwitcherText = document.querySelector('#bd-theme-text');
        const activeThemeIcon = document.querySelector('.theme-icon-active use');

        if (!themeSwitcher) return;

        document
            .querySelectorAll('[data-bs-theme-value]')
            .forEach((element) => {
                element.classList.remove('active');
                element.setAttribute('aria-pressed', 'false');

                const checkIcon = element.querySelector('.bi.ms-auto');
                if (checkIcon) checkIcon.classList.add('d-none');
            });

        const activeButton = document.querySelector(
            `[data-bs-theme-value="${theme}"]`
        );

        if (!activeButton) return;

        activeButton.classList.add('active');
        activeButton.setAttribute('aria-pressed', 'true');

        const activeIcon = activeButton.querySelector('use').getAttribute('href');
        activeThemeIcon.setAttribute('href', activeIcon);

        const checkIcon = activeButton.querySelector('.bi.ms-auto');
        if (checkIcon) checkIcon.classList.remove('d-none');

        const label = activeButton.textContent.trim();
        themeSwitcherText.textContent = label;
    };

    // Inicialização
    document.addEventListener('DOMContentLoaded', () => {
        const storedTheme = getPreferredTheme();
        setTheme(storedTheme);
        showActiveTheme(storedTheme);

        document
            .querySelectorAll('[data-bs-theme-value]')
            .forEach((toggle) => {
                toggle.addEventListener('click', () => {
                    const theme = toggle.getAttribute('data-bs-theme-value');
                    localStorage.setItem(STORAGE_KEY, theme);
                    setTheme(theme);
                    showActiveTheme(theme);
                });
            });
    });

    // Escutar mudanças no SO quando está em "auto"
    window
        .matchMedia('(prefers-color-scheme: dark)')
        .addEventListener('change', () => {
            const storedTheme = getStoredTheme();
            if (storedTheme === 'auto') {
                setTheme('auto');
            }
        });
})();