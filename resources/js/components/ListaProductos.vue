<template>
    <div v-show="productos.length>0" class="col-md-12">
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link active" href="#">Productos</a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Acciones</a>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="#">Agregar Producto</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#">Administrar</a>
                </div>
            </li>
            <li class="nav-item">
                <form class="form-inline my-2 my-lg-0" v-on:submit.prevent="leer">
                    <input v-model="busqueda.buscarProducto" class="form-control mr-sm-2" type="search" placeholder="Busqueda..." aria-label="Buscar">
                    <button class="btn btn-success my-2 my-sm-0" type="submit">Buscar</button>
                </form>
            </li>
        </ul>
        <div class="form-check">
            <div class="form-check">
                <input v-on:change="paginaActual=1;leer()"  v-model="busqueda.opcionCategoria" class="form-check-input" type="radio" name="tipoCantidad" id="tipoCantidad1" value="1" checked>
                <label class="form-check-label" for="tipoCantidad1">
                    Todos
                </label>
            </div>
            <div class="form-check">
                <input v-on:change="paginaActual=1;leer()"  v-model="busqueda.opcionCategoria" class="form-check-input" type="radio" name="tipoCantidad" id="tipoCantidad2" value="2">
                <label class="form-check-label" for="tipoCantidad2">
                    Sin Categorias
                </label>
            </div>
            <div class="form-check">
                <input v-on:change="paginaActual=1;leer()"  v-model="busqueda.opcionCategoria" class="form-check-input" type="radio" name="tipoCantidad" id="tipoCantidad3" value="3">
                <label class="form-check-label" for="tipoCantidad3">
                    Con Categorias
                </label>
            </div>
<!--            <input v-on:change="leer" v-model="busqueda.soloSinCategoria" class="form-check-input" type="checkbox" value="" id="defaultCheck1">-->
<!--            <label class="form-check-label" for="defaultCheck1">-->
<!--                Solo Productos Sin Categorias-->
<!--            </label>-->
        </div>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th scope="col">Codigo</th>
                <th scope="col">Nombre</th>
                <th scope="col">Precio</th>
                <th scope="col">Num Cate</th>
                <th scope="col">Acciones</th>
            </tr>
            </thead>
            <tbody>
            <tr
                v-for="(producto,index) in productos"
                :key="index"
            >
                <th scope="row">{{producto.codigo}}</th>
                <td>{{producto.nombre}}</td>
                <td>{{producto.precio.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")}}</td>
                <td>{{producto.cant_categorias}}</td>
                <td>
                    <a @click="editarProducto(index)" href="#!"><i class="material-icons">edit</i></a>
                    <a @click="editarThumbnail(index)" href="#!"><i class="material-icons">add_a_photo</i></a>
                    <a @click="borrarProducto(index)" href="#!"><i class="material-icons">delete</i></a>
                    <a @click="editarCategorias(index)" href="#!"><i class="material-icons">category</i></a>
                </td>
            </tr>
            </tbody>
        </table>
        <pagination-view
            :actual="paginaActual"
            :total="paginaTotal"
            :slidingEndingSize="10"
            @paginacionClick="paginacionClick"
        ></pagination-view>
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
        <modal-editar-producto-categoria
            :producto="modalEditarCategoria.producto"
        ></modal-editar-producto-categoria>
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
                    opcionCategoria: 1,    //para mostrar solo productos sin categorias, con categoria o ambos
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
                },
                modalEditarCategoria:{
                    producto: null,
                }
            }
        },
        watch:{
            _categoriaId: function () {
                this.categoriaId = this._categoriaId
                this.leer()
            },
        },
        mounted() {
            console.log('Visor de productos iniciado con categoriaId = '+this._categoriaId);
            this.categoriaId = this._categoriaId
            this.leer();
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
            // buscarProducto(){
            //     console.log('buscar '+this.busqueda.buscarProducto);
            //     axios.get('/producto',{params:{
            //             palabra_clave:this.busqueda.buscarProducto,
            //             categoria_id: this.categoriaId,
            //             page: this.paginaActual,
            //             cantidad: this.paginaCantidadItem
            //         }}).then((response) => {
            //         this.productos = response.data.data;
            //         this.paginaTotal = response.data.last_page;
            //     });
            // },
            leer(){   //toma el valor por defecto si no hubo nada
                let parametroBusqueda = {
                    categoria_id:this.categoriaId,
                    page: this.paginaActual,
                    cantidad: this.paginaCantidadItem,
                    opcionCategoria: this.busqueda.opcionCategoria,
                };
                if(this.busqueda.buscarProducto.length > 0){
                    parametroBusqueda.palabra_clave = this.busqueda.buscarProducto;
                }
                axios.get('/producto',{params:parametroBusqueda}).then((response) => {
                    this.productos = response.data.data;
                    this.paginaTotal = response.data.last_page;
                }).catch((error)=>{
                    console.log(error);
                    alert(error.toString());
                });
            },
            paginacionClick(n){
                this.paginaActual = n;
                this.leer();
                // if(this.busqueda.buscarProducto.length > 0){
                //     this.buscarProducto();
                // }else{
                //     this.leer(this.categoriaId);
                // }
            },
            editarCategorias(n){
                console.log('editando categoria de '+n);
                this.modalEditarCategoria.producto = this.productos[n];
                $('#modalEditarProductoCategoria').modal();
            },
        }
    }
</script>
