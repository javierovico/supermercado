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
                        <p>Precio Productos: {{$precio(modal.precio)}}</p>
                        <p>Precio Delivery: {{$precio($store.getters.pagoDelivery)}}</p>
                        <p>Precio Total: {{$precio(modal.precio + $store.getters.pagoDelivery)}}</p>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <label class="input-group-text" for="inputGroupSelect01">Metodo pago</label>
                            </div>
                            <select v-model="metodoPago" class="custom-select" id="inputGroupSelect01">
                                <option disabled value="">Selecciona...</option>
                                <option value="1">POST Online</option>
                                <option value="2">Tarjeta Credito/Debito contraentrega</option>
                                <option value="3">Efectivo contraentrega</option>
                            </select>
                        </div>
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
                metodoPago: ''
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
                if(this.metodoPago=='' || this.metodoPago < 1){
                    $.notify({title:'Debe seleccionar un metodo de pago',content:''});
                    return;
                }
                axios.post('/compra/confirmar',{
                    compraId: this.modal.compraId,
                    metodo: this.metodoPago,
                }).then((response)=>{
                    switch(this.metodoPago){
                        case '1':
                        case 1:
                            this.mostrarBancard(response.data.process_id);
                            break;
                        case 2:
                        case 3:
                        case '2':
                        case '3':
                            $('#modalConfirmarCompra').modal('hide');
                            this.$router.push('/carrito/historial');
                            break;
                    }
                }).catch((error)=>{
                    console.log(error);
                    $.notify({title:this.$printJson(error.response.data.message)});
                }).finally(()=>{
                    this.cargando = false;
                });
            },
            mostrarBancard(processId){
                Bancard.Checkout.createForm('iframe-container', processId, {});
            }
        }
    }
</script>
