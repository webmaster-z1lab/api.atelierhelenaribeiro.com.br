/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('../bootstrap')

import Echo from 'laravel-echo'
import Vue from 'vue'
import VueNativeNotification from 'vue-native-notification'

Vue.use(VueNativeNotification, {requestOnNotify: true})

if(process.env.MIX_ECHO_SERVER_ENABLE === "true") {
    window.io = require('socket.io-client')

    window.Echo = new Echo({
        broadcaster: 'socket.io',
        host: window.location.hostname + ':6001'
    })
}

Vue.component('notifications', require('./components/notifications').default)

new Vue({el: '#v-notifications'})
