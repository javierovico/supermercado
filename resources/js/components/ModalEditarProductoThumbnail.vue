<template>
    <!-- Modal -->
    <div class="modal fade bd-example-modal-xl" id="modalEditarProductoThumbnail" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">
                        Miniatura para {{producto.nombre}}
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
                        <img :src="$url+producto.codigo+'.jpg'" v-if="existe" class="embed-responsive embed-responsive-1by1">
                        <div class="custom-file">
                            <input v-on:change.passive="processFile($event)" type="file" class="custom-file-input" id="customFileLang" lang="es">
                            <label class="custom-file-label" for="customFileLang">{{nombreArchivo}}</label>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button v-on:click.prevent="guardarProductoThumbnail" class="btn btn-primary" type="button" :disabled="cargando">
                        <span v-if="cargando" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        {{!cargando?'Guardar':'Guardando'}}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props:['producto'],
        data() {
            return {
                cargando: false,
                file: '',
                existe: false,
                nombreArchivo: 'Seleccione archivo'
            }
        },
        watch:{
            producto: function () {
                this.existe = false;
                this.cargando = true;
                this.file = '';
                this.nombreArchivo = 'Seleccione archivo';
                console.log("Cambio "+this.producto.nombre);
                const response = jQuery.ajax({
                    url: this.$url+this.producto.codigo+'.jpg',
                    async: false
                }).status;
                this.existe = response === 200;
                this.cargando = false;

                // axios.get(this.$url+this.producto.codigo+'.jpg',{
                //     responseType: 'blob'
                // }).then((response)=>{
                //         this.existe = true;
                //     }).catch((error)=>{
                //         console.log(error);
                //         this.existe = false;
                //     }).finally(()=>{
                //         this.cargando = false;
                //     })
            }
        },

        // mounted() {
        //     this.cargando = false;
        // },

        methods: {
            guardarProductoThumbnail(){
                if(this.file === ''){
                    alert('no seleccionaste ningun archivo');
                    return;
                }
                this.cargando = true;
                console.log(this.file);
                const formData = new FormData();
                formData.append("thumbnail", this.file);
                axios.post('/producto/thumbnail/'+this.producto.id, formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }).then((response)=>{
                    $('#modalEditarProductoThumbnail').modal('hide')
                }).catch((error)=>{
                    alert(error.response.data.errors.thumbnail[0]);
                }).finally(()=>{
                    this.file = '';
                    this.cargando = false;
                    this.nombreArchivo = 'Seleccione archivo';
                });
            },
            processFile(event){
                this.file = event.target.files[0];
                this.nombreArchivo = this.file.name;
            },
        }
    }
</script>
