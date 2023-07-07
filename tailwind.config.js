const defaultTheme = require('tailwindcss/defaultTheme');
const colors = require('tailwindcss/colors')

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
        '/node_modules/flowbite/**/*.{js,jsx,ts,tsx}'
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
        colors: {
            transparent: 'transparent',
            'body': '#0c0c0c',
            'card': '#2c2b2b',
            'sidebar': '#232222',
            'cardtop': '#151515',
            'bordercard': '#2c2b2b',
            black: colors.black,
            white: colors.white,
            gray: colors.gray,
            emerald: colors.emerald,
            indigo: colors.indigo,
            yellow: colors.yellow,
            amber: colors.amber,
            neutral: colors.neutral,
            zinc: colors.zinc,
            red: colors.red,
            stone: colors.stone,
            slate:colors.slate,
            'btnCancelBg': '#ff514f33',
            'btnCancelText': '#FF514F',
            'btnSubmitBg': '#29BB8933',
            'btnSubmitText': '#29BB89',
            'bgAmberTag': '#ff9f2433',
            'bgModal': '#4a41369e',
            'bgImgOverlay':'#00000094',
            'directoryBg': '#100f0f',
            'bgSubmittedBg': '#2c3dda69',
            'bgSubmittedText': '#31a2ff',

            /* new theme colors */
            'themeColorPrimary' : '#20E3AC',
            'themeColorGreen' : '#72D54F',
            'themeColorAccent' : '#0581A8',
            'themeColorSky' : '#5CE1E6',

            /* new colors */
            /* Chart colors*/
            'themeChartColorOne' : '#1ae5ae',
            'themeChartColorTwo' : '#1faa9c',
            'themeChartColorThree' : '#0581a8',
            'themeChartColorFour' : '#2f5f98',
            'themeChartColorFive' : '#31356e',
            'themeChartNoColor' : '#494f56'
        },
    },

    plugins: [
        require('@tailwindcss/forms'),
        require('flowbite/plugin')
    ],

    darkMode: 'class',
};
