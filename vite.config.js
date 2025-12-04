import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    server: {
        host: '0.0.0.0', // Listen on all network interfaces
        port: 5173, // Default Vite port
        strictPort: true,
        hmr: {
            host: 'localhost', // Will be overridden by VITE_HMR_HOST in .env if set
        },
    },
});
