/** @type {import('tailwindcss').Config} */
module.exports = {

    content: [
        "./src/Resources/**/*.blade.php",
        "./src/Resources/**/*.js",
        "./src/Resources/**/*.vue"
    ],

    theme: {
        container: {
            center: true,
            screens: {
                "2xl": "1440px",
            },
            padding: {
                DEFAULT: "90px",
            },
        },

        screens: {
            sm: "525px",
            md: "768px",
            lg: "1024px",
            xl: "1240px",
            "2xl": "1440px",
            1180: "1180px",
            1060: "1060px",
            991: "991px",
            868: "868px",
        },

        extend: {
            colors: {
                navyBlue: "#060C3B",
                lightOrange: "#F6F2EB",
                darkGreen: '#40994A',
                darkBlue: '#0044F2',
                darkPink: '#F85156',
                // Colores personalizados para el tema naranja
                orange: {
                    50: '#fff7ed',
                    100: '#ffedd5',
                    200: '#fed7aa',
                    300: '#fdba74',
                    400: '#fb923c',
                    500: '#f97316',
                    600: '#ea580c',
                    700: '#c2410c',
                    800: '#9a3412',
                    900: '#7c2d12',
                    950: '#431407',
                },
            },

            fontFamily: {
                poppins: ["Poppins", "sans-serif"],
                dmserif: ["DM Serif Display", "serif"],
                nunito: ["Nunito", "sans-serif"],
            },

            backgroundColor: {
                'orange-translucent': 'rgba(249, 115, 22, 0.95)',
            },

            backdropBlur: {
                xs: '2px',
            },
        }
    },

    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography'),
    ],

    safelist: [
        {
            pattern: /icon-/,
        },
        {
            pattern: /bg-orange-/,
            variants: ['hover', 'focus', 'active'],
        },
        {
            pattern: /text-orange-/,
            variants: ['hover', 'focus', 'active'],
        }
    ]
};
