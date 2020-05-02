<template>
<!--    <p>{{categoriaId}}</p>-->
    <ul class="'collapsible popout">
        <li
            :data-index="index"
            v-for="(categoria, index) in categorias"
            :key="categoria.id">
            <div class="collapsible-header"><i class="material-icons">filter_drama</i>{{categoria.nombre}}</div>
            <div class="collapsible-body">
                <item-categoria
                    :ref="categoria.id"
                    :categoriaId="categoria.id">
                </item-categoria>
            </div>
        </li>
    </ul>
</template>

<script>

    // <img class="activator" :src="urlBase+((thought.thumbnail)?('productos/'+thought.thumbnail):'img/producto.jpg')">
    export default {
        props: ['categoriaId'],
        data() {
            return {
                editMode: false,
                urlBase: urlBase +'/',
                categorias: []
            };
        },
        mounted() {

            axios.get('/categoria',{params:{categoria_id:this.categoriaId}}).then((response) => {
                this.categorias = response.data;
            });
            // let vueI = this;
            // $(document).ready(function(){
            //     $('.collapsible-'+this.categoriaId).collapsible({
            //         onOpenStart:function (e) {
            //             let id = $(e).attr('data-index');
            //             console.log('item '+id);
            //             // vueI.$children[id].cargar();
            //         }
            //     })
            // });
        },
        methods: {
            cargar(){
                console.log('cargar cate Item '+this.categoriaId);
                if(this.categorias.length === 0){
                    axios.get('/categoria',{params:{categoria_id:this.categoriaId}}).then((response) => {
                        this.categorias = response.data;
                    });
                }
            },
            onClickDelete() {
                axios.delete(`/thoughts/${this.thought.id}`).then(() => {
                    this.$emit('delete');
                });
            },
            onClickEdit() {
                this.editMode = true;
            },
            onClickUpdate() {
                const params = {
                    nombre: this.thought.nombre
                };
                axios.put(`/thoughts/${this.thought.id}`, params).then((response) => {
                    this.editMode = false;
                    const thought = response.data;
                    this.$emit('update', thought);
                });
            }
        }
    }
</script>
