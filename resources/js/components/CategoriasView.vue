<template>
    <ul class="collapsible popout">
        <li
            :data-index="index"
             v-for="(categoria, index) in categorias"
             :key="categoria.id">
            <div class="collapsible-header"><i class="material-icons">filter_drama</i>{{categoria.nombre}}</div>
            <div class="collapsible-body">
                <item-categoria
                    :ref="categoria.id"
                    :categoriaId="categoria.id"
                    @update="updateCategoria(index, ...arguments)"
                    @delete="deleteCategoria(index)">
                </item-categoria>
            </div>
        </li>
    </ul>
</template>
<!--@click="llamar(categoria.id)"-->
<script>
    export default {
        data() {
            return {
                categorias: []
            }
        },

        mounted() {
            axios.get('/categoria').then((response) => {
                this.categorias = response.data;
            });
            let vueI = this;
            $(document).ready(function(){
                $('.collapsible').collapsible();//{
                    // onOpenStart:function (e) {
                    //     let id = $(e).attr('data-index');
                    //     console.log('abierto '+id);
                    //     vueI.$children[id].cargar();
                    // }
                // })
            });
        },

        methods: {
            llamar(id){
                console.log(id);
            },
            addCategoria(thought) {
                this.categorias.push(thought);
            },
            updateCategoria(index, thought) {
                this.categorias[index] = thought;
            },
            deleteCategoria(index) {
                this.categorias.splice(index, 1);
            }
        }
    }
</script>
