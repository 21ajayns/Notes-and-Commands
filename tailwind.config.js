/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
            },
            colors: {
                ink: {
                    950: '#120e0a',
                    900: '#17130f',
                    850: '#1c1712',
                    800: '#221c16',
                    700: '#2b2019',
                    600: '#362c21',
                    500: '#a89a86',
                    400: '#c2b6a4',
                    300: '#d8cdbe',
                    100: '#f5efe6',
                },
            },
            boxShadow: {
                card: '0 1px 2px rgba(0,0,0,0.4)',
                popover: '0 12px 32px rgba(0,0,0,0.5), 0 2px 8px rgba(0,0,0,0.4)',
            },
        },
    },
    plugins: [],
};
