<template>
    <div v-show="productos.length>0" class="col-md-12">
        <nav>
            <div class="nav-wrapper">
                <a href="#!" class="brand-logo"><i class="material-icons">storefront</i>Productos</a>
                <ul class="right hide-on-med-and-down">
                    <li v-show="busqueda.mostrar" >
                        <div class="container">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="input-field col-md-12">
                                            <i class="material-icons prefix white-text">search</i>
                                            <input v-on:change="buscarProducto" v-model="busqueda.buscarProducto" type="text" id="autocomplete-input" class="autocomplete white-text">
                                            <label class="white-text" for="autocomplete-input">Busqueda</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li v-show="!busqueda.mostrar"><a href="#!" @click="busqueda.mostrar = true"><i class="material-icons">search</i></a></li>
                </ul>
            </div>
        </nav>
<!--        Empieza la tabla principal-->
        <div class="container">
            <div class="row">
                <div
                    v-for="(producto,index) in productos"
                    :key="index"
                    class="col-md-12 m6 l4 xl4">
                    <div class="card">
                        <div class="card-image waves-effect waves-block waves-light">
                            <img class="activator responsive-img" :src="$url+producto.codigo+'.jpg'">
                        </div>
                        <div class="card-content">
                            <span class="card-title activator grey-text text-darken-4 truncate">{{producto.nombre}}</span>
                            <p><a href="#">Precio: {{producto.precio.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")}} Gs.</a></p>
                        </div>
                        <div class="card-reveal">
                            <span class="card-title grey-text text-darken-4">Detalles<i class="material-icons right">close</i></span>
                            <p>
                                {{producto.nombre}}
                            </p>
                        </div>
                        <div class="card-action">
                            <a href="#">Agregar al carrito</a>
                            <a href="#">Lista de deseos</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<!--        Termina la vista princppa tabla-->
        <pagination-view
            :actual="paginaActual"
            :total="paginaTotal"
            :slidingEndingSize="10"
            @paginacionClick="paginacionClick"
        ></pagination-view>
        <!-- Modal Structure -->
        <div id="modalEditar" class="modal">
            <div class="modal-content">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <h4 v-if="modalEditar.indexEditando>=0">Editar Producto {{modalEditar.productoEditando.nombre}}</h4>
                            <h4 v-else>Agregar nuevo producto</h4>
                        </div>
                        <form class="col-md-12">
                            <div class="row">
                                <div class="input-field col-md-12">
                                    <input v-model="modalEditar.productoEditando.nombre" id="producto-nombre" type="text" class="validate">
                                    <label for="producto-nombre">Nombre</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-field col s6">
                                    <input v-on:change="ponerNumeros" v-model="modalEditar.productoEditando.precio" placeholder="Solo numero sin puntos" id="producto-precio" type="text" class="validate">
                                    <label for="producto-precio">Precio en Gs</label>
                                </div>
                                <div class="input-field col s6">
                                    <input v-model="modalEditar.productoEditando.codigo" min="0" step="1" id="producto-codigo" placeholder="solo numero" type="number" class="validate">
                                    <label for="producto-codigo">Codigo</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-field col s6">
                                    <input v-model="modalEditar.productoEditando.impuesto" min="0" step="1" placeholder="Impuesto en porcentaje" id="producto-impuesto" type="number" class="validate">
                                    <label for="producto-impuesto">Impuesto</label>
                                </div>
                                <div class="input-field col s6">
                                    <input v-model="modalEditar.productoEditando.descuento" id="producto-descuento" placeholder="solo numero en porcentaje" type="number" class="validate">
                                    <label for="producto-descuento">Descuento</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-field col s6">
                                    <input v-model="modalEditar.productoEditando.stock" min="0" step="1" placeholder="Stock Disponible" id="producto-stock" type="number" class="validate">
                                    <label for="producto-stock">En stock</label>
                                </div>
                                <div class="input-field col s6">
                                    <input v-model="modalEditar.productoEditando.precio_mayorista" id="producto-precio-mayorista" placeholder="Dejar vacio si no aplica" type="number" class="validate">
                                    <label for="producto-precio-mayorista">Precio Mayorista</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-field col s6">
                                    <input v-model="modalEditar.productoEditando.precio_costo" min="0" step="1" placeholder="Precio Costo" id="producto-precio-costo" type="number" class="validate">
                                    <label for="producto-precio-costo">Precio Costo</label>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button @click="guardarCambios" class="btn waves-effect waves-light" type="submit" name="action">Guardar
                    <i class="material-icons right">save</i>
                </button>
            </div>
        </div>
        <!-- Modal Structure -->
        <div id="modalBorrar" class="modal">
            <div class="modal-content">
                <h4>Seguro de querer eliminar?</h4>
                <p>No se puede deshacer</p>
            </div>
            <div class="modal-footer">
                <button @click="confirmarBorrar()" class="btn waves-effect waves-light" type="submit" name="action">ELIMINAR
                    <i class="material-icons right">save</i>
                </button>
            </div>
        </div>
        <!-- Modal Thumbnail -->
        <div id="modalThumbnail" class="modal">
            <div class="modal-content">
                <h4>Miniatura</h4>
                <div class="file-field input-field">
                    <div class="btn">
                        <span>Miniatura</span>
                        <input type="file" accept="image/jpeg" @change="processFile($event)">
                    </div>
                    <div class="file-path-wrapper">
                        <input class="file-path validate" type="text">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button @click="confirmarThumbnail()" class="btn waves-effect waves-light" type="submit" name="action">Guardar
                    <i class="material-icons right">save</i>
                </button>
            </div>
        </div>
    </div>
</template>
<!--@click="llamar(categoria.id)" waves-effect  70 76  7 7   70 80  7 8    70 81 7 8-->
<script>
    import PaginationView from "./PaginationView";
    export default {
        components: {PaginationView},
        props:['_categoriaId'],     //si recibimos una categoria, solo los productos de esa categoria, sino todos los productos
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
                    },
                },
                modalBorrar:{
                    indexBorrando: -1,
                },
                modalThumbnail:{
                    index: -1,
                    file: '',
                }
            }
        },
        watch:{
            _categoriaId: function () {
                this.leer(this._categoriaId)
            },
        },
        mounted() {
            $(document).ready(function(){
                $('.modal').modal();
            });
            console.log('Visor de productos iniciado con categoriaId = '+this._categoriaId);
            this.leer(this._categoriaId);
        },

        methods: {
            confirmarThumbnail(){
                console.log(this.modalThumbnail.file);
                const formData = new FormData();
                formData.append("thumbnail", this.modalThumbnail.file);
                axios.post('producto/thumbnail/'+this.productos[this.modalThumbnail.index].id, formData, {
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
                this.modalThumbnail.index = indice;
                $('#modalThumbnail').modal('open')
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
                axios.put('/producto/'+this.modalEditar.productoEditando.id, this.modalEditar.productoEditando)
                    .then((response) => {
                        console.log(response);
                        this.productos.splice(this.modalEditar.indexEditando,1,this.modalEditar.productoEditando);
                        $('.modal').modal('close')
                    }).catch((error)=>{
                        console.log(error);
                        alert(error.response.data.message);
                });
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
                $('#modalEditar').modal('open')
            },
            buscarProducto(){
                console.log('buscar '+this.busqueda.buscarProducto);
                axios.get('/producto',{params:{
                        busqueda:this.busqueda.buscarProducto,
                        categoria_id: this.categoria_id,
                        limite_inferior: (this.paginaActual-1)*this.paginaCantidadItem,
                        cantidad: this.paginaCantidadItem
                    }}).then((response) => {
                    this.productos = response.data.productos;
                    this.paginaTotal = Math.ceil(parseInt(response.data.cantidad)/this.paginaCantidadItem);
                });
            },
            leer(categoriaId){
                axios.get('/producto',{params:{
                    categoria_id:categoriaId,
                    limite_inferior: (this.paginaActual-1)*this.paginaCantidadItem,
                    cantidad: this.paginaCantidadItem
                }}).then((response) => {
                    this.productos = response.data.productos;
                    this.paginaTotal = Math.ceil(parseInt(response.data.cantidad)/this.paginaCantidadItem);
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
