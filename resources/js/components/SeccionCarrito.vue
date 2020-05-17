<template>
    <div class="col-md-12">
        <div class="container">
            <div class="row">
                <div v-if="carritoProductos.length == 0" class="col-md-12">
                    <p>Vaya, todavia no tenes ningun producto en tu carrito</p>
                </div>
                <div
                    v-for="(producto,index) in carritoProductos"
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
                            <p class="card-text">{{calcCantidad(producto)}} ({{calcPrecio(producto)}})</p>
                        </div>
                        <div class="card-footer">
                            <small class="text-muted">{{calcPrecioTotal(producto)}}</small>
                            <a class="float-right" @click="borrarCarrito(producto,index)" href="#!"><i class="material-icons">remove_shopping_cart</i></a>
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
        <div v-if="carritoProductos.length>0" class="container mt-4">
            <div class="row">
                <div class="col-12">
                    <form class="form-inline" @submit.prevent="confirmacionPago">
                        <button type="submit" class="btn btn-primary mb-2">Confirmar Pago</button>
                    </form>
                </div>
            </div>
        </div>
        <modal-borrar-carrito
            :modal="modalBorrarCarrito"
            @borrado="productoBorrado"
        ></modal-borrar-carrito>
        <modal-confirmar-compra
            :modal="modalConfirmacionPago"
            @pagoConfirmado="pagoConfirmado"
        ></modal-confirmar-compra>
    </div>
</template>
<script>
    import ModalBorrarCarrito from "./ModalBorrarCarrito";
    export default {
        components: {ModalBorrarCarrito},
        props:[
            'auth',             //para saber si esta o no iniciado
        ],
        data() {
            return {
                carritoProductos: [],
                paginaActual: 1,
                paginaTotal: 1,
                paginaCantidadItem:20,
                busqueda:{
                    buscarProducto: ''
                },
                modalBorrarCarrito:{
                    productoBorrando: null,
                    index: null,
                },
                modalConfirmacionPago:{
                    precio: 0,
                    compraId: 0,
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
        // computed:{
        // },

        methods: {
            pagoConfirmado(){
                this.carritoProductos.splice(0,this.carritoProductos.length);
            },
            confirmacionPago(){
                let total = 0;
                this.carritoProductos.forEach((e)=>{
                    total += e.precio * e.pivot.cantidad;
                })
                this.modalConfirmacionPago.precio = total;
                this.modalConfirmacionPago.compraId = this.carritoProductos[0].pivot.compra_id;
                $('#modalConfirmarCompra').modal();
            },
            borrarCarrito(prod,index){
                $('#modalBorrarCarrito').modal();
                this.modalBorrarCarrito.productoBorrando = prod;
                this.modalBorrarCarrito.index = index;
            },
            productoBorrado(index){
                this.carritoProductos.splice(index,1);
            },
            calcCantidad(producto){
                return producto.pivot.cantidad + (producto.tipo_medida_producto_id==1?' Unidades':' Kg')
            },
            calcPrecio(producto){
                return producto.precio.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")+' Gs. '+(producto.tipo_medida_producto_id===1?'c/u':'x Kg.')
            },
            calcPrecioTotal(producto){
                return 'Total: '+(producto.precio * producto.pivot.cantidad).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")+' Gs.';
            },
            imageDefault(e){
                e.target.src = '/img/producto.png';
            },
            leer(){
                let parametroBusqueda = {
                    page: this.paginaActual,
                    cantidad: this.paginaCantidadItem,
                };
                if(this.busqueda.buscarProducto.length > 0){
                    parametroBusqueda.palabra_clave = this.busqueda.buscarProducto;
                }
                axios.get('/compra',{params:parametroBusqueda}).then((response) => {
                    this.carritoProductos = response.data.data;
                    this.paginaTotal = response.data.last_page;
                }).catch((error)=>{
                    if(error.response.data.message == "tenes que iniciar sesion"){
                        this.$router.push('/iniciar-sesion');
                    }else{
                        $.notify({title:error.response.data.message})
                    }
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
