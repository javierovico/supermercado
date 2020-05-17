<template>
    <div class="col-md-12">
        <table class="table">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Producto</th>
                <th scope="col">Cantidad</th>
                <th scope="col">Precio Unitario</th>
                <th scope="col">Total</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(producto,index) in productos">
                <th scope="row">{{index+1}}</th>
                <td>{{producto.nombre}}</td>
                <td>{{producto.pivot.cantidad}}</td>
                <td>{{$precio(producto.pivot.precio_actual)}}</td>
                <td>{{$precio(producto.pivot.precio_actual * producto.pivot.cantidad)}}</td>
            </tr>
            </tbody>
        </table>
        <vista-paginacion
            :actual="paginaActual"
            :total="paginaTotal"
            :slidingEndingSize="10"
            @paginacionClick="paginacionClick"
        ></vista-paginacion>
    </div>
</template>
<script>
    export default {
        props:[
            'auth',             //para saber si esta o no iniciado
        ],
        data() {
            return {
                productos: [],
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
            leer(){
                let parametroBusqueda = {
                    page: this.paginaActual,
                    cantidad: this.paginaCantidadItem,
                };
                axios.get('/compra/historial/detalle/'+this.$route.params.id,{params:parametroBusqueda}).then((response) => {
                    this.productos = response.data.data;
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
        }
    }
</script>
