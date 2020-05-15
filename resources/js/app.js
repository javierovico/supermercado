/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

import 'jquery-ui/ui/widgets/autocomplete.js';
import 'jquery-ui/ui/widgets/datepicker.js';
import 'jquery-ui/ui/i18n/datepicker-es.js';
import 'jquery-ui/themes/base/all.css';
import './bancard-checkout-3.0.0.js';

import 'bootstrap-notify';
$.notifyDefaults({
    // settings
    element: 'body',
    position: null,
    type: "danger",
    allow_dismiss: true,
    newest_on_top: false,
    showProgressbar: false,
    placement: {
        from: "top",
        align: "right"
    },
    offset: 20,
    spacing: 10,
    z_index: 2000,
    delay: 5000,
    timer: 1000,
    url_target: '_blank',
    mouse_over: null,
    animate: {
        enter: 'animated fadeInDown',
        exit: 'animated fadeOutUp'
    },
    onShow: null,
    onShown: null,
    onClose: null,
    onClosed: null,
    icon_type: 'class',
    template: '<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">' +
        '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
        '<span data-notify="icon"></span> ' +
        '<span data-notify="title">{1}</span> ' +
        '<span data-notify="message">{2}</span>' +
        '<div class="progress" data-notify="progressbar">' +
        '<div class="progress-bar progress-bar-{0}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>' +
        '</div>' +
        '<a href="{3}" target="{4}" data-notify="url"></a>' +
        '</div>'
});
//menu
// import './hoverIntent'
// import './jquery.dropdown'
// import './jquery.dropdownPlain'

import 'metismenu/src/index'

$.datepicker.setDefaults({
    changeYear: true,
    changeMonth: true,
    dateFormat: 'yy-mm-dd'
});

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
Vue.component('seccion-carrito', require('./components/SeccionCarrito.vue').default);

Vue.component('utils-modal', require('./components/UtilsModal.vue').default);
Vue.component('modal-editar-producto', require('./components/ModalEditarProducto.vue').default);
Vue.component('modal-editar-producto-thumbnail', require('./components/ModalEditarProductoThumbnail.vue').default);
Vue.component('modal-borrar-producto', require('./components/ModalBorrarProducto.vue').default);
Vue.component('modal-editar-producto-categoria', require('./components/ModalEditarProductoCategoria.vue').default);
Vue.component('modal-agregar-producto-carrito',require('./components/ModalAgregarProductoCarrito.vue').default);
Vue.component('modal-confirmar-compra',require('./components/ModalConfirmarCompra.vue').default);
Vue.component('pruebas-pago',require('./components/PruebasPagos.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
Vue.prototype.$url = 'https://s3.us-east-2.amazonaws.com/supermercado.kamaleon360.com/elsuper/';
Vue.prototype.$precio = function (precio) {
    return precio.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")+' Gs. ';
}
Vue.prototype.$printJson = function (json) {
    if (typeof json != 'string') {
        json = JSON.stringify(json, undefined, 2);
    }
    json = json.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
    return json.replace(/("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)/g, function (match) {
        var cls = 'number';
        if (/^"/.test(match)) {
            if (/:$/.test(match)) {
                cls = 'key';
            } else {
                cls = 'string';
            }
        } else if (/true|false/.test(match)) {
            cls = 'boolean';
        } else if (/null/.test(match)) {
            cls = 'null';
        }
        return '<span class="' + cls + '">' + match + '</span>';
    });
};
const appV = new Vue({
    el: '#app',
});
