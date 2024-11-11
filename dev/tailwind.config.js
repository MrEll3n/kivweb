/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'selector',
    content: ['./index.html', './src/**/*.{vue,js,ts,jsx,tsx}'],
    theme: {
        extend: {
            fontFamily: {
                'dosis-regular': ['Dosis-Regular', 'sans-serif'],
                'dosis-bold': ['Dosis-Bold', 'sans-serif'],
                'tourney-regular': ['Tourney-Regular', 'sans-serif'],
                'tourney-bold': ['Tourney-Bold', 'sans-serif'],
                'teko-regular': ['Teko-regular', 'sans-serif'],
                'teko-bold': ['Teko-bold', 'sans-serif'],
                'teko-logo' : ['Teko-Logo', 'sans-serif'],
                'teko-medium': ['Teko-Medium', 'sans-serif'],
                'teko-normal': ['Teko-Normal', 'sans-serif']
            },
        },
    },
    plugins: [],
};

