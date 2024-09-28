import { ViteImageOptimizer } from 'vite-plugin-image-optimizer';
import { defineConfig } from 'vite';
import autoprefixer from 'autoprefixer';

export default defineConfig(() => {
    return {
        server: {
            origin: 'http://localhost:5173',
        },
        base:"./",
        build: {
            rollupOptions: {
                input:['js/main.js','scss/style.scss', 'scss/tinystyles.scss'],
                output: {
                    assetFileNames: ({ name }) => {
                        if(/\.css$/.test(name)){
                            return 'css/[name].[ext]';
                        }
                        else if(/\.js$/.test(name)){
                            return 'js/[name].[ext]';
                        }
                        else if(/\.woff2$/.test(name)){
                            return 'fonts/[name].[ext]';
                        }
                        return '[ext]/[name].[ext]';
                    },
                    entryFileNames:'js/main.js',
                },
            },
        },
        css: {
            devSourcemap:true,
            postcss: {
                plugins: [
                    autoprefixer(),
                ],
            },
        },
        plugins: [
            ViteImageOptimizer({
                test: /\.(jpe?g|png|gif|tiff|webp|svg|avif)$/i,
                exclude: undefined,
                include: undefined,
                includePublic: true,
                logStats: true,
                ansiColors: true,
                svg: {
                    multipass: true,
                    plugins: [
                        {
                            name: 'preset-default',
                            params: {
                                overrides: {
                                    cleanupNumericValues: false,
                                    removeViewBox: false, // https://github.com/svg/svgo/issues/1128
                                },
                                cleanupIDs: {
                                    minify: false,
                                    remove: false,
                                },
                                convertPathData: false,
                            },
                        },
                        'sortAttrs',
                        {
                            name: 'addAttributesToSVGElement',
                            params: {
                                attributes: [{ xmlns: 'http://www.w3.org/2000/svg' }],
                            },
                        },
                    ],
                },
                png: {
                    // https://sharp.pixelplumbing.com/api-output#png
                    quality: 100,
                },
                jpeg: {
                    // https://sharp.pixelplumbing.com/api-output#jpeg
                    quality: 100,
                },
                jpg: {
                    // https://sharp.pixelplumbing.com/api-output#jpeg
                    quality: 100,
                },
                tiff: {
                    // https://sharp.pixelplumbing.com/api-output#tiff
                    quality: 100,
                },
                // gif does not support lossless compression
                // https://sharp.pixelplumbing.com/api-output#gif
                gif: {},
                webp: {
                    // https://sharp.pixelplumbing.com/api-output#webp
                    lossless: true,
                },
                avif: {
                    // https://sharp.pixelplumbing.com/api-output#avif
                    lossless: true,
                },
                cache: false,   
                cacheLocation: undefined,
            }),
        ],
    };
});