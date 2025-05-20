import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css',
                'resources/js/app.js',
                'resources/css/main.css',
                'resources/css/catalog/css',
                'resources/js/catalog.js',
                'resources/css/about_us.css',
                'resources/css/contacts.css',
                'resources/js/brand.js',
                'resources/js/modal_slider.js',
                'resources/js/cart.js',
                'resources/css/filters.css',
            ],
            refresh: true,
        }),
    ],
});
