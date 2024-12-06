import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',

                'resources/admin/css/custom.css',

                'resources/js/app.js',

                'resources/admin/js/helpers.js',
                'resources/admin/js/custom.js'
            ],
            refresh: true,
        }),
    ],
});
