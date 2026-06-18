import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.tsx',
    ],

    // Safelist dynamic gradient classes used in Sponsores component
    safelist: [
        'from-amber-500', 'to-orange-600',
        'from-sky-500',   'to-blue-600',
        'from-rose-500',  'to-pink-600',
    ],

    theme: {
        extend: {
            colors: {
                brand: {
                    50:  '#f0fdf4',
                    100: '#dcfce7',
                    200: '#bbf7d0',
                    300: '#86efac',
                    400: '#4ade80',
                    500: '#16a34a',
                    600: '#15803d',
                    700: '#166534',
                    800: '#14532d',
                    900: '#052e16',
                    950: '#031a0d',
                },

                surface: {
                    DEFAULT: '#ffffff',
                    page:    '#f9f9fb',
                    raised:  '#f4f4f6',
                    overlay: '#ffffff',
                    inverse: '#18181b',
                },

                content: {
                    DEFAULT:   '#18181b',
                    secondary: '#52525b',
                    muted:     '#a1a1aa',
                    disabled:  '#d4d4d8',
                    inverse:   '#ffffff',
                    link:      '#15803d',
                },

                border: {
                    DEFAULT: '#e4e4e7',
                    strong:  '#a1a1aa',
                    focus:   '#16a34a',
                    inverse: '#3f3f46',
                },

                status: {
                    'success':         '#16a34a',
                    'success-subtle':  '#dcfce7',
                    'success-content': '#14532d',
                    'warning':         '#d97706',
                    'warning-subtle':  '#fef3c7',
                    'warning-content': '#78350f',
                    'danger':          '#dc2626',
                    'danger-subtle':   '#fee2e2',
                    'danger-content':  '#7f1d1d',
                    'info':            '#2563eb',
                    'info-subtle':     '#dbeafe',
                    'info-content':    '#1e3a8a',
                },
            },

            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
                display: ['"Fraunces"', 'ui-serif', 'Georgia', 'serif'],
            },

            keyframes: {
                'slide-in': {
                    '0%': { transform: 'translateX(100%)', opacity: '0' },
                    '100%': { transform: 'translateX(0)', opacity: '1' },
                },
            },

            animation: {
                'slide-in': 'slide-in 0.3s ease-out',
            },
        },
    },

    plugins: [forms],
};
