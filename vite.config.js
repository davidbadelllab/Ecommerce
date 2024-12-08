import { defineConfig, loadEnv } from "vite";
import laravel from "laravel-vite-plugin";
import path from "path";

export default defineConfig({
    build: {
        emptyOutDir: true,
    },
    
    plugins: [
        laravel({
            hotFile: "shop-bliss-vite.hot",
            publicDirectory: "public",
            buildDirectory: "themes/shop/bliss/build",
            input: [
                "resources/themes/bliss/assets/css/app.css",
                "resources/themes/bliss/assets/js/app.js",
            ],
            refresh: true,
        }),
    ],

    resolve: {
        alias: {
            '~': path.resolve(__dirname, 'node_modules'),
            '@': path.resolve(__dirname, 'resources')
        }
    },

    css: {
        postcss: {
            plugins: [
                require('tailwindcss')({
                    theme: {
                        extend: {
                            fontFamily: {
                                'concert': ['Concert One', 'cursive'],
                                'nunito': ['Nunito', 'sans-serif'],
                                'raleway': ['Raleway', 'sans-serif']
                            },
                            fontWeight: {
                                thin: '100',
                                extralight: '200',
                                light: '300',
                                normal: '400',
                                medium: '500',
                                semibold: '600',
                                bold: '700',
                                extrabold: '800',
                                black: '900'
                            }
                        }
                    }
                }),
                require('autoprefixer'),
            ],
        }
    }
});