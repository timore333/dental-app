/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.jsx',
    ],
    darkMode: 'class',
    theme: {
        extend: {
            colors: {
                // Medical/Clinic Color Palette
                primary: {
                    50: '#f0f9ff',
                    100: '#e0f2fe',
                    200: '#bae6fd',
                    300: '#7dd3fc',
                    400: '#38bdf8',
                    500: '#0ea5e9',
                    600: '#0284c7',
                    700: '#0369a1',
                    800: '#075985',
                    900: '#0c4a6e',
                },
                medical: {
                    teal: '#17a2b8',
                    light: '#e8f5f7',
                    dark: '#0c4a6e',
                },
                // Semantic Colors
                success: '#10b981',
                warning: '#f59e0b',
                error: '#ef4444',
                info: '#3b82f6',
            },
            spacing: {
                '4.5': '1.125rem',
                '14': '3.5rem',
            },
            typography: {
                DEFAULT: {
                    css: {
                        color: 'currentColor',
                    },
                },
            },
            boxShadow: {
                'sm': '0 1px 2px 0 rgb(0 0 0 / 0.05)',
                'md': '0 4px 6px -1px rgb(0 0 0 / 0.1)',
                'lg': '0 10px 15px -3px rgb(0 0 0 / 0.1)',
                'dark': '0 4px 6px -1px rgb(0 0 0 / 0.3)',
            },
            borderRadius: {
                'lg': '0.5rem',
                'xl': '0.75rem',
                '2xl': '1rem',
            },
        },
    },
    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography'),
    ],
}
