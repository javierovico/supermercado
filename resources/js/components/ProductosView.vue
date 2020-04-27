<template>
    <div class="row">
        <lista-productos
            :_categoriaId="null"
        ></lista-productos>
    </div>
</template>

<script>
    export default {
        data() {
            return {
            }
        },

        mounted() {
            // axios.get('/producto').then((response) => {
            //     this.thoughts = response.data;
            // });
        },

        methods: {

        }
    }
</script>
