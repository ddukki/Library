import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue2 from '@vitejs/plugin-vue2';

export default defineConfig({
    plugins: [
        vue2(),
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/sass/library.scss',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
    resolve: {
        alias: {
            '~bootstrap': '/node_modules/bootstrap',
            'vue': 'vue/dist/vue.esm.js',
        },
    },
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
