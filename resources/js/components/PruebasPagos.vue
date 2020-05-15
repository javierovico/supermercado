<template>
    <div class="row">

    </div>

</template>

<script>
    export default {
        data() {
            return {
                nada:true,
            }
        },

        mounted() {
            console.log('iniciado');
            let axiosConfig = {
                headers: {
                    'Content-Type': 'application/json;charset=UTF-8',
                    // "Access-Control-Allow-Origin": "*",
                }
            };


            axios.post('https://vpos.infonet.com.py:8888/vpos/api/0.3/single_buy',{
                "public_key": "d3uY6f5jkDBa0AtPGcTeaVyzoQlFlHCH",
                "operation": {
                    "token": "5effd2accd79afa13829013515a0add3"
                }
            },axiosConfig).catch((error)=>{
                console.log(error);
            }).finally(()=>{
                this.cargando = false;
            });
        },

        methods: {

        }
    }
</script>
