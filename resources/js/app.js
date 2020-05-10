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

import SlidingPagination from 'vue-sliding-pagination'


Vue.component('my-thoughts-component', require('./components/MyThoughtsComponent.vue').default);
Vue.component('form-component', require('./components/FormComponent.vue').default);
Vue.component('modal-nuevo-producto', require('./components/ModalNuevoProducto.vue').default);
Vue.component('thought-component', require('./components/ThoughtComponent.vue').default);

Vue.component('seccion-categoria', require('./components/CategoriasView.vue').default);
Vue.component('seccion-producto', require('./components/ProductosView.vue').default);
Vue.component('seccion-iniciar', require('./components/IniciarView.vue').default);
Vue.component('seccion-registro', require('./components/RegistrarView.vue').default);

Vue.component('item-categoria', require('./components/ItemCategoriaView.vue').default);

//componentes pequenhos
Vue.component('lista-productos', require('./components/ListaProductos.vue').default);
Vue.component('lista-productos-sel', require('./components/ListaProductosSel.vue').default);
Vue.component('vista-paginacion', require('./components/PaginationView.vue').default);
Vue.component('sliding-pagination',SlidingPagination);

Vue.component('seccion-inicio', require('./components/SeccionInicio.vue').default);

Vue.component('utils-modal', require('./components/UtilsModal.vue').default);
Vue.component('modal-editar-producto', require('./components/ModalEditarProducto.vue').default);
Vue.component('modal-editar-producto-thumbnail', require('./components/ModalEditarProductoThumbnail.vue').default);
Vue.component('modal-borrar-producto', require('./components/ModalBorrarProducto.vue').default);
Vue.component('modal-editar-producto-categoria', require('./components/ModalEditarProductoCategoria.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
Vue.prototype.$url = 'https://s3.us-east-2.amazonaws.com/supermercado.kamaleon360.com/elsuper/';
const appV = new Vue({
    el: '#app',
});
