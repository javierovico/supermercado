/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))


Vue.component('my-thoughts-component', require('./components/MyThoughtsComponent.vue').default);
Vue.component('form-component', require('./components/FormComponent.vue').default);
Vue.component('modal-nuevo-producto', require('./components/ModalNuevoProducto.vue').default);
Vue.component('thought-component', require('./components/ThoughtComponent.vue').default);

Vue.component('seccion-categoria', require('./components/CategoriasView.vue').default);
Vue.component('seccion-producto', require('./components/ProductosView.vue').default);
Vue.component('seccion-iniciar', require('./components/IniciarView.vue').default);

Vue.component('item-categoria', require('./components/ItemCategoriaView.vue').default);

//componentes pequenhos
Vue.component('lista-productos', require('./components/ListaProductos.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const appV = new Vue({
    el: '#app',
});
