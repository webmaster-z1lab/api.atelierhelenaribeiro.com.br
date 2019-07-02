const mix = require('laravel-mix')

let vendors = [
    'vue',
    'vuex',
    'vee-validate',
    'axios',
    'sweetalert2',
    'lodash',
    'collect.js',
    'http-build-query',
    'vee-validate/dist/locale/pt_BR',
    'vue-native-notification',
    'vue2-perfect-scrollbar',
    'moment',
]

mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .extract(vendors)

mix.version()

if (mix.inProduction()) {
    mix.disableNotifications()
}
