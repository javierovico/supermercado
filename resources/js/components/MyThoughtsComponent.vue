<template>
    <div id="principal">
        <header>
            <nav class="navbar navbar-expand-lg navbar-light navbar-dark bg-primary">
                <a class="navbar-brand" href="#" v-on:click="cambiarSeccion('inicio')">Del Super</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item active">
                            <a @click="cambiarSeccion('inicio')" class="nav-link" href="#">Inicio <span v-if="sel==='inicio'" class="sr-only">(current)</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Carrito<span v-if="sel==='carrito'" class="sr-only">(current)</span></a>
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
                    <form class="form-inline my-2 my-lg-0">
                        <input class="form-control mr-sm-2" type="search" placeholder="Busqueda..." aria-label="Buscar">
                        <a href="#!" class="text-white"><i class="material-icons prefix white-text">search</i></a>
                    </form>
                </div>
            </nav>
        </header>
        <main class="py-4">
            <div class="container-fluid">
                <seccion-producto v-if="sel === 'productos'"></seccion-producto>
                <seccion-categoria :_idPadre="null" v-if="sel === 'categorias'"></seccion-categoria>
                <seccion-iniciar @checkUser="checkUser();cambiarSeccion('inicio')" v-if="sel === 'iniciar'"></seccion-iniciar>
                <seccion-registro @checkUser="checkUser();cambiarSeccion('inicio')" v-if="sel === 'registro'"></seccion-registro>
                <seccion-inicio v-if="sel === 'inicio'"></seccion-inicio>
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
    export default {
        data() {
            return {
                auth:{
                    iniciado: false,
                    name: '',
                    roles:[],
                },
                sel: 'categorias',  //'inicio', 'carrito','categorias','productos','iniciar','registro'
            }
        },

        mounted() {
            this.checkUser();
            // $(document).ready(function(){
            //     $('.sidenav')
            //         .sidenav()
            //         .on('click tap', 'li a', () => {
            //             $('.sidenav').sidenav('close');
            //         });
            // });
        },

        methods: {
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
