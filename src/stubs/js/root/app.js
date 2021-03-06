/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Remember to: npm i moment vue-loading-overlay vue-notification vuetify es6-promise @mdi/font
 * Also npm i vuetifyjs-mix-extension -D
 * And wrap the content i <v-app></v-app>
 */

import vuetify from './vuetify'
import 'es6-promise/auto'
import Notifications from 'vue-notification'
import moment from 'moment'

import 'vuetify/dist/vuetify.min.css';
import '@mdi/font/css/materialdesignicons.min.css';
import 'vue-loading-overlay/dist/vue-loading.css';

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.prototype.$http = axios;
Vue.prototype.moment = moment;

Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('j-errors', require('./JCommon/Errors/JErrors.vue').default);
Vue.component('user-index', require('./components/Users/UserIndex.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.use(Notifications)

const app = new Vue({
    vuetify,
    el: '#app',
});
