<template>
    <div class="col-md-12">
        <table class="table">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Fecha</th>
                <th v-if="$store.getters.isRol('financiero')" scope="col">Nombre</th>
                <th v-if="$store.getters.isRol('financiero')" scope="col">Telefono</th>
                <th scope="col">Total</th>
                <th scope="col">Estado</th>
                <th scope="col">Acciones</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(compra,index) in compras">
                <th scope="row">{{index+1}}</th>
                <td>{{$intToFecha(compra.updated_at)}}</td>
                <td v-if="$store.getters.isRol('financiero')">{{compra.user.name}}{{compra.user.apellido}}</td>
                <td v-if="$store.getters.isRol('financiero')">{{compra.user.telefono}}</td>
                <td>{{$precio(compra.pago_total)}}</td>
                <td>{{estado(compra)}}</td>
                <td>
                    <router-link :to="{path:'/carrito/historial/detalle/'+compra.id}" data-toggle="tooltip" data-placement="top" title="Ver detalles de compra"><i class="material-icons">remove_red_eye</i></router-link>
                    <a href="#!" v-if="$store.getters.isRol('financiero')" @click.prevent="mostrarConfirmacionRollback(index)" data-toggle="tooltip" data-placement="top" title="Deshacer Pago"><i class="material-icons">close</i></a>
                </td>
            </tr>
            </tbody>
        </table>
        <vista-paginacion
            :actual="paginaActual"
            :total="paginaTotal"
            :slidingEndingSize="10"
            @paginacionClick="paginacionClick"
        ></vista-paginacion>
        <ModalConfirmarRollback
            :modal="modalRollback"
        ></ModalConfirmarRollback>
    </div>
</template>
<script>
    import ModalConfirmarRollback from "./ModalConfirmarRollback";
    export default {
        components: {ModalConfirmarRollback},
        props:[
            'auth',             //para saber si esta o no iniciado
        ],
        data() {
            return {
                compras: [],
                paginaActual: 1,
                paginaTotal: 1,
                paginaCantidadItem:20,
                busqueda:{
                    buscarProducto: ''
                },
                modalBorrarCarrito:{
                    productoBorrando: null,
                    index: null,
                },
                modalConfirmacionPago:{
                    precio: 0,
                    compraId: 0,
                },
                modalRollback:{
                    compraRollback:null,
                    index:null,
                }
            }
        },
        watch:{
            categoriaSeleccionada: function () {
                this.leer();
            },
        },
        mounted() {
            const statusPayment = this.$route.query.status;
            switch(statusPayment){
                case 'payment_success':
                    $.notify({
                        title:'Pago',
                        message: 'el pago se conpleto satisfactoriamente',
                    }, {
                        type:'success',
                    })

                    break;
                case 'payment_failed':
                    $.notify({
                        title:'Pago',
                        message: 'Hubo un error procesando el pago',
                    }, {
                        type:'danger',
                    })
                    break;
            }
            this.leer();
        },
        // computed:{
        // },

        methods: {
            estado(compra){
                if(compra.pagado == 1){
                    return 'pagado';
                }else if(compra.estado == 'x1'){
                    return 'Pago pos (entrega en curso)'
                }else if(compra.estado == 'x2'){
                    return 'contraentrega pendiente (e)'
                }else if(compra.estado == 'x3'){
                    return 'contraentrega pendiente (e)'
                }
                return 'desconocido';
            },
            leer(){
                let parametroBusqueda = {
                    page: this.paginaActual,
                    cantidad: this.paginaCantidadItem,
                };
                axios.get('/compra/historial',{params:parametroBusqueda}).then((response) => {
                    this.compras = response.data.data;
                    this.paginaTotal = response.data.last_page;
                }).catch((error)=>{
                    if(error.response.data.message == "tenes que iniciar sesion"){
                        this.$router.push('/iniciar-sesion');
                    }else{
                        $.notify({title:error.response.data.message})
                    }
                });
            },
            paginacionClick(n){
                this.paginaActual = n;
                this.leer();
            },
            mostrarConfirmacionRollback(index){
                this.modalRollback.compraRollback = this.compras[index];
                this.modalRollback.index = index;
                $('#modalConfirmarRollback').modal();
            },
        }
    }
</script>
