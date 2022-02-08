const colors = require('tailwindcss/colors');
/* eslint-disable global-require */
module.exports = {
    content: ['./templates/**/*.twig', './assets/**/*.js'],
    darkMode: 'media',
    justifyContent: ['hover', 'focus'],
    theme: {
        extend: {
            fontFamily: {
                body: ['sans-serif'],
            },
            colors: {
                transparent: 'transparent',
                current: 'currentColor',
                white: '#ffffff',
                complementary: '#0875c6',
                tahiti: {
                    100: '#cffafe',
                    200: '#a5f3fc',
                    300: '#67e8f9',
                    400: '#22d3ee',
                    500: '#06b6d4',
                    600: '#0891b2',
                    700: '#0e7490',
                    800: '#155e75',
                    900: '#164e63',
                },
                primary: {
                    light: '#ee8035',
                    DEFAULT: '#c55a11',
                    dark: '#7f3a0b',
                },
                black: {
                    light: '#7a7a7a',
                    DEFAULT: '#0e1212',
                },
            },
            backgroundImage: {
                'footer-texture': "url('/assets/images/footer.png')",
                'index-cover': "url('/assets/images/index.jpg')",
            },
        },
    },
    variants: {
        extend: {},
    },
    plugins: [require('@tailwindcss/aspect-ratio')],
};
/* eslint-enable global-require */
