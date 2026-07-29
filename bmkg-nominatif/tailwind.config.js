import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['DM Sans', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                canvas: '#f8fafc',
                surface: '#ffffff',
                text: { primary: '#0f172a', secondary: '#64748b', tertiary: '#94a3b8' },
                accent: { blue: '#0369a1', blueHover: '#075985' },
                border: { subtle: '#e2e8f0' },
            },
            borderRadius: { '2xl': '1rem', '3xl': '1.25rem' },
            spacing: { page: 'clamp(1rem, 3vw, 2rem)' },
        },
    },
    plugins: [forms],
};
