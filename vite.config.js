import { defineConfig } from 'vite'
import laravel, { refreshPaths } from 'laravel-vite-plugin'
import react from '@vitejs/plugin-react'

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/css/landing.css',
                'resources/js/landing.js',
                'resources/js/player/app.jsx',
                'resources/css/player.css',
            ],
            refresh: [
                ...refreshPaths,
                'app/Livewire/**',
            ],
        }),
        react(),
    ],
    resolve: {
        alias: {
            '@': '/resources/js/player',
        },
    },
})