/** @type {import('tailwindcss').Config} */

export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
        "./node_modules/preline/dist/preline.js",
    ],
    darkMode: 'class', // Enable dark mode support
    theme: {
        extend: {},
    },
    plugins: [
        require('preline/plugin'),
    ],
}

