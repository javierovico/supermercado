<template>
    <!-- Modal -->
    <div class="modal fade bd-example-modal-xl" id="modalCalificarEntrega" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">
                        Calificar Experiencia de compra
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
                        <form>
                            <p class="clasificacion">
                                <template v-for="index in 5">
                                    <input :id="'radio'+index" type="radio" name="estrellas" v-model="estrellas" :value="6-index">
                                    <label :for="'radio'+index">★</label>
                                </template>
<!--                                <input id="radio2" type="radio" name="estrellas" value="4">-->
<!--                                <label for="radio2">★</label>-->
<!--                                <input id="radio3" type="radio" name="estrellas" value="3">-->
<!--                                <label for="radio3">★</label>-->
<!--                                <input id="radio4" type="radio" name="estrellas" value="2">-->
<!--                                <label for="radio4">★</label>-->
<!--                                <input id="radio5" type="radio" name="estrellas" value="1">-->
<!--                                <label for="radio5">★</label>-->
                            </p>
                        </form>
                    </template>
                    <div  id="iframe-container"/>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button @click.prevent="confirmarCalificacion" class="btn btn-primary" type="button" :disabled="cargando">
                        <span v-if="cargando" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        {{!cargando?'Enviar':'Enviando'}}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props:['modal','index'],
        data() {
            return {
                cargando: false,
                estrellas:0
            }
        },
        watch:{
            index: function () {
                this.cargando = false;
                this.estrellas = 0;
            }
        },
        methods: {
            confirmarCalificacion(){
                if(this.estrellas < 1){
                    $.notify({
                        title: 'No completaste el campo',
                        message: '',
                    });
                    return;
                }
                this.cargando = true;
                axios.post('/compra/calificar/'+this.modal.compraCalificar.id,{
                    estrellas: this.estrellas
                }).then((response)=>{
                    $.notify({
                        title: 'Guardado',
                        message: ''
                    },{
                        type:'success',
                    });
                    $('#modalCalificarEntrega').modal('hide');
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
<style>
    .clasificacion label{
        color: gray;
        font-size: 50px;
    }
    .clasificacion input[type= "radio"]{
        display: none;
    }
    .clasificacion{
        direction: rtl;/* right to left */
        unicode-bidi: bidi-override;/* bidi de bidireccional */
    }
    .clasificacion label:hover{
        color:orange;
    }
    .clasificacion label:hover ~ label{color:orange;}
    .clasificacion input[type = "radio"]:checked ~ label{color:orange;}
</style>
