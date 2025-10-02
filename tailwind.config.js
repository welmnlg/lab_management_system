import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            
            // ### TAMBAHKAN DARI SINI ###
            keyframes: {
              'scan-vertical': {
                '0%': { transform: 'translateY(0)' },
                '100%': { transform: 'translateY(240px)' }, // 240px adalah jarak gerak di dalam kotak 256px
              }
            },
            animation: {
              'scan': 'scan-vertical 2s linear infinite alternate',
            }
            // ### SAMPAI SINI ###
        },
    },

    plugins: [forms],
};