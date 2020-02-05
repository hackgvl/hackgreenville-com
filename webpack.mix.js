let mix = require('laravel-mix');

const {
    // If 'browserSync_host' is set in the .env file then use that value. If not default it.
    browserSync_host = '0.0.0.0:8000',
} = process.env || {}; /* process.env is set automatically if the .env file exists */

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/assets/js/app.js', 'public/js')
    .sass('resources/assets/sass/app.scss', 'public/css');

mix.version();


mix.browserSync(browserSync_host);
