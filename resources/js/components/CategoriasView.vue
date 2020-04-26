<template>
    <ul class="collection with-header">
        <li class="collection-header">
            <nav>
                <div class="nav-wrapper">
                    <div class="col s12">
                        <a
                            v-for="(catAnt, index) in categoriasAnteriores"
                            :key="index"
                            @click="desapilar(index)"
                            href="#!" class="breadcrumb">
                            {{catAnt.nombre}}
                        </a>
                    </div>
                </div>
            </nav>
        </li>
        <a
            :data-index="index"
            v-for="(categoria, index) in categorias"
            :key="categoria.id"
            href="#!"
            @click="cambiarCategoria(index)"
            class="collection-item">
            <div>
                {{categoria.nombre}}
                <a href="#!" class="secondary-content">
                    <i class="material-icons">send</i>
                </a>
            </div>
        </a>
    </ul>
</template>
<!--@click="llamar(categoria.id)"-->
<script>
    export default {
        props:['_idPadre','_tituloPadre'],
        data() {
            return {
                categorias: [],
                // padreId: null,
                categoriasAnteriores: [],   //[{nombre:,id:}]
            }
        },

        mounted() {
            console.log('Creado con _idPadre: '+ this._idPadre+ ' Titulo '+this._tituloPadre);
            this.leer((this._tituloPadre)?this._idPadre:'Categoria',this._idPadre);
            // this.padreId = this._idPadre;
            // this.leer('Categorias',null);
        },

        methods: {
            cambiarCategoria(index){
                const categoria = this.categorias[index];
                console.log('cambiando categoria a id:');
                this.leer(categoria.nombre,categoria.id);
                // this.padreId = id;
                // this.leer();
            },
            leer(nombre, id){
                axios.get('/categoria',{params:{categoria_id:id}}).then((response) => {
                    this.categoriasAnteriores.push({nombre:nombre,id:id});
                    this.categorias = response.data;
                });
            },
            desapilar(index){
                if(index+1 === this.categoriasAnteriores.length){
                    return;
                }
                let antes = this.categoriasAnteriores[index];
                this.categoriasAnteriores.splice(index,this.categoriasAnteriores.length);
                this.leer(antes.nombre,antes.id);
            },
            llamarHijo(id){
                this.$children[id].cargar();
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
