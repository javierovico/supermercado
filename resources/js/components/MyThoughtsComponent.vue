<template>
    <div id="principal">
        <header>
            <nav class="navbar navbar-expand-lg navbar-light navbar-dark bg-primary">
                <a href="#!" class="text-white" data-toggle="collapse" data-target="#navbarSupportedCategoria" aria-controls="navbarSupportedCategoria" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></a>
<!--                <button class="btn" type="button" data-toggle="collapse" data-target="#navbarSupportedCategoria" aria-controls="navbarSupportedCategoria" aria-expanded="false" aria-label="Toggle navigation">-->
<!--                    <i class="material-icons">storefront</i>-->
<!--                </button>-->
                <a class="navbar-brand" href="#" data-toggle="collapse" data-target="#navbarSupportedCategoria" aria-controls="navbarSupportedCategoria" aria-expanded="false" aria-label="Toggle navigation">Del Super</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="material-icons prefix white-text">face</i>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto">
                        <li :class="'nav-item'+(sel==='inicio'?' active':'')">
                            <a @click="cambiarSeccion('inicio');categoriaSeleccionada = null;" class="nav-link" href="#">Inicio <span v-if="sel==='inicio'" class="sr-only">(current)</span></a>
                        </li>
                        <li :class="'nav-item'+(sel==='carrito'?' active':'')">
                            <a @click="cambiarSeccion('carrito');categoriaSeleccionada = null;"  class="nav-link" href="#">Carrito<span v-if="sel==='carrito'" class="sr-only">(current)</span></a>
                        </li>
                        <li v-if="auth.roles.includes('admin')" class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Administracion
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" v-on:click="cambiarSeccion('categorias')" href="#">Categorias</a>
                                <a class="dropdown-item" v-on:click="cambiarSeccion('productos')" href="#">Productos</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#">Otros</a>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownSessi" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{auth.iniciado?auth.name:'Invitado'}}
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdownSessi">
                                <template v-if="auth.iniciado">
                                    <a href="#" class="dropdown-item" v-on:click="cerrarSesion">Salir</a>
                                </template>
                                <template v-else>
                                    <a href="#" class="dropdown-item" v-on:click="cambiarSeccion('iniciar')">Iniciar</a>
                                    <a href="#" class="dropdown-item" v-on:click="cambiarSeccion('registro')">Registrarse</a>
                                </template>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
                        </li>
                    </ul>
                    <form class="form-inline my-2 my-lg-0" v-on:submit.prevent="buscar">
                        <input v-model="busquedaTexto" class="form-control mr-sm-2" type="search" placeholder="Busqueda..." aria-label="Buscar">
                        <a href="#!" class="text-white"><i class="material-icons prefix white-text">search</i></a>
                    </form>
                </div>
            </nav>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-11 col-sm-8 col-md-6 col-lg-5">
                        <div class="collapse navbar-collapse" id="navbarSupportedCategoria">
                            <nav class="sidebar-nav">
                                <ul class="metismenu" id="menu1">
                                    <li v-for="(categoria,index) in categoriasAnidadas">
                                        <a v-on:click.passive="menuPulsado(categoria)" :class="(categoria.subCategorias.length > 0)?'has-arrow':''" href="#" :aria-expanded="(categoria.subCategorias.length > 0)?'false':null">
                                            <i class="material-icons">storefront</i>
                                            {{categoria.nombre}}
                                        </a>
                                        <ul v-if="categoria.subCategorias.length > 0" class="mm-collapse">
                                            <li v-for="(categoria2,index2) in categoria.subCategorias">
                                                <a v-on:click.passive="menuPulsado(categoria2)" :class="(categoria2.subCategorias.length > 0)?'has-arrow':''" :aria-expanded="(categoria2.subCategorias.length > 0)?'false':null" href="#">
                                                     {{categoria2.nombre}}
                                                </a>
                                                <ul v-if="categoria2.subCategorias.length > 0" class="mm-collapse">
                                                    <li v-for="(categoria3,index3) in categoria2.subCategorias">
                                                        <a v-on:click.passive="menuPulsado(categoria3)" :class="(categoria3.subCategorias.length > 0)?'has-arrow':''" :aria-expanded="(categoria3.subCategorias.length > 0)?'false':null" href="#">
                                                             {{categoria3.nombre}}
                                                        </a>
                                                        <ul v-if="categoria3.subCategorias.length > 0" class="mm-collapse">
                                                            <li v-for="(categoria4,index4) in categoria3.subCategorias">
                                                                <a v-on:click.passive="menuPulsado(categoria4)" :class="(categoria4.subCategorias.length > 0)?'has-arrow':''" :aria-expanded="(categoria4.subCategorias.length > 0)?'false':null" href="#">
                                                                     {{categoria4.nombre}}
                                                                </a>
                                                                <ul v-if="categoria4.subCategorias.length > 0" class="mm-collapse">
                                                                    <li v-for="(categoria5,index5) in categoria4.subCategorias">
                                                                        <a v-on:click.passive="menuPulsado(categoria5)" href="#">
                                                                             {{categoria5.nombre}}
                                                                        </a>
                                                                    </li>
                                                                </ul>
                                                            </li>
                                                        </ul>
                                                    </li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <main class="py-4">
            <div class="container-fluid">
                <seccion-producto v-if="sel === 'productos'"></seccion-producto>
                <seccion-categoria :_idPadre="0" v-if="sel === 'categorias'"></seccion-categoria>
                <seccion-iniciar @registrarse="cambiarSeccion('registro')" @checkUser="checkUser();cambiarSeccion('inicio')" v-if="sel === 'iniciar'"></seccion-iniciar>
                <seccion-registro @registrarse="cambiarSeccion('registro')" @iniciar="cambiarSeccion('iniciar')" @checkUser="checkUser();cambiarSeccion('productos')" v-if="sel === 'registro'"></seccion-registro>
                <seccion-inicio @iniciar="cambiarSeccion('iniciar')" @registrarse="cambiarSeccion('registro')" :auth="auth" ref="seccionInicio" :categoriaSeleccionada="categoriaSeleccionada" v-if="sel === 'inicio'"></seccion-inicio>
                <seccion-carrito :auth="auth" v-if="sel === 'carrito'"></seccion-carrito>
            </div>
        </main>
        <footer class="page-footer">
            <div class="container">
                <div class="row">
                    <div class="col l6 s12">
                        <h5 class="white-text">Footer Content</h5>
                        <p class="grey-text text-lighten-4">You can use rows and columns here to organize your footer content.</p>
                    </div>
                    <div class="col l4 offset-l2 s12">
                        <h5 class="white-text">Links</h5>
                        <ul>
                            <li><a class="grey-text text-lighten-3" href="#!">Link 1</a></li>
                            <li><a class="grey-text text-lighten-3" href="#!">Link 2</a></li>
                            <li><a class="grey-text text-lighten-3" href="#!">Link 3</a></li>
                            <li><a class="grey-text text-lighten-3" href="#!">Link 4</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="footer-copyright">
                <div class="container">
                    © 2014 Copyright Text
                    <a class="grey-text text-lighten-4 right" href="#!">More Links</a>
                </div>
            </div>
        </footer>
    </div>

