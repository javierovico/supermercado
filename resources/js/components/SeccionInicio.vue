<template>
    <div class="col-md-12">
        <div class="container">
            <div class="row">
                <div

                    v-for="(producto,index) in productos"
                    :key="index"
                    class="col-12 col-sm-6 col-md-4 col-lg-3" style="padding-top: 15px">
                    <div class="card" style="height: 100%">
                        <div class="embed-responsive embed-responsive-4by3 hero-image">
                            <a href="#!">
                                <img @error="imageDefault" :alt="'Imagen Producto'+producto.nombre" class="img-thumbnail card-img-top embed-responsive-item" :src="$url+producto.codigo+'.jpg'" style="object-fit: cover">
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
