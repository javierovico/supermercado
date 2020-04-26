<template>
    <div v-if="productos.length>0" class="col s12">
        <h3 class="header" style="color: #ee6e73;">Productos</h3>
        <table>
            <thead>
            <tr>
                <th>Codigo</th>
                <th>Nombre</th>
                <th>Precio</th>
            </tr>
            </thead>

            <tbody>
            <tr
                v-for="(producto,index) in productos"
                :key="index"
            >
                <td>{{producto.codigo}}</td>
                <td>{{producto.nombre}}</td>
                <td>{{producto.precio}}</td>
            </tr>
            </tbody>
        </table>
    </div>
</template>
<!--@click="llamar(categoria.id)" waves-effect  70 76  7 7   70 80  7 8    70 81 7 8-->
<script>
    export default {
        props:['_categoriaId'],     //si recibimos una categoria, solo los productos de esa categoria, sino todos los productos
        data() {
            return {
                categoriaId:null,
                productos: [],
                cantidadTotalResultado: 0,
                limiteInferiorBuscado: 0,
                cantidadBuscada: 10,
            }
        },
        watch:{
            _categoriaId: function () {
                this.leer(this._categoriaId)
            },
        },
        mounted() {
            console.log('Visor de productos iniciado con categoriaId = '+this._categoriaId);
            this.leer(this._categoriaId);
        },

        methods: {
            leer(categoriaId){
                axios.get('/producto',{params:{categoria_id:categoriaId}}).then((response) => {
                    this.productos = response.data.productos;
                }).catch((error)=>{
                    console.log(error);
                    alert(error.toString());
                });
                this.categoriaId = categoriaId;
            },
        }
    }
</script>
