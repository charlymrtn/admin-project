
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('content-component', require('./components/ContentComponent.vue'));
Vue.component('categoria-component', require('./components/ContentComponent.vue'));

Vue.component('modal-eliminar-component', require('./components/modals/EliminarComponent.vue'));
Vue.component('modal-nuevoeditar-component', require('./components/modals/NuevoEditarComponent.vue'));

Vue.component('table-content-component', require('./components/tables/ContentComponent.vue'));

const app = new Vue({
    el: '#app',
    data: {
      menu: 0
    }
});
