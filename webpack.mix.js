const mix = require('laravel-mix')
const { resolve, vendors } = require('./webpack.config.js')

mix.webpackConfig({
    resolve
})

/**
 * Compile SASS to Stylesheets
 */
mix.sass('resources/sass/app.scss', 'public/css')

/**
 * Compile Javascript-Vue using Webpack
 */
mix.js('resources/js/notifications/main.js', 'public/js/notification')
    .extract(vendors)

mix.version()

if (mix.inProduction()) {
    mix.disableNotifications()
}
