<template>
    <div class="col-md-12">
    </div>
</template>
<!--@click="llamar(categoria.id)" waves-effect  70 76  7 7   70 80  7 8    70 81 7 8-->
<script>
    export default {
        props:['categoria'],     //si recibimos una categoria, solo los productos de esa categoria, sino todos los productos
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
            categoria: function () {
                this.leer();
            },
        },
        mounted() {
            this.leer();
        },

        methods: {
            leer(){
                // axios.get('/producto',{params:{
                //         categoria_id:catego,
                //         limite_inferior: (this.paginaActual-1)*this.paginaCantidadItem,
                //         cantidad: this.paginaCantidadItem
                //     }}).then((response) => {
                //     this.productos = response.data.productos;
                //     this.paginaTotal = Math.ceil(parseInt(response.data.cantidad)/this.paginaCantidadItem);
                // }).catch((error)=>{
                //     console.log(error);
                //     alert(error.toString());
                // });
                // this.categoriaId = categoriaId;
            },
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
