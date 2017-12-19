let mix = require('laravel-mix');

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

mix
    .js('resources/assets/js/app.js', 'public/js')
    .sass('resources/assets/sass/app.scss', 'public/css')
    .styles(['node_modules/medium-editor/dist/css/medium-editor.min.css',
    'node_modules/medium-editor/dist/css/themes/bootstrap.min.css',
    'node_modules/vue-multiselect/dist/vue-multiselect.min.css'], 'public/css/all.css')
    .autoload({
        jquery: ['$', 'window.jQuery'],
        Popper: ['popper.js', 'default']
     });