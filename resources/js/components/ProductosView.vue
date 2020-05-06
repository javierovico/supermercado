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
            // $(document).ready(function(){
            //     $('.fixed-action-btn').floatingActionButton();
            // });
            // document.addEventListener('DOMContentLoaded', function() {
            //     var elems = document.querySelectorAll('.fixed-action-btn');
            //     var instances = M.FloatingActionButton.init(elems, {
            //         direction: 'left',
            //         hoverEnabled: false
            //     });
            // });
            // axios.get('/producto').then((response) => {
            //     this.thoughts = response.data;
            // });
        },

        methods: {

        }
    }
</script>
