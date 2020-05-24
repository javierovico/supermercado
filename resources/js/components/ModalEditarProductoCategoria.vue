<template>
    <!-- Modal -->
    <div class="modal fade bd-example-modal-xl" id="modalEditarProductoCategoria" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 v-if="!cargando" class="modal-title" id="exampleModalLongTitle">
                        Editar Categoria para {{producto?producto.nombre:'ERROR'}}
                    </h5>
                    <h5 v-else class="modal-title" id="exampleModalLongTitle">
                        Cargando informacion...
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
                    <template v-else>
                        <blockquote class="blockquote text-center">
                            <p class="mb-0">Categorias actuales</p>
                        </blockquote>
                        <ul class="list-group">
                            <li v-for="(categoria,index) in categorias" class="list-group-item d-flex justify-content-between align-items-center">
                                {{categoria.nombre}}
                                <a @click="borrarCategoria(index)" href="#!"><i class="material-icons">delete</i></a>
                            </li>
                        </ul>

                        <div class="col-md-12 mt-4">
                            <!--                    Inicio de titulo-->
                            <nav class="navbar navbar-expand-lg navbar-light" style="background-color: #e3f2fd">
                                <a class="navbar-brand" href="#!">
                                    Buscar Categoria para agregar
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
                                                <a :class="(busqueda.resultadoBusqueda.length>0?'':' disabled')"
                                                   v-on:click.prevent="busqueda.resultadoBusqueda.length>0 && seleccionarTodoBusqueda()" class="dropdown-item" href="#">
                                                    {{busqueda.resultadoBusqueda.length>0?'Seleccionar Todo':'Ninguno seleccionado'}}
                                                </a>
                                            </div>
                                        </li>
                                    </ul>
                                    <form class="form-inline my-2 my-lg-0" v-on:submit.prevent="prepararBusqueda">
                                        <input v-model="busqueda.buscarCategoria" class="form-control mr-sm-2" type="search" placeholder="Busqueda..." aria-label="Buscar">
                                        <button class="btn btn-link" type="submit"><i class="material-icons prefix white-text">search</i></button>
                                    </form>
                                    <form v-if="busqueda.resultadoBusqueda.length>0" class="form-inline my-2 my-lg-0 ml-3">
                                        <a  v-on:click.prevent="enviarResultados"
                                            href="#!"><i class="material-icons prefix white-text">send</i></a>
                                    </form>
                                </div>
                            </nav>

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
                                    <li v-if="categoriasAnteriores.length>0" class="breadcrumb-item active" aria-current="page">
                                        {{categoriasAnteriores[categoriasAnteriores.length-1].nombre}}
                                    </li>
                                </ol>
                            </nav>
                            <!--                    Fin de titulo-->
                        </div>
                        <div class="col-md-12">
                            <div class="container">
                                <div class="row">
                                    <div
                                        v-for="(categoria,index) in busqueda.resultadoBusqueda"
                                        :key="index"
                                        class="col-12 col-sm-6 col-md-4 col-lg-4 col-xl-3 border border-primary">
                                        <div class="col-auto my-1">
                                            <div class="custom-control custom-checkbox mr-sm-2">
                                                <label class="form-check-label" >
                                                    <input v-model="categoria.seleccionado" class="form-check-input" type="checkbox" id="defaultCheck1">
                                                    {{categoria.nombre}}
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-auto my-1">
                                            <a v-on:click="apilar(categoria)" href="#!"><i class="material-icons prefix white-text">folder_open</i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <vista-paginacion
                            :actual="paginaActual"
                            :total="paginaTotal"
                            :slidingEndingSize="10"
                            @paginacionClick="paginacionClick"
                        ></vista-paginacion>
                    </template>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
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
                cargando: true,
                categorias: [], //las categorias disponibles
                busqueda:{
                    buscarCategoria: '',
                    resultadoBusqueda: [],
                    categoriaPadreId: null,
                },
                paginaActual: 1,
                paginaTotal: 1,
                categoriasAnteriores: [{nombre:'Principal',id:null}],

            }
        },
        watch:{
            producto: function(){
                if(this.producto != null){
                    this.cargarCategorias();
                }
                this.inicializar();
            },
        },

        mounted() {
            if(this.producto){
                this.cargarCategorias();
            }
        },

        methods: {
            inicializar(){
                this.busqueda.resultadoBusqueda = [];
                this.busqueda.buscarCategoria = '';
                this.busqueda.categoriaPadreId = null;
                this.categoriasAnteriores = [{nombre:'Principal',id:null}];
                this.buscarCategoria();
            },
            prepararBusqueda(){
                this.categoriasAnteriores = [{nombre:'Busqueda',id:null}];
                this.busqueda.categoriaPadreId = null;
                this.buscarCategoria();
            },
            apilar(categoria){
                this.busqueda.categoriaPadreId=categoria.id;
                this.busqueda.buscarCategoria = '';
                this.categoriasAnteriores.push({
                    nombre: categoria.nombre,
                    id: categoria.id,
                });
                this.buscarCategoria();
            },
            desapilar(index){
                if(index+1 === this.categoriasAnteriores.length){
                    return;
                }
                let antes = this.categoriasAnteriores[index];
                this.categoriasAnteriores.splice(index+1,this.categoriasAnteriores.length);
                this.busqueda.categoriaPadreId = antes.id;
                this.buscarCategoria();
            },
            paginacionClick(n){
                this.paginaActual = n;
                this.buscarProducto();
            },
            enviarResultados(){
                const valorEnviar = [];
                this.busqueda.resultadoBusqueda.forEach(function (e) {
                    if(typeof e.seleccionado === "boolean"){
                        valorEnviar.push({categoriaId:e.id, valor:e.seleccionado});
                    }
                });
                this.cargando = true;
                axios.post('/producto/updateCategoriasList/'+this.producto.id,{
                    categorias: valorEnviar,
                }).then((response) => {
                    this.producto.cant_categorias += response.data.data.variacion;
                    this.cargarCategorias();
                }).catch((error)=>{
                    if(typeof error.response.data.message != "undefined"){
                        alert(error.response.data.message);
                    }else{
                        alert('error: '+JSON.stringify(error.response.data));
                    }
                    console.log(error);
                }).finally(()=>{
                    this.cargando = false;
                });
            },
            seleccionarTodoBusqueda(){
                this.busqueda.resultadoBusqueda.forEach(function(part, index, theArray) {
                    theArray[index].seleccionado = true;
                });
            },
            buscarCategoria(){
                console.log('Buscando ',this.busqueda.buscarCategoria);
                this.cargando= true;
                this.paginaActual = 1;
                const modoBusqueda = this.busqueda.buscarCategoria.length>0;
                const modoRaiz = this.busqueda.categoriaPadreId == null;
                axios.get('/categoria',{params:{
                    palabra_clave: modoBusqueda?this.busqueda.buscarCategoria:null,
                    producto_match: this.producto.id,
                    categoria_id: (!modoRaiz || modoBusqueda)?this.busqueda.categoriaPadreId:'0',
                }}).then((response)=>{
                    this.busqueda.resultadoBusqueda = response.data.data;
                }).catch((error)=>{
                    this.busqueda.resultadoBusqueda = [];
                    if(typeof error.response.data.message != "undefined"){
                        alert(error.response.data.message);
                    }else{
                        alert('error: '+JSON.stringify(error.response.data));
                    }
                }).finally(()=>{
                    this.cargando = false;
                });
            },
            guardarProducto(){
                this.cargando = true;
                setTimeout(()=>{
                    this.cargando = false;
                },4000);
            },
            cargarCategorias(){ //carga las categorias a las cuales pertenece ese producto actual
                this.cargando = true;
                this.categorias = [];
                axios.get('/categoria/byProducto/'+this.producto.id).then((response)=>{
                    this.categorias.push(...response.data.data);
                }).catch((error)=>{
                    this.categorias = [];
                    if(typeof error.response.data.message != "undefined"){
                        alert(error.response.data.message);
                    }else{
                        alert('error: '+JSON.stringify(error.response.data));
                    }
                }).finally(()=>{
                    this.cargando = false;
                });
            },
            borrarCategoria(n){
                this.cargando = true;
                axios.post('/producto/updateCategoriasList/'+this.producto.id,{
                    categorias:[
                        {
                            categoriaId:this.categorias[n].id,
                            valor:false
                        }
                    ]
                }).then((response)=>{
                    this.categorias.splice(n,1);
                    this.producto.cant_categorias--;
                    this.buscarCategoria();
                }).catch((error)=>{
                    if(typeof error.response.data.message != "undefined"){
                        alert(error.response.data.message);
                    }else{
                        alert('error: '+JSON.stringify(error.response.data));
                    }
                }).finally(()=>{
                    this.cargando = false;
                });
            },
        }
    }
</script>
