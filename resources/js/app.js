
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue').default;

//support vuex
import Vuex from 'vuex'
import VueEasyLightbox from 'vue-easy-lightbox'

Vue.use(Vuex)
Vue.use(VueEasyLightbox)
import storeData from "./store/index"

const store = new Vuex.Store(
    storeData
)

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))


Vue.component('active-trades', require('./components/ActiveTrades.vue').default);
Vue.component('trade-messages', require('./components/Messages.vue').default);
Vue.component('trade-messages-completed', require('./components/Messages_completed.vue').default);


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    store,  //vuex
});
