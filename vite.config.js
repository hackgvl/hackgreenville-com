import { defineConfig, loadEnv } from 'vite';
import laravel from 'laravel-vite-plugin';
import {ViteImageOptimizer} from "vite-plugin-image-optimizer";
import * as fs from 'fs'

export default defineConfig(({mode}) => {
    Object.assign(process.env, loadEnv(mode, process.cwd()))

    const {
        VITE_SERVER_HMR_HOST = '0.0.0.0',
        VITE_SERVER_HOST = '0.0.0.0',
        VITE_SERVER_POLLING = true,
    } = process.env

    return {
        plugins: [
            laravel({
                input: [
                    'resources/css/app.css',
                    'resources/sass/app.scss',
                    'resources/js/app.js',
                ],
                refresh: true,
            }),
            ViteImageOptimizer({
                test: /\.(jpe?g|png|gif|tiff|webp|svg|avif)$/i,
                includePublic: true,
                ansiColors: true,
                png: {
                    // https://sharp.pixelplumbing.com/api-output#png
                    quality: 85,
                },
                jpeg: {
                    // https://sharp.pixelplumbing.com/api-output#jpeg
                    quality: 80,
                },
                jpg: {
                    // https://sharp.pixelplumbing.com/api-output#jpeg
                    quality: 80,
                },
                tiff: {
                    // https://sharp.pixelplumbing.com/api-output#tiff
                    quality: 100,
                },
                webp: {
                    // https://sharp.pixelplumbing.com/api-output#webp
                    lossless: true,
                },
                avif: {
                    // https://sharp.pixelplumbing.com/api-output#avif
                    lossless: true,
                },
            }),
        ],
        server: {
            hmr: {
                host: VITE_SERVER_HMR_HOST,
            },
            host: VITE_SERVER_HOST,
            https: httpsCertConfig(process.env),
            watch: {
                usePolling: VITE_SERVER_POLLING,
            },
        },
    }
});

function httpsCertConfig(env) {
    const {
        VITE_HTTPS_CERT = null,
        VITE_HTTPS_KEY = null
    } = env

    if (!VITE_HTTPS_CERT && !VITE_HTTPS_KEY) return false

    if (!fs.existsSync(VITE_HTTPS_CERT) && !fs.existsSync(VITE_HTTPS_KEY)) return false

    return {
        cert: fs.readFileSync(VITE_HTTPS_CERT),
        key: fs.readFileSync(VITE_HTTPS_KEY),
    }
}
