<template>
    <div v-if="productos.length>0" class="col s12">
        <h3 class="header" style="color: #ee6e73;">Productos</h3>
        <table>
            <thead>
            <tr>
                <th>Codigo</th>
                <th>Nombre</th>
                <th>Precio</th>
                <th>Acciones</th>
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
                <td>
                    <div class="row">
                        <div class="col s6">
                            Borrar
                        </div>
                        <div class="col s6">
                            Modificar
                        </div>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
        <sliding-pagination
            :current="paginaActual"
            :total="paginaTotal"
            :slidingEndingSize="4"
            @page-change="paginacionClick"
        ></sliding-pagination>
<!--        <vista-paginacion-->
<!--            :cantidadTotalResultado="cantidadTotalResultado"-->
<!--            :limiteInferiorBuscado="limiteInferiorBuscado"-->
<!--            :cantidadBuscada="cantidadBuscada"-->
<!--            @paginacionClick="paginacionClick"-->
<!--        ></vista-paginacion>-->
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
                paginaActual: 1,
                paginaTotal: 1,
                paginaCantidadItem: 10,
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
                console.log(n);
                if(n<1 || n> this.paginaTotal){
                    return;
                }
                this.paginaActual = n;
                this.leer(this.categoriaId);
            },
        }
    }
</script>
