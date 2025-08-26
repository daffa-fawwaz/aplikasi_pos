import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import tailwindcss from "@tailwindcss/vite";

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/css/app.css", "resources/js/app.js"],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        https: true, // gunakan HTTPS
        host: true, // supaya bisa diakses network/ngrok
        port: 5173, // port default Vite
        hmr: {
            protocol: "wss", // WebSocket secure untuk HMR
            host: "localhost",
            port: 5173, // port dev server Vite
        },
    },
    build: {
        rollupOptions: {
            output: {
                manualChunks: undefined,
            },
        },
    },
});
