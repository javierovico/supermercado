<template>
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li
                            v-for="(catAnt, index) in categoriasAnteriores"
                            v-if="(index+1)<categoriasAnteriores.length"
                            :key="index"
                            class="breadcrumb-item">
                            <a @click="desapilar(index)" href="#">
                                {{catAnt.nombre}}
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            {{categoriasAnteriores[categoriasAnteriores.length-1].nombre}}
                        </li>
                    </ol>
                </nav>
                <ul class="list-group">
                    <a href="#!"
                       @click="cambiarCategoria(index)"
                       :data-index="index"
                       v-for="(categoria, index) in categorias"
                       :key="categoria.id"
                       class="list-group-item d-flex justify-content-between align-items-center">
                        {{categoria.nombre}}
                        <span class="badge badge-primary badge-pill">{{(categoria.cant_sub_cat>0)?categoria.cant_sub_cat+' subcat'+(categoria.cant_prod>0?', ':''):''}}{{(categoria.cant_prod > 0)?categoria.cant_prod+' prod':'' }}</span>
                    </a>
                </ul>
            </div>
            <template v-if="categoriasAnteriores.length>1">
                <div class="col-md-6">
                    <h4>Productos pertenecientes a la categoria {{categoriasAnteriores[categoriasAnteriores.length-1].nombre}}</h4>
                    <lista-productos
                        :_categoriaId="idPadre"
                    ></lista-productos>
                </div>
                <h3>Buscar producto para agregar o quitar de la categoria</h3>
                <div class="col-md-12">
                    <form class="form-inline my-2 my-lg-0" v-on:submit.prevent="buscarProducto">
                        <input v-on:change.prevent="buscarProducto" v-model="productoBuscado" class="form-control mr-sm-2" type="search" placeholder="Busqueda..." aria-label="Buscar">
                        <button class="btn btn-success my-2 my-sm-0" type="submit">Buscar</button>
                        <button
                            v-if="resultadoBusquedaProducto.length>0"
                            @click="enviarResultados()"
                            type="button" class="btn btn-outline-primary">
                            Guardar
                        </button>
                    </form>
                </div>
                <div class="col-md-12">
                    <div class="container">
                        <div class="row">
                            <div
                                v-for="(producto,index) in resultadoBusquedaProducto"
                                :key="index"
                                class="list-group-item col-md-5">
                                <div class="form-check">
                                    <label class="form-check-label" >
                                        <input v-model="producto.seleccionado" class="form-check-input" type="checkbox" id="defaultCheck1">
                                        {{producto.nombre}}
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <pagination-view
                    :actual="paginaActual"
                    :total="paginaTotal"
                    :slidingEndingSize="10"
                    @paginacionClick="paginacionClick"
                ></pagination-view>
            </template>
        </div>
    </div>
</template>
<!--@click="llamar(categoria.id)" waves-effect  70 76  7 7   70 80  7 8    70 81 7 8-->
<script>

    import PaginationView from "./PaginationView";

    export default {
        components: {PaginationView},
        props:['_idPadre','_tituloPadre'],
        data() {
            return {
                idPadre:null,
                categorias: [],
                productos: [],
                categoriasAnteriores: [],   //[{nombre:,id:}]
                productoBuscado: '',
                resultadoBusquedaProducto: [],
                paginaActual: 1,
                paginaTotal: 1,
                paginaCantidadItem: 20,
            }
        },

        mounted() {
            console.log('Creado con _idPadre: '+ this._idPadre+ ' Titulo '+this._tituloPadre);
            this.idPadre = this._idPadre;
            this.leer((this._tituloPadre)?this._idPadre:'Categorias',this._idPadre);
        },

        methods: {
            cambiarCategoria(index){
                const categoria = this.categorias[index];
                console.log('cambiando categoria a id:');
                this.leer(categoria.nombre,categoria.id);
            },
            leer(nombre, id){
                axios.get('/categoria',{params:{categoria_id:id}}).then((response) => {
                    this.categoriasAnteriores.push({nombre:nombre,id:id});
                    this.categorias = response.data;
                    this.idPadre = id;
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
            buscarProducto(){
                axios.get('/producto',{params:{
                    busqueda:this.productoBuscado,
                    categoria_id: this.getCategoriaIdActual(),
                    limite_inferior: (this.paginaActual-1)*this.paginaCantidadItem,
                    cantidad: this.paginaCantidadItem
                }}).then((response) => {
                    this.resultadoBusquedaProducto = response.data.productos;
                    this.paginaTotal = Math.ceil(parseInt(response.data.cantidad)/this.paginaCantidadItem);
                });
            },
            getCategoriaIdActual(){
                return (this.categoriasAnteriores.length > 0)?(this.categoriasAnteriores[this.categoriasAnteriores.length-1].id):null;
            },
            paginacionClick(n){
                this.paginaActual = n;
                this.buscarProducto();
            },
            enviarResultados(){
                const valorEnviar = [];
                this.resultadoBusquedaProducto.forEach(function (e) {
                    if(typeof e.seleccionado === "boolean"){
                        valorEnviar.push({id:e.id, valor:e.seleccionado});
                    }
                });
                axios.post('/categoria/updateProductosList',{
                        lista: JSON.stringify(valorEnviar),
                        categoria: this.getCategoriaIdActual()
                    }).then((response) => {
                        console.log('guardado');
                }).catch((error)=>{
                    alert('No tenes permisos');
                    console.log(error);
                });
                console.log(valorEnviar,this.getCategoriaIdActual());
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
