import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

export default {
    darkMode: 'class', // via .dark auf <html>, gesteuert durch ThemeToggle
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],

    theme: {
        extend: {
            colors: {
                // Primärfarbpalette (identisch für Light/Dark)
                primary: {
                    50: '#eff6ff',
                    100: '#dbeafe',
                    200: '#bfdbfe',
                    300: '#93c5fd',
                    400: '#60a5fa',
                    500: '#3b82f6', // Hauptakzent (Buttons, Links)
                    600: '#2563eb',
                    700: '#1d4ed8',
                    800: '#1e40af',
                    900: '#1e3a8a',
                },

                // Oberfläche – helle/dunkle Varianten
                surface: {
                    light: '#ffffff',
                    dark: '#0f172a', // dark:bg-surface-dark
                },

                // Textfarben – Light vs. Dark
                text: {
                    light: '#111827',
                    dark: '#f3f4f6',
                },

                // Akzentfarben (Sekundär)
                accent: {
                    blue: '#3b82f6',
                    green: '#22c55e',
                    red: '#ef4444',
                    yellow: '#facc15',
                },
            },

            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },

            boxShadow: {
                soft: '0 2px 4px rgba(0, 0, 0, 0.25)',
                card: '0 2px 8px rgba(0, 0, 0, 0.35)',
            },
        },
    },

    plugins: [forms, typography],
};
