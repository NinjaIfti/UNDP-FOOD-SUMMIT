import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            animation: {
                'fade-in-up': 'fade-in-up 0.6s ease-out forwards',
            },
            keyframes: {
                'fade-in-up': {
                    '0%': { opacity: '0', transform: 'translateY(20px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
            },
            colors: {
                primary: {
                    1: 'var(--color-primary-1)',
                    2: 'var(--color-primary-2)',
                },
                accent: {
                    1: 'var(--color-accent-1)',
                    2: 'var(--color-accent-2)',
                },
                ink: 'var(--color-text-dark)',
                copy: 'var(--color-text-muted)',
                undp: 'var(--color-undp)',
                cofsa: 'var(--color-cofsa-orange)',
            },
            fontFamily: {
                body: ['var(--font-body)', 'var(--font-fallback)'],
                title: ['var(--font-title)', 'var(--font-fallback)'],
                sans: ['var(--font-title)', ...defaultTheme.fontFamily.sans],
                serif: ['var(--font-body)', ...defaultTheme.fontFamily.serif],
            },
        },
    },

    plugins: [forms],
};
