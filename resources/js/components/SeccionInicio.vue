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
                            <a class="float-right" @click="agregarCarrito(producto)" href="#!"><i class="material-icons">add_shopping_cart</i></a>
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
        ],
        data() {
            return {
                categoriaId:null,
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
            categoriaSeleccionada: function () {
                this.leer();
            },
        },
        mounted() {
            this.leer();
        },

        methods: {
            agregarCarrito(prod){
                console.log(prod.nombre);
                if(this.auth.iniciado){
                    this.modalCarrito.producto = prod;
                    $('#modalAgregarProductoCarrito').modal();
                }else{
                    this.$emit('iniciar');
                }
            },
            imageDefault(e){
                e.target.src = '/img/producto.png';
            },
            leer(){
                let parametroBusqueda = {
                    categoria_id:this.categoriaSeleccionada?this.categoriaSeleccionada.id:null,
                    recursivo: 1,
                    page: this.paginaActual,
                    cantidad: this.paginaCantidadItem,
                    opcionCategoria: this.busqueda.opcionCategoria,
                };
                if(this.busqueda.buscarProducto.length > 0){
                    parametroBusqueda.palabra_clave = this.busqueda.buscarProducto;
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
                this.paginaActual = n;
                this.leer();
            },
            busquedaExterna(texto){
                this.busqueda.buscarProducto = texto;
                this.leer();
            }
        }
    }
</script>
