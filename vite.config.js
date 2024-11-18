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
});