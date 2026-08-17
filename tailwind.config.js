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
                    950: '#0a0a0c',
                    900: '#0f0f12',
                    850: '#131316',
                    800: '#18181c',
                    700: '#212126',
                    600: '#2b2b31',
                    500: '#6e6e76',
                    400: '#8d8d95',
                    300: '#b4b4ba',
                    100: '#eeeeef',
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
