<template>
    <!-- Modal -->
    <div class="modal fade bd-example-modal-xl" id="modalAgregarProductoCarrito" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">
                        Agregar al Carrito {{producto?producto.nombre:'no-def'}}
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="d-flex justify-content-center" v-if="cargando">
                        <div class="spinner-border" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                    <div v-else>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="cantidadInput">{{compuCantidad}}</label>
                                <input v-model="cantidad" type="number" :min="compuCantMinima" :step="compuCantMinima" class="form-control" id="cantidadInput" placeholder="Cantidad">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button v-on:click.prevent="agregarAlCarrito" class="btn btn-primary" type="submit" :disabled="cargando">
                        <span v-if="cargando" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        {{!cargando?'Agregar':'Agregando'}}
                    </button>
<!--                    <button :disabled="cargando" v-on:click.prevent="guardarProducto" type="button" class="btn btn-primary">Guardar</button>-->
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    export default {
        props:['producto'],
        data() {
            return {
                cargando: false,
                cantidad: 1
            }
        },
        watch:{
            producto: function () {
                this.cantidad = 1;
            }
        },
        computed:{
            compuCantidad: function () {
                return this.producto?(this.producto.tipo_medida_producto_id===1?('Cantidad en unidades'):('Cantidad en Kg')):('No definido');
            },
            compuCantMinima: function () {
                return this.producto?(this.producto.tipo_medida_producto_id===1?('1'):('0.1')):('0.1');
            },
        },

        // mounted() {
        //     this.cargando = false;
        // },

        methods: {
            agregarAlCarrito(){
                if(this.producto.tipo_medida_producto_id ===1 && this.cantidad != parseInt(this.cantidad)){
                    $.notify({
                        message:"Debe ser un numero entero",
                    });
                    return;
                }
                if(this.cantidad < 0.1){
                    $.notify({
                        message:"No selecciono la cantidad necesaria",
                    });
                    return;
                }
                this.cargando = true;
                axios.post('/compra',{
                    cantidad: this.cantidad,
                    productoId: this.producto.id,
                }).then((response)=>{
                    $('#modalAgregarProductoCarrito').modal('hide');
                    console.log(response.data);
                }).catch((error)=>{
                    $.notify({message:error.response.data.message});
                }).finally(()=>{
                    this.cargando=false;
                });

                // setTimeout(()=>{
                //     this.cargando = false;
                // },2000);
                // axios.put('/producto/'+this.modalEditar.productoEditando.id, this.modalEditar.productoEditando)
                //     .then((response) => {
                //         this.$emit('cambiosGuardados');
                //     }).catch((error)=>{
                //         console.log(error);
                //         alert(error.response.data.message);
                //     }).finally(()=>{
                //         $('#modalAgregarProductoCarrito').modal('hide');
                //         this.cargando = false;
                //     });
            }
        }
    }
</script>
