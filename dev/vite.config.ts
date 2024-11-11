import { fileURLToPath, URL } from 'node:url';

import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';

// https://vite.dev/config/
export default defineConfig({
    plugins: [vue(),],
    server: {
        port: 3000,
    },
    base: '/kivweb/frontend/',
    build: {
        outDir: '/Applications/XAMPP/xamppfiles/htdocs/kivweb/frontend', // Adjust this path to match your project structure
    },
    resolve: {
        alias: {
        '@': fileURLToPath(new URL('./src', import.meta.url))
        }
    }
});
