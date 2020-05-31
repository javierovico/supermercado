<template>

    <div class="col-md-12">
        <table class="table">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Fecha</th>
                <th scope="col">Nombre</th>
                <th scope="col">Correo</th>
                <th scope="col">Asunto</th>
                <th scope="col">Mensaje</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(sugerencia,index) in sugerencias">
                <th scope="row">{{index+1}}</th>
                <td>{{$intToFecha(sugerencia.updated_at)}}</td>
                <td>{{sugerencia.nombre}}</td>
                <td>{{sugerencia.email}}</td>
                <td>{{sugerencia.asunto}}</td>
                <td>{{sugerencia.mensaje}}</td>
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
        name: "SuregenciasBox",
        mounted() {
            axios.get('/sugerencia').then((response)=>{
                this.sugerencias = response.data.data;
            }).catch((error)=>{
                $.notify({
                    title:error.response.data.message,
                    message:''
                })
            }).finally(()=>{

            });
        },
        data(){
            return {
                sugerencias: [],
                paginaActual: 1,
                paginaTotal: 1,
                paginaCantidadItem:20,
            }
        },
        methods:{
            paginacionClick(n){
                this.paginaActual = n;
                this.leer();
            },
        }
    }
</script>

<style scoped>

</style>
