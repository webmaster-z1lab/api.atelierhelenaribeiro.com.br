const path = require('path')

module.exports = {
  resolve: {
    alias: {
      // '@': path.resolve(__dirname, 'resources/js'),
      // '@components': path.resolve(__dirname, 'resources/js/components'),
      // '@forms': path.resolve(__dirname, 'resources/js/forms'),
      // '#': path.resolve(__dirname, 'resources/sass')
    }
  },
  vendors: [
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
}