<!--    <div class="container">-->
<!--        <seccion-producto v-if="sel === 'producto'"></seccion-producto>-->
<!--        <seccion-categoria v-if="sel === 'categorias'"></seccion-categoria>-->
<!--        <seccion-iniciar v-if="sel === 'iniciar'"></seccion-iniciar>-->
<!--    </div>-->
</template>

<script>
    const meti = require('metismenu/src/index');
    export default {
        data() {
            return {
                auth:{
                    iniciado: false,
                    name: '',
                    roles:[],
                },
                sel: 'carrito',  //'inicio', 'carrito','categorias','productos','iniciar','registro',
                categoriasAnidadas: [],
                categoriaSeleccionada:null,
                busquedaTexto : '',
            }
        },

        mounted() {
            this.checkUser();
            axios.get('/categoria/listaOrdenada').then((response)=>{
               this.categoriasAnidadas = response.data;
            }).catch((error)=>{
            }).finally(()=>{

            });
        },
        updated() {
            if(this.categoriasAnidadas.length >0){
                $('#menu1').metisMenu({
                    toggle:true,
                    preventDefault: false, //para que funcione el click
                });
            }
        },
        methods: {
            buscar: function(){
                this.$refs.seccionInicio.busquedaExterna(this.busquedaTexto);
            },
            menuPulsado: function(categoria){
                console.log(categoria.nombre);
                this.cambiarSeccion('inicio');
                this.categoriaSeleccionada = categoria;
                // $('#navbarSupportedCategoria').collapse('hide');
            },
            cambiarSeccion: function (secc) {
                this.sel = secc;
            },
            cerrarSesion: function () {
                axios.post('/logout').then((response) => {
                    this.auth.iniciado = false;
                    this.auth.name = '';
                    this.auth.roles = [];
                }).catch((error)=>{
                    console.error(error.response);
                });
            },
            checkUser: function () {
                axios.get('/checkUser').then((response) => {
                    this.auth = response.data;
                }).catch((error)=>{
                    this.auth.iniciado = false;
                    this.auth.name = '';
                    this.auth.roles = [];
                    console.error(error.response);
                });
            }
        }
    }
</script>
