const Encore = require('@symfony/webpack-encore');

Encore
    // the project directory where all compiled assets will be stored
    .setOutputPath('public/build/')
    // the public path used by the web server to access the output path
    .setPublicPath('/build')
    // will create public/build/app.js and public/build/app.css
    .addEntry('app', './assets/app.js')
    // enable Sass/SCSS support
    .enableSassLoader()
    // enable Vue.js support
    .enableVueLoader()
    // enable React support
    .enableReactPreset()
    // enable symfony UX support (this enables automatic handling of the assets)
    .enableStimulusBridge()
    .enableSassLoader()
    .enableVueLoader()
    .enableReactPreset()
    .enablePostCssLoader()
;

module.exports = Encore.getWebpackConfig();
