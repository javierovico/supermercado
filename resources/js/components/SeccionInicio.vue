<template>
    <div class="col-md-12">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
                        <ol class="carousel-indicators">
                            <li data-target="#carouselExampleControls" data-slide-to="0" class="active"></li>
                            <li data-target="#carouselExampleControls" data-slide-to="1"></li>
                            <li data-target="#carouselExampleControls" data-slide-to="2"></li>
                        </ol>
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <div class="embed-responsive embed-responsive-21by9 hero-image">
                                    <a href="#!">
                                        <img @error="imageDefault"  class="img-thumbnail card-img-top embed-responsive-item" src="/img/1.png" style="object-fit: cover">
                                    </a>
                                </div>
                            </div>
                            <div class="carousel-item">
                                <div class="carousel-caption d-none d-md-block">
                                    <span class="h1 text-white bg-dark">¿Aún no te registrate?</span>
                                    <p><router-link class="text-white bg-dark" to="/registrarse">Registrarse</router-link></p>
                                </div>
                                <div class="embed-responsive embed-responsive-21by9 hero-image">
                                    <a href="#!">
                                        <img @error="imageDefault"  class="img-thumbnail card-img-top embed-responsive-item" src="/img/2.png" style="object-fit: cover">
                                    </a>
                                </div>
                            </div>
                            <div class="carousel-item">
                                <div class="carousel-caption d-none d-md-block">
                                    <span  class="h1 text-white bg-dark">Regristra a tu supermercado</span>
                                    <p><router-link  class="text-white bg-dark" to="/front/contacto">Registrarse</router-link></p>
                                </div>
                                <div class="embed-responsive embed-responsive-21by9 hero-image">
                                    <a href="#!">
                                        <img @error="imageDefault"  class="img-thumbnail card-img-top embed-responsive-item" src="/img/3.png" style="object-fit: cover">
                                    </a>
                                </div>
                            </div>
                        </div>
                        <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>
                </div>
                <div

                    v-for="(producto,index) in productos"
                    :key="index"
                    class="col-12 col-sm-6 col-md-4 col-lg-3" style="padding-top: 15px">
                    <div class="card" style="height: 100%">
                        <div class="embed-responsive embed-responsive-4by3 hero-image">
                            <a href="#!">
                                <div id="imagencontent" class="img-thumbnail card-img-top embed-responsive-item" >
                                    <img @error="imageDefault" :alt="'Imagen Producto'+producto.nombre" class="img-thumbnail card-img-top embed-responsive-item"  :src="$url+producto.codigo+'.jpg'" style="object-fit: cover">
                                    <img v-if="categoriaId==null && busquedaTexto.length==0" src="/img/destacado.png" class="img-thumbnail card-img-top embed-responsive-item" style="background: transparent"/>
                                </div>
                            </a>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">{{producto.nombre}}</h5>
                            <p class="card-text"></p>
                        </div>
                        <div class="card-footer">
                            <small class="text-muted">{{producto.precio.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")}} Gs. {{producto.tipo_medida_producto_id===1?'c/u':'x Kg.'}}</small>
                            <a class="float-right" @click.prevent="agregarCarrito(producto)" href="#!"><i class="material-icons">add_shopping_cart</i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <vista-paginacion
            :actual="paginaActual"
            :total="paginaTotal"
            :slidingEndingSize="10"
            @paginacionClick="paginacionClick"
        ></vista-paginacion>
        <modal-agregar-producto-carrito
            :producto="modalCarrito.producto"
        ></modal-agregar-producto-carrito>
    </div>
</template>
<script>
    export default {
        props:[
            'categoriaSeleccionada',//si recibimos una categoria, solo los productos de esa categoria, sino todos los productos
            'auth',             //para saber si esta o no iniciado
            'query',
        ],
        data() {
            return {
                categoriaId:null,
                busquedaTexto:'',
                productos: [],
                paginaActual: 1,
                paginaTotal: 1,
                paginaCantidadItem:20,
                busqueda:{
                    buscarProducto: ''
                },
                modalCarrito:{
                    producto:null
                }
            }
        },
        watch:{
            query:function(){
                console.log(this.query)
            },
            $route(to,from){
                if(this.analizarQuery(to.query)) {
                    this.leer();
                }
            },
            categoriaSeleccionada: function () {
                this.leer();
            },
        },
        mounted() {
            this.analizarQuery(this.$route.query);
            this.leer();
        },

        methods: {
            analizarQuery(query){
                let cambio = false;
                if(query.categoriaId != undefined){
                    cambio = true;
                    this.categoriaId = parseInt(query.categoriaId);
                }else{
                    this.categoriaId = null;
                }
                if(query.busqueda != undefined){
                    cambio = true;
                    this.busquedaTexto = query.busqueda;
                }else{
                    this.busquedaTexto = '';
                }
                if(query.paginaActual != undefined){
                    cambio = true;
                    this.paginaActual = parseInt(query.paginaActual);
                }else{
                    this.paginaActual = 1;
                }
                return true;
            },
            agregarCarrito(prod){
                if(this.$store.getters.iniciado){
                    this.modalCarrito.producto = prod;
                    $('#modalAgregarProductoCarrito').modal();
                }else{
                    this.$router.push('/iniciar-sesion')
                    // this.$emit('iniciar');
                }
            },
            imageDefault(e){
                e.target.src = '/img/producto.png';
            },
            leer(){
                let parametroBusqueda = {
                    categoria_id:this.categoriaId,
                    recursivo: 1,
                    page: this.paginaActual,
                    cantidad: this.paginaCantidadItem,
                    opcionCategoria: this.busqueda.opcionCategoria,
                    princi: this.categoriaId?null:1,    //muestra los principales si la categoria no esta seleccionada
                };
                if(this.busquedaTexto.length > 0){
                    parametroBusqueda.palabra_clave = this.busquedaTexto;
                    parametroBusqueda.princi = null;
                }
                axios.get('/producto',{params:parametroBusqueda}).then((response) => {
                    this.productos = response.data.data;
                    this.paginaTotal = response.data.last_page;
                }).catch((error)=>{
                    console.log(error);
                    alert(error.toString());
                });
            },
            paginacionClick(n){
                this.$router.push({path:'/',query:{categoriaId: this.categoriaId,paginaActual:n, busqueda:this.$route.query.busqueda}})
            },
            busquedaExterna(texto){
                this.busqueda.buscarProducto = texto;
                this.leer();
            }
        }
    }
</script>
