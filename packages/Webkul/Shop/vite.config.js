import { defineConfig, loadEnv } from "vite";
import vue from "@vitejs/plugin-vue";
import laravel from "laravel-vite-plugin";
import tailwindcss from 'tailwindcss';
import autoprefixer from 'autoprefixer';
import path from "path";

export default defineConfig(({ mode }) => {
    const envDir = "../../../";

    Object.assign(process.env, loadEnv(mode, envDir));

    return {
        build: {
            emptyOutDir: true,
        },

        envDir,

        server: {
            host: process.env.VITE_HOST || "localhost",
            port: process.env.VITE_PORT || 5173,
        },

        plugins: [
            vue(),

            laravel({
                hotFile: "../../../public/shop-default-vite.hot",
                publicDirectory: "../../../public",
                buildDirectory: "themes/shop/default/build",
                input: [
                    "src/Resources/assets/css/app.css",
                    "src/Resources/assets/js/app.js",
                ],
                refresh: true,
            }),
        ],

        css: {
            postcss: {
                plugins: [
                    tailwindcss({
                        config: './packages/Webkul/Shop/tailwind.config.js'
                    }),
                    autoprefixer(),
                ],
            },
        },

        experimental: {
            renderBuiltUrl(filename, { hostId, hostType, type }) {
                if (hostType === "css") {
                    return path.basename(filename);
                }
            },
        },

        resolve: {
            alias: {
                '@shop': path.resolve(__dirname, './src/Resources'),
            },
        },
    };
});
