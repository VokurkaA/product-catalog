module.exports = {
    content: ["./src/**/*.{html,js,php}"],
    theme: {
        extend: {
            keyframes: {
                'fade-out': {
                    '0%': { opacity: '1' },
                    '80%': { opacity: '1' },
                    '100%': { opacity: '0', display: 'none' }
                }
            },
            animation: {
                'fade-out': 'fade-out 3s ease-in-out forwards'
            }
        },
    },
    plugins: [],
};