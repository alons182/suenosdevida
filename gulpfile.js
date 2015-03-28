var elixir = require('laravel-elixir');
require('laravel-elixir-stylus');

elixir.config.sourcemaps = false;

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Less
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
    mix.less('app.less')
    .stylus('main.styl', 'resources/assets/css')

    .styles([
        'default.css',
        'default.date.css',
        'main.css'
    ],'public/css/bundle.css','resources/assets/css')

    .styles([
        'lightbox.css',
        'colpick.css',
        'classic.css',
        'classic.date.css',
        'admin.css'
    ],'public/css/backend.css','resources/assets/css')

    .scripts([
        'vendor/jquery-1.11.0.min.js',
        'vendor/jquery.hoverIntent.minified.js',
        'vendor/holder.js',
        'vendor/easyzoom.js',
        'vendor/legacy.js',
        'vendor/picker.js',
        'vendor/picker.date.js',
        'vendor/simpleCart.min.js',
        'vendor/jquery.cycle2.min.js',
        'vendor/countdown.js',
        'ubicaciones.js',
        'main.js'
    ],'public/js/bundle.js','resources/assets/js')
    .scripts([
        'vendor/jquery-1.11.0.min.js',
        'vendor/handlebars-v1.3.0.js',
        'vendor/lightbox.min.js',
        'vendor/ajaxupload.js',
        'vendor/colpick.js',
        'vendor/holder.js',
        'vendor/legacy.js',
        'vendor/picker.js',
        'vendor/picker.date.js',
        'ubicaciones.js',
        'admin.js'
    ],'public/js/backend.js','resources/assets/js')

    .version([
        'public/css/bundle.css',
        'public/css/backend.css',
        'public/js/bundle.js',
        'public/js/backend.js'
    ]);
});
