<template>
    <!-- Modal -->
    <div class="modal fade bd-example-modal-xl" id="modalEditarProducto" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 v-if="modalEditar.indexEditando>=0" class="modal-title" id="exampleModalLongTitle">
                        Editar Producto {{modalEditar.productoEditando.nombre}}
                    </h5>
                    <h5 v-else class="modal-title" id="exampleModalLongTitle">
                        Agregar nuevo producto
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div v-if="cargando" class="d-flex justify-content-center">
                        <div class="spinner-border" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                    <form v-else>
                        <div class="form-group">
                            <label for="nombreProducto">Nombre</label>
                            <input v-model="modalEditar.productoEditando.nombre" type="text" class="form-control" id="nombreProducto">
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="codigoProducto">Codigo</label>
                                <input v-model="modalEditar.productoEditando.codigo" type="text" class="form-control" id="codigoProducto">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="inputPassword4">Impuesto</label>
                                <input v-model="modalEditar.productoEditando.impuesto" type="number" step="1" min="0" class="form-control" id="inputPassword4">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="stockProducto">En Stock</label>
                                <input v-model="modalEditar.productoEditando.stock" type="text" class="form-control" id="stockProducto">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="descuentoProducto">Descuento en porcentaje</label>
                                <input v-model="modalEditar.productoEditando.descuento" type="number" step="1" min="0" class="form-control" id="descuentoProducto">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="inputState">Tipo Medida</label>
                                <select v-model="modalEditar.productoEditando.tipo_medida_producto_id" id="inputState" class="form-control">
                                    <option disabled value="">Selecciona...</option>
                                    <option value="1">c/u</option>
                                    <option value="2">por Kg</option>
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="precioProducto">Precio</label>
                                <input v-model="modalEditar.productoEditando.precio" step="1" min="0" type="number" class="form-control" id="precioProducto">
                            </div>
                            <div class="form-group col-md-2">
                                <label for="inputZip">Precio Mayorista</label>
                                <input v-model="modalEditar.productoEditando.precio_mayorista" type="number" step="1" min="0" class="form-control" id="inputZip">
                            </div>
                            <div class="form-group col-md-2">
                                <label for="precioCosto">Precio Costo</label>
                                <input v-model="modalEditar.productoEditando.precio_costo" type="number" step="1" min="0" class="form-control" id="precioCosto">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button v-on:click.prevent="guardarProducto" class="btn btn-primary" type="button" :disabled="cargando">
                        <span v-if="cargando" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        {{!cargando?'Guardar':'Guardando'}}
                    </button>
<!--                    <button :disabled="cargando" v-on:click.prevent="guardarProducto" type="button" class="btn btn-primary">Guardar</button>-->
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props:['modalEditar'],
        data() {
            return {
                cargando: false
            }
        },

        // mounted() {
        //     this.cargando = false;
        // },

        methods: {
            guardarProducto(){
                this.cargando = true;
                axios.put('/producto/'+this.modalEditar.productoEditando.id, this.modalEditar.productoEditando)
                    .then((response) => {
                        this.$emit('cambiosGuardados');
                    }).catch((error)=>{
                        console.log(error);
                        alert(error.response.data.message);
                    }).finally(()=>{
                        $('#modalEditarProducto').modal('hide');
                        this.cargando = false;
                    });
            }
        }
    }
</script>
