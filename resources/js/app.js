/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import UserByCompra from "./components/UserByCompra";

require('./bootstrap');

import 'jquery-ui/ui/widgets/autocomplete.js';
import 'jquery-ui/ui/widgets/datepicker.js';
import 'jquery-ui/ui/i18n/datepicker-es.js';
import 'jquery-ui/themes/base/all.css';
import './bancard-checkout-3.0.0.js';

import 'bootstrap-notify';
import VueRouter from 'vue-router';
import Vuex from 'vuex';

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
    template: `
<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">
    <button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>
    <span data-notify="icon"></span>
    <span data-notify="title"><b>{1}</b> {2}</span>
    <div class="progress" data-notify="progressbar">
        <div class="progress-bar progress-bar-{0}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
    </div>
    <a href="{3}" target="{4}" data-notify="url"></a>
</div>`
});
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

import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

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

Vue.component('font-awesome-icon', FontAwesomeIcon)

Vue.use(VueRouter);
Vue.use(Vuex);
import SeccionCarrito from "./components/SeccionCarrito";
import SeccionInicio from "./components/SeccionInicio";
import IniciarView from "./components/IniciarView";
import RegistrarView from "./components/RegistrarView";
import ProductosView from "./components/ProductosView";
import CategoriasView from "./components/CategoriasView";
import SeccionCarritoHistorial from "./components/SeccionCarritoHistorial";
import SeccionCarritoHistorialDetalle from "./components/SeccionCarritoHistorialDetalle";
import ContactForm from "./components/ContactForm";
import SuregenciasBox from "./components/SuregenciasBox";

const router = new VueRouter({
    mode: 'history',
    base: __dirname,
    routes:[
        {path: '/', component: SeccionInicio, props:(route)=>({query:route.query})},
        {path: '/carrito/historial/detalle/:id', component: SeccionCarritoHistorialDetalle},
        {path: '/carrito/historial', component: SeccionCarritoHistorial},
        {path: '/carrito', component: SeccionCarrito},
        {path: '/financiero/pagos', component: SeccionCarritoHistorial},
        {path: '/iniciar-sesion', component: IniciarView},
        {path: '/registrarse', component: RegistrarView},
        {path: '/admin/productos', component: ProductosView},
        {path: '/admin/categorias', component: CategoriasView},
        {path: '/front/admin/usuario/byCompra/:idCompra', component: UserByCompra, props:true},
        {path: '/front/contacto', component: ContactForm},
        {path: '/front/admin/sugerencias', component: SuregenciasBox},
    ],
});

const store = new Vuex.Store({
    state:{
        auth: {
            iniciado: false,
            name: '',
            roles:[],
        },
        pagoMinimo: 100000,     //ACTUALIZAR TAMBIEN EN ENVIORMENT
        pagoDelivery: 20000,    //actualizar en enviorment
    },
    mutations:{
        setAuth(state,valor){
            if(valor){
                state.auth = valor;
            }else{
                state.auth= {
                    iniciado: false,
                    name: '',
                    roles:[],
                };
            }
        },
    },
    getters:{
        getAuth: state =>{
            return state.auth;
        },
        iniciado: state=>{
            return state.auth && state.auth.iniciado;
        },
        pagoMinimo: state=>{
            return state.pagoMinimo;
        },
        pagoDelivery: state=>{
            return state.pagoDelivery;
        },
        isRol: (state) => (rol) =>{
            // console.log(state.auth.roles);
            return state.auth && state.auth.roles.includes(rol);
        },
    },
});

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
Vue.prototype.$intToFecha = function (unix_timestamp) {
// Create a new JavaScript Date object based on the timestamp
// multiplied by 1000 so that the argument is in milliseconds, not seconds.
    const date = new Date(unix_timestamp * 1000);
// Hours part from the timestamp
    const hours = date.getHours();
// Minutes part from the timestamp
    const minutes = "0" + date.getMinutes();
// Seconds part from the timestamp
    const seconds = "0" + date.getSeconds();
    const anho = date.getFullYear();
    const mes = '0'+(date.getMonth()+1);
    const dia = '0'+date.getDate();
    // console.log(date.getDate());
    // console.log(date.getMonth());
    // console.log(date.getDay());

// Will display time in 10:30:23 format
    const formattedTime = anho + '-' + mes.substr(-2) + '-' + dia.substr(-2) + ' ' + hours + ':' + minutes.substr(-2) + ':' + seconds.substr(-2);
    return formattedTime;

}
// Vue.prototype.$auth = {
//     iniciado: false,
//     name: '',
//     roles:[],
// };
const appV = new Vue({
    el: '#app',
    router,
    store
});
