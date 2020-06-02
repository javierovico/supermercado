<template>
    <!-- Modal -->
    <div class="modal fade bd-example-modal-xl" id="modalConfirmarEntrega" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">
                        Confirmar entrega de productos para {{modal.compraEntrega?modal.compraEntrega.user.name:'no'}}
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
                        <p>Establecer estado de compra a entregado.</p>
                    </template>
                    <div  id="iframe-container"/>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button @click.prevent="confirmarEntrega" class="btn btn-primary" type="button" :disabled="cargando">
                        <span v-if="cargando" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        {{!cargando?'Confirmar':'Confirmando'}}
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
            confirmarEntrega(){
                this.cargando = true;
                axios.post('/compra/entregado/'+this.modal.compraEntrega.id).then((response)=>{
                    $.notify({
                        title: 'Guardado',
                        message: ''
                    },{
                        type:'success',
                    });
                    this.modal.compraEntrega.estadoStr = 'entregado';
                    $('#modalConfirmarEntrega').modal('hide');
                }).catch((error)=>{
                    console.log(error);
                    $.notify({title:this.$printJson(error.response.data.message)});
                }).finally(()=>{
                    this.cargando = false;
                });
            }
        }
    }
</script>
