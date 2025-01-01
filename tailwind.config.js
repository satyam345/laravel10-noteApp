import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography'; // Optional
import aspectRatio from '@tailwindcss/aspect-ratio'; // Optional

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js', // Include JS files
        './resources/js/**/*.vue', // Include Vue.js files
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    darkMode: 'class', // Optional: Enable dark mode
    plugins: [forms, typography, aspectRatio], // Add plugins as needed
};
