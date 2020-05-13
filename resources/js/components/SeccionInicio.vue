<template>
    <div class="col-md-12">
        <div class="container">
            <div class="row">
                <div
                    v-for="producto in productos"
                    class="col-12 col-sm-6 col-md-4 col-lg-3" style="padding-top: 15px">
                    <div class="card" style="height: 100%">
                        <div class="embed-responsive embed-responsive-4by3 hero-image">
                            <a href="#!">
                                <img :alt="'Imagen Producto'+producto.nombre" class="img-thumbnail card-img-top embed-responsive-item" :src="$url+producto.codigo+'.jpg'" style="object-fit: cover">
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="card-title text-right mt-0">
                                <i class="fas fa-star fa-lg text-warning"></i>
                                <i class="fas fa-star fa-lg text-warning"></i>
                                <i class="fas fa-star fa-lg text-warning"></i>
                                <i class="fas fa-star fa-lg text-warning"></i>
                                <i class="fas fa-star fa-lg text-warning"></i>
                            </div>
                            <h5 class="card-title"><strong>{{producto.nombre}}</strong></h5>
                            <p class="card-text"></p>
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
    </div>
</template>
<script>
    export default {
        props:['categoriaSeleccionada'],     //si recibimos una categoria, solo los productos de esa categoria, sino todos los productos
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
            leer(){
                let parametroBusqueda = {
                    categoria_id:this.categoriaSeleccionada?this.categoriaSeleccionada.id:null,
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
