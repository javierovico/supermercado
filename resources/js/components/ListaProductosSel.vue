<template>
    <div>
        <!--                    Inicio de titulo-->
        <nav class="navbar navbar-expand-lg navbar-light" style="background-color: #e3f2fd">
            <a class="navbar-brand" href="#!">Productos de la Cat</a>
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
                            <a class="dropdown-item disabled" href="#">Eliminar</a>
                        </div>
                    </li>
                </ul>
                <form class="form-inline my-2 my-lg-0" v-on:submit.prevent="buscarProducto">
                    <input v-on:change.prevent="buscarProducto" v-model="busqueda.buscarProducto" class="form-control mr-sm-2" type="search" placeholder="Busqueda..." aria-label="Buscar">
                    <a href="#!"><i class="material-icons prefix white-text">search</i></a>
                </form>
            </div>
        </nav>
        <!--                    Fin de titulo-->
        <div v-show="productos.length>0" class="col-md-12 mt-2">
            <ul class="list-group">
                <li
                    v-for="(producto,index) in productos"
                    :key="index"
                    class="list-group-item">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" :id="'check'+index">
                            <label class="form-check-label" :for="'check'+index">
                                {{producto.nombre}}
                            </label>
                        </div>
                    </li>
            </ul>

            <vista-paginacion
                :total="paginaTotal"
                :actual="paginaActual"
                slidingEndingSize="5"
                @paginacionClick="paginacionClick"
            >
            </vista-paginacion>
    <!--        Modal para editar o agregar-->
            <modal-editar-producto
                @cambiosGuardados="guardarCambios"
                :modalEditar="modalEditar"
            >
            </modal-editar-producto>
            <modal-editar-producto-thumbnail
                :producto="modalThumbnail.producto"
            >
            </modal-editar-producto-thumbnail>
        </div>
    </div>
</template>
<!--@click="llamar(categoria.id)" waves-effect  70 76  7 7   70 80  7 8    70 81 7 8-->
<script>
    import PaginationView from "./PaginationView";
    export default {
        components: {PaginationView},
        props:['_categoriaId','_actualizar'],     //si recibimos una categoria, solo los productos de esa categoria, sino todos los productos
        data() {
            return {
                categoriaId:null,
                productos: [],
                paginaActual: 1,
                paginaTotal: 1,
                paginaCantidadItem: 10,
                busqueda:{
                    buscarProducto: '',
                    mostrar: false,//para saber si se muestra o no el boton de busqeuda con el texto
                } ,
                modalEditar:{
                    indexEditando: -1,
                    productoEditando: {
                        precio_costo:0,
                        precio_mayorista: 0,
                        stock: 0,
                        descuento:0,
                        precio: 0,
                        codigo: 0,
                        nombre: '',
                        impuesto: 10,
                        tipo_medida_producto_id: "",
                    },
                },
                modalBorrar:{
                    indexBorrando: -1,
                },
                modalThumbnail:{
                    producto:{
                        precio_costo:0,
                        precio_mayorista: 0,
                        stock: 0,
                        descuento:0,
                        precio: 0,
                        codigo: 0,
                        nombre: '',
                        impuesto: 10,
                        tipo_medida_producto_id: "",
                    }
                }
            }
        },
        watch:{
            _categoriaId: function () {
                this.leer(this._categoriaId)
            },
            _actualizar: function () {
                this.leer(this._categoriaId)
            }
        },
        mounted() {
            console.log('Visor de productos iniciado con categoriaId = '+this._categoriaId);
            this.leer(this._categoriaId);
        },

        methods: {
            confirmarThumbnail(){
                console.log(this.modalThumbnail.file);
                const formData = new FormData();
                formData.append("thumbnail", this.modalThumbnail.file);
                axios.post('/producto/thumbnail/'+this.productos[this.modalThumbnail.index].id, formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }).then((response)=>{
                    $('.modal').modal('close')
                }).catch((error)=>{
                    alert(error.response.data.errors.thumbnail[0]);
                });
            },
            processFile(event){
                this.modalThumbnail.file = event.target.files[0]
            },
            editarThumbnail(indice){
                this.modalThumbnail.producto = this.productos[indice];
                $('#modalEditarProductoThumbnail').modal();
            },
            confirmarBorrar(){
                axios.delete('/producto/'+this.productos[this.modalBorrar.indexBorrando].id)
                    .then((response) => {
                        console.log(response);
                        this.productos.splice(this.modalBorrar.indexBorrando,1);
                        $('.modal').modal('close')
                    }).catch((error)=>{
                        console.log(error);
                        alert(error.response.data.message);
                    });
            },
            borrarProducto(indice){
                console.log('borrando producto '+indice);
                this.modalBorrar.indexBorrando = indice;
                $('#modalBorrar').modal('open')
            },
            guardarCambios(){
                this.productos.splice(this.modalEditar.indexEditando,1,this.modalEditar.productoEditando);
            },
            ponerNumeros(){
                let num = this.modalEditar.productoEditando.precio.replace(/\./g,'');
                if(!isNaN(num)){
                    num = num.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1.');
                    num = num.split('').reverse().join('').replace(/^[\.]/,'');
                    this.modalEditar.productoEditando.precio = num;
                } else{ alert('Solo se permiten numeros');
                    this.modalEditar.productoEditando.precio = this.modalEditar.productoEditando.precio.replace(/[^\d\.]*/g,'');
                }
            },
            editarProducto(indice){
                console.log('editando producto '+indice);
                this.modalEditar.indexEditando = indice;
                this.modalEditar.productoEditando = JSON.parse(JSON.stringify(this.productos[indice]));
                $('#modalEditarProducto').modal()
            },
            buscarProducto(){
                console.log('buscar '+this.busqueda.buscarProducto);
                axios.get('/producto',{params:{
                        palabra_clave:this.busqueda.buscarProducto,
                        categoria_id: this.categoriaId,
                        page: this.paginaActual,
                        cantidad: this.paginaCantidadItem
                    }}).then((response) => {
                        this.productos = response.data.data;
                        this.paginaTotal = response.data.last_page;
                    });
            },
            leer(categoriaId){
                axios.get('/producto',{params:{
                    categoria_id:categoriaId,
                    page: this.paginaActual,
                    cantidad: this.paginaCantidadItem
                }}).then((response) => {
                    this.productos = response.data.data;
                    this.paginaTotal = response.data.last_page;
                }).catch((error)=>{
                    console.log(error);
                    alert(error.toString());
                });
                this.categoriaId = categoriaId;
            },
            paginacionClick(n){
                this.paginaActual = n;
                if(this.busqueda.buscarProducto.length > 0){
                    this.buscarProducto();
                }else{
                    this.leer(this.categoriaId);
                }
            },
        }
    }
</script>
