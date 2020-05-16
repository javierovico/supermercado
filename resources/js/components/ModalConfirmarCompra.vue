<template>
    <!-- Modal -->
    <div class="modal fade bd-example-modal-xl" id="modalConfirmarCompra" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">
                        Proceder a la compra de estos articulos?
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
                        <p>Precio Total: {{$precio(modal.precio)}}</p>
                    </template>
                    <div  id="iframe-container"/>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button @click.prevent="confirmarBorrar" class="btn btn-primary" type="button" :disabled="cargando">
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
            confirmarBorrar(){
                this.cargando = true;
                axios.post('/compra/confirmar',{
                    compraId: this.modal.compraId,
                }).then((response)=>{
                    const processId = response.data.process_id;
                    const styles = {
                        'input-background-color': '#453454',
                        'input-text-color': '#B22222',
                        'input-border-color': '#CCCCCC',
                        'input-placeholder-color': '#999999',
                        'button-background-color': '#5CB85C',
                        'button-text-color': '#FFFFFF',
                        'button-border-color': '#4CAE4C',
                        'form-background-color': '#999999',
                        'form-border-color': '#DDDDDD',
                        'header-background-color': '#F5F5F5',
                        'header-text-color': '#333333',
                        'hr-border-color': '#B22222'
                    };
                    const options = {
                        styles: styles
                    }
                    Bancard.Checkout.createForm('iframe-container', processId, {});
                    console.log(processId);
                }).catch((error)=>{
                    console.log(error);
                    $.notify({title:this.$printJson(error.response.data.message)});
                }).finally(()=>{
                    this.cargando = false;
                });

                // setTimeout(()=>{
                //     this.cargando = false;
                //     $('#modalConfirmarCompra').modal('hide');
                //     this.$emit('pagoConfirmado');
                // },3000);

                // axios.post('/compra/'+this.modal.productoBorrando.pivot.id)
                //     .then((response) => {
                //         $('#modalConfirmarCompra').modal('hide');
                //         this.$emit('borrado',this.modal.index);
                //     }).catch((error)=>{
                //         $.notify({message:error.response.data.message});
                //     }).finally(()=>{
                //         this.cargando=false;
                //     });
            }
        }
    }
</script>
