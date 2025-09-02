import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import tailwindcss from "@tailwindcss/vite";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/css/app.css",
                "resources/js/app.js",
                "resources/js/barcode-search.js",
                "resources/js/barcode-create.js",
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        host: true, // biar bisa diakses dari luar (ngrok, LAN, dll)
        port: 5173,
        hmr: {
            protocol: "wss", // biar websocket jalan di HTTPS ngrok
            host: "https://8c9339c269f4.ngrok-free.app", // ganti sesuai subdomain ngrok aktif
            port: 443, // HMR lewat port default HTTPS
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
