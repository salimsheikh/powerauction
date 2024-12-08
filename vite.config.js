import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',                
            ],
            refresh: true,
        }),
    ],
    server: {
        host: '127.0.0.1', // Change to '0.0.0.0' if you want it accessible from other devices on the network
        port: 5173,       // Default Vite port
    },
});
