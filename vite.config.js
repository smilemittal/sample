import {
    defineConfig
} from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel([
            'resources/js/app.js',
            'resources/js/lang/en.js',
            'resources/css/app.css',
            'resources/css/custom.css',
            'resources/css/dropdown.css',
            'resources/js/dropdown.js',
            // 'resources/js/flowbite.js',
            'node_modules/jszip/dist/jszip.min.js'

        ]),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    extensions: ['.vue'],
    resolve: {
        alias: {
            '@': '/resources/js',
            '@public': '/public'
        }
    },
    server: {
        host: "0.0.0.0",
        open: false,
        port: 5174,
        hmr: {
            overlay: false,
            //host: 'localhost',
        },

    },
});
