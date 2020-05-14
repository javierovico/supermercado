<template>
    <!-- Modal -->
    <div class="modal fade bd-example-modal-xl" id="modalBorrarCarrito" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">
                        Esta seguro de borrar el producto {{modal.productoBorrando?modal.productoBorrando.nombre:'indefinido'}} de tu lista?
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
                    <template v-else>
                        <p>No se puede deshacer</p>
                    </template>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button v-on:click.prevent="confirmarBorrar" class="btn btn-primary" type="button" :disabled="cargando">
                        <span v-if="cargando" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        {{!cargando?'Quitar':'Quitando'}}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props:['modal'],
        data() {
            return {
                cargando: false,
            }
        },
        watch:{
            modal: function () {
                this.cargando = false;
            }
        },
        methods: {
            confirmarBorrar(){
                this.cargando = true;
                axios.delete('/compraProducto/'+this.modal.productoBorrando.pivot.id)
                    .then((response) => {
                        $('#modalBorrarCarrito').modal('hide');
                        this.$emit('borrado',this.modal.index);
                    }).catch((error)=>{
                        $.notify({message:error.response.data.message});
                    }).finally(()=>{
                        this.cargando=false;
                    });
            }
        }
    }
</script>
