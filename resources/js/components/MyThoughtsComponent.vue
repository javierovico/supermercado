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
                                <router-link to="/front/admin/sugerencias" class="dropdown-item" href="#">Buzon Sugerencia</router-link>
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
                        <li :class="'nav-item'+($route.fullPath==='/front/contacto'?' active':'')">
                            <router-link to="/front/contacto" class="nav-link" href="#">Contacto <span v-if="$route.fullPath==='/front/contacto'" class="sr-only">(current)</span></router-link>
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
<!--        <footer id="footer" class="section-bg">-->
<!--        <div class="footer-top">-->
<!--            <div class="container">-->
<!--                <div class="row">-->
<!--                    <div class="col-lg-6">-->
<!--                        <div class="row">-->
<!--                            <div class="col-sm-6">-->
<!--                                <div class="footer-info">-->
<!--                                    <h3>Del Super</h3>-->
<!--                                    <p>-->
<!--                                        Nacemos por que queremos cuidarte.-->
<!--                                        En medio de una pandemia que afecta a todo el mundo, y mensajes que nos piden que nos quedemos en casa, vimos la necesidad proteger la salud de las personas mas vulnerables.-->
<!--                                        Buscamos hacer las compras del supermercado, mas facil, rapido y accesible.-->
<!--                                        desde ahora quedate tranqui en casa, todo lo que necesitas llega hasta la puerta de tu casa. facil - Rapido - seguro-->
<!--                                    </p>-->
<!--                                </div>-->
<!--                                <div class="footer-newsletter">-->
<!--                                    <h4>Suscripcion</h4>-->
<!--                                    <form action="" method="post">-->
<!--                                        <input type="email" name="email"><input type="submit" value="Anotame!">-->
<!--                                    </form>-->
<!--                                </div>-->

<!--                            </div>-->

<!--                            <div class="col-sm-6">-->
<!--                                <div class="footer-links">-->
<!--                                    <h4>Links Utiles</h4>-->
<!--                                    <ul>-->
<!--                                        <li><a href="#">Acerca de nosotros</a></li>-->
<!--                                        <li><a href="#">Servicios</a></li>-->
<!--                                        <li><a href="/img/terminos.pdf">Terminos del servicio</a></li>-->
<!--                                        <li><a href="/img/terminos.pdf">Politicas de Privacidad</a></li>-->
<!--                                    </ul>-->
<!--                                </div>-->

<!--                                <div class="footer-links">-->
<!--                                    <h4>Contactos</h4>-->
<!--                                    <p>-->
<!--                                        Coronel Bogado C/ Saturio Rios <br>-->
<!--                                        San Lorenzo, Central<br>-->
<!--                                        Paraguay <br>-->
<!--                                        <a href="tel:+5959982425662 "><strong>Telefono:</strong> +5959982425662</a><br>-->
<!--                                        <a href="https://wa.me/5959982425662?text=Me%20gustaría%20saber%20mas"><strong>Whatsapp:</strong> 5959982425662</a> <br>-->
<!--                                        <a href="mailto:sacdelsuper@gmail.com" target="_blank"><strong>Correo:</strong> sacdelsuper@gmail.com</a> <br>-->
<!--                                    </p>-->
<!--                                </div>-->

<!--                                <div class="social-links">-->
<!--                                    <a href="https://twitter.com/" class="twitter"><i class="fa fa-twitter"></i></a>-->
<!--                                    <a href="https://www.facebook.com/" class="facebook"><i class="fa fa-facebook"></i></a>-->
<!--                                    <a href="https://www.instagram.com/" class="instagram"><i class="fa fa-instagram"></i></a>-->
<!--                                    <a class="linkedin"><i class="fa fa-linkedin"></i></a>-->
<!--                                </div>-->

<!--                            </div>-->

<!--                        </div>-->

<!--                    </div>-->

<!--                    <section id="contact" class="col-lg-6">-->

<!--                        <div class="form">-->

<!--                            <h4>Dejanos Un mensaje</h4>-->
<!--                            <p>Tambien podes hacerlo a travez de nuestro correo electronico, llamandonos, o escribiendonos por whatsapp :).</p>-->
<!--                            <form action="" method="get" role="form" class="contactForm">-->
<!--                                <div class="form-group">-->
<!--                                    <input type="text" name="name" class="form-control" id="name" placeholder="Tu nombre" data-rule="minlen:4" data-msg="Por favor ingresa al menos cuatro caracteres">-->
<!--                                    <div class="validation"></div>-->
<!--                                </div>-->
<!--                                <div class="form-group">-->
<!--                                    <input type="text" class="form-control" name="contacto" id="contacto" placeholder="Tu correo o Telefono" data-rule="minlen:4" data-msg="Por favor, proporciona un correo o numero de telefono para que podamos responderte">-->
<!--                                    <div class="validation"></div>-->
<!--                                </div>-->
<!--                                <div class="form-group">-->
<!--                                    <input type="text" class="form-control" name="subject" id="subject" placeholder="Asunto" data-rule="minlen:4" data-msg="Por favor, ingresa al menos cuatro caracteres">-->
<!--                                    <div class="validation"></div>-->
<!--                                </div>-->
<!--                                <div class="form-group">-->
<!--                                    <textarea class="form-control" name="message" rows="5" data-rule="required" data-msg="No te olvides del mensaje" placeholder="Mensaje y telefono..."></textarea>-->
<!--                                    <div class="validation"></div>-->
<!--                                </div>-->

<!--                                <div id="sendmessage">Tu mensaje fue correctamente enviado!</div>-->
<!--                                <div id="errormessage"></div>-->

<!--                                <div class="text-center"><button type="submit" title="Send Message">Enviar Mensaje</button></div>-->
<!--                            </form>-->
<!--                        </div>-->

<!--                    </section>-->



<!--                </div>-->

<!--            </div>-->
<!--        </div>-->

<!--        <div class="container">-->
<!--            <div class="copyright">-->
<!--                © 2020-2020 Copyright <strong>DelSuper</strong>. Todos los derechos reservados-->
<!--            </div>-->
<!--        </div>-->
<!--    </footer>-->
        <FooterPage></FooterPage>
    </div>
</template>

<script>
    import FooterPage from "./FooterPage";
    export default {
        components: {FooterPage},
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
