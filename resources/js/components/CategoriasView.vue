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
                            <a @click.prevent="desapilar(index)" href="#">
                                {{catAnt.nombre}}
                            </a>
                        </li>
                        <li v-if="categoriasAnteriores.length>0" class="breadcrumb-item active" aria-current="page">
                            {{categoriasAnteriores[categoriasAnteriores.length-1].nombre}}
                        </li>
                    </ol>
                </nav>
                <ul class="list-group">
                    <a href="#!"
                       @click.prevent="cambiarCategoria(index)"
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
                    <lista-productos-sel
                        :_categoriaId="idPadre"
                        :_actualizar="actualizarProductosSel"
                    ></lista-productos-sel>
                </div>
                <div class="col-md-12 mt-4">
                    <!--                    Inicio de titulo-->
                    <nav class="navbar navbar-expand-lg navbar-light" style="background-color: #e3f2fd">
                        <a class="navbar-brand" href="#!">
                            Buscar Producto para agregar
                        </a>
                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarNavDropdown">
                            <ul class="navbar-nav">
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Seleccion
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                        <a :class="(resultadoBusquedaProducto.length>0?'':' disabled')"
                                           v-on:click.prevent="resultadoBusquedaProducto.length>0 && seleccionarTodoBusqueda()" class="dropdown-item" href="#">
                                            {{resultadoBusquedaProducto.length>0?'Seleccionar Todo':'Ninguno seleccionado'}}
                                        </a>
                                    </div>
                                </li>
                            </ul>
                            <form class="form-inline my-2 my-lg-0" v-on:submit.prevent="paginaActual = 1;buscarProducto()">
                                <input v-model="productoBuscado" class="form-control mr-sm-2" type="search" placeholder="Busqueda..." aria-label="Buscar">
                                <a href="#!"><i class="material-icons prefix white-text">search</i></a>
                            </form>
                            <form v-if="resultadoBusquedaProducto.length>0" class="form-inline my-2 my-lg-0 ml-3">
                                <a  v-on:click.prevent="enviarResultados"
                                    href="#!"><i class="material-icons prefix white-text">send</i></a>
                            </form>
                        </div>
                    </nav>
                    <!--                    Fin de titulo-->
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
                actualizarProductosSel: 0,  //para detectar los cambios nada mas
            }
        },

        mounted() {
            console.log('Creado con _idPadre: '+ this._idPadre+ ' Titulo '+this._tituloPadre);
            this.idPadre = this._idPadre;
            this.leer((this._tituloPadre)?this._idPadre:'Categorias',this._idPadre);
        },

        methods: {
            cambiarCategoria(index){
                this.productoBuscado = '';
                this.buscarProducto();
                const categoria = this.categorias[index];
                console.log('cambiando categoria a id:');
                this.leer(categoria.nombre,categoria.id);
            },
            leer(nombre, id){
                axios.get('/categoria/catSel',{params:{categoria_id:id}}).then((response) => {
                    this.categoriasAnteriores.push({nombre:nombre,id:id});
                    this.categorias = response.data.data;
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
                    palabra_clave:this.productoBuscado.length>0?this.productoBuscado:null,
                    categoria_match: this.getCategoriaIdActual(),   //solo para que seleccione
                    page: this.paginaActual,
                    cantidad: this.paginaCantidadItem
                }}).then((response) => {
                    this.resultadoBusquedaProducto = response.data.data;
                    this.paginaTotal = response.data.last_page;
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
                        this.actualizarProductosSel++;
                }).catch((error)=>{
                    alert('No tenes permisos');
                    console.log(error);
                });
                console.log(valorEnviar,this.getCategoriaIdActual());
            },
            seleccionarTodoBusqueda(){
                this.resultadoBusquedaProducto.forEach(function(part, index, theArray) {
                    theArray[index].seleccionado = true;
                });
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
