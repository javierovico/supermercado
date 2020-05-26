<template>
    <div id="principal">
        <header>
            <nav class="navbar navbar-expand-lg navbar-light navbar-dark bg-primary">
                <a href="#!" class="text-white" data-toggle="collapse" data-target="#navbarSupportedCategoria" aria-controls="navbarSupportedCategoria" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></a>
<!--                <button class="btn" type="button" data-toggle="collapse" data-target="#navbarSupportedCategoria" aria-controls="navbarSupportedCategoria" aria-expanded="false" aria-label="Toggle navigation">-->
<!--                    <i class="material-icons">storefront</i>-->
<!--                </button>-->
                <a class="navbar-brand" href="#" data-toggle="collapse" data-target="#navbarSupportedCategoria" aria-controls="navbarSupportedCategoria" aria-expanded="false" aria-label="Toggle navigation">
                    <img src="/img/logo.png" width="30" height="30" alt="">
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="material-icons prefix white-text">face</i>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto">
                        <li :class="'nav-item'+($route.fullPath==='/'?' active':'')">
                            <router-link to="/" @click.passive="categoriaSeleccionada = null" class="nav-link" href="#">Inicio <span v-if="$route.fullPath==='/'" class="sr-only">(current)</span></router-link>
                        </li>
                        <li v-if="$store.getters.isRol('user')" class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropDownCarrito" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Carrito
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropDownCarrito">
                                <router-link to="/carrito" class="dropdown-item" href="#">Carrito de compras</router-link>
                                <div class="dropdown-divider"></div>
                                <router-link to="/carrito/historial" class="dropdown-item" href="#">Carrito pagado</router-link>
                            </div>
                        </li>
                        <li v-if="$store.getters.isRol('admin')" class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Administracion
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <router-link to="/admin/categorias" class="dropdown-item" href="#">Categorias</router-link>
                                <router-link to="/admin/productos" class="dropdown-item" href="#">Productos</router-link>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#!">Otros</a>
                            </div>
                        </li>
                        <li v-if="$store.getters.isRol('financiero')" class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownPagos" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Gestion Pago
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdownPagos">
                                <router-link href="#" class="dropdown-item" to="/financiero/pagos">Ver Pagos</router-link>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownSessi" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{$store.getters.getAuth.iniciado?$store.getters.getAuth.name:'Invitado'}}
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdownSessi">
                                <template v-if="$store.getters.getAuth.iniciado">
                                    <a href="#" class="dropdown-item" v-on:click="cerrarSesion">Salir</a>
                                </template>
                                <template v-else>
                                    <router-link href="#" class="dropdown-item" to="/iniciar-sesion">Iniciar</router-link>
                                    <router-link href="#" class="dropdown-item" to="/registrarse">Registrarse</router-link>
                                </template>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
                        </li>
                    </ul>
                    <form class="form-inline my-2 my-lg-0" v-on:submit.prevent="$router.push({path:'/',query:{busqueda:busquedaTexto,categoriaId:$route.query.categoriaId}})">
                        <input v-model="busquedaTexto" class="form-control mr-sm-2" type="search" placeholder="Busqueda..." aria-label="Buscar">
                        <button type="submit" href="#!" class="text-white btn btn-link"><i class="material-icons prefix white-text">search</i></button>
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
                <router-view @checkUser="checkUser()" class="view"></router-view>
<!--                <seccion-producto v-if="sel === 'productos'"></seccion-producto>-->
<!--                <seccion-categoria :_idPadre="0" v-if="sel === 'categorias'"></seccion-categoria>-->
<!--                <seccion-iniciar @registrarse="cambiarSeccion('registro')" @checkUser="checkUser();cambiarSeccion('inicio')" v-if="sel === 'iniciar'"></seccion-iniciar>-->
<!--                <seccion-registro @registrarse="cambiarSeccion('registro')" @iniciar="cambiarSeccion('iniciar')" @checkUser="checkUser();cambiarSeccion('productos')" v-if="sel === 'registro'"></seccion-registro>-->
<!--                <seccion-inicio @iniciar="cambiarSeccion('iniciar')" @registrarse="cambiarSeccion('registro')" :auth="auth" ref="seccionInicio" :categoriaSeleccionada="categoriaSeleccionada" v-if="sel === 'inicio'"></seccion-inicio>-->
<!--                <seccion-carrito :auth="auth" v-if="sel === 'carrito'"></seccion-carrito>-->
            </div>
        </main>
        <!-- Footer -->
        <footer class="pt-4 my-md-5 pt-md-5 border-top">
            <div class="row">
                <div class="col-12 col-md">
                    <img class="mb-2" src="img/logo.png" alt="" width="24" height="24">
                    <small class="d-block mb-3 text-muted">© 2020-2020</small>
                </div>
                <div class="col-6 col-md-8">
                    <h5>Nosotros</h5>
                    <ul class="list-unstyled text-small">
                        <p>
                            Nacemos por que queremos cuidarte.<br>
                            En medio de una pandemia que afecta a todo el mundo, y mensajes que nos piden que nos quedemos en casa,
                            vimos la necesidad proteger la salud de las personas mas vulnerables.<br>
                            Buscamos hacer las compras del supermercado, mas facil, rapido y accesible.<br>
                            desde ahora quedate tranqui en casa, todo lo que necesitas llega hasta la puerta de tu casa.
                            facil - Rapido - seguro<br>
                            Como funciona?<br>
                            Desde ahora podes comprar desde tu casa y sin esfuerzo.<br>
                            Elegi un supermercado de tu zona de cobertura, hace tu recorrido en el supermercado virtual sin levantarte del
                            sofa, una vez que tengas todo lo mas rico en tu carrito podes proceder al pago, mientras tanto nosotros
                            preparamos tu pedido, vas a recibir un correo confirmando los productos que elegiste y el monto total.<br>
                            pronto el delivery estara llegando a tu casa.<br>
                            Compra desde el exterior, desde la computadora en la oficina o desde u celular.<br>
                            horario de envios<br>
                            una vez que confirmemos tu pedido, hacemos la entrega en un periodo de 1 - 5 horas
                            los horarios de entrega son de 9:00 a 19:00
                            los precios y horarios de entrega pueden variar segun el local y/o fechas festivas.
                        </p>
                    </ul>
                </div>
                <div class="col-6 col-md">
                    <h5>Mas</h5>
                    <ul class="list-unstyled text-small">
                        <li><a class="text-muted" href="img/terminos.pdf">Terminos y condiciones</a></li>
                    </ul>
                </div>
            </div>
        </footer>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                sel: 'inicio',  //'inicio', 'carrito','categorias','productos','iniciar','registro',
                categoriasAnidadas: [],
                categoriaSeleccionada:null,
                busquedaTexto : '',
            }
        },

        mounted:function() {
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
                // console.log(categoria.nombre);
                this.$router.push({path:'/',query:{categoriaId: categoria.id}})
            },
            cambiarSeccion: function (secc) {
                this.sel = secc;
            },
            cerrarSesion: function () {
                axios.post('/logout').then((response) => {
                    this.$store.commit('setAuth',response.data);
                }).then((response)=>{
                    this.$router.push('/');
                }).catch((error)=>{
                    console.error(error.response);
                });
            },
            checkUser: function () {
                axios.get('/checkUser').then((response) => {
                    this.$store.commit('setAuth',response.data);// = response.data;
                    console.log(this.$store.getters.getAuth);
                }).catch((error)=>{
                    this.
                    this.$auth.iniciado = false;
                    this.$auth.name = '';
                    this.$auth.roles = [];
                    console.error(error.response);
                });
            }
        }
    }
</script>
