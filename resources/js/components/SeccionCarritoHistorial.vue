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
                <th scope="col">Entrega</th>
                <th scope="col">Acciones</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(compra,index) in compras" :style="'background:'+(compra.estadoStr!='entregado'?'#fbdbdb':'rgb(205, 247, 200)')">
                <th scope="row">{{index+1}}</th>
                <td>{{$intToFecha(compra.updated_at)}}</td>
                <td v-if="$store.getters.isRol('financiero')">{{compra.user.name}}{{compra.user.apellido}}</td>
                <td v-if="$store.getters.isRol('financiero')">{{compra.user.telefono}}</td>
                <td>{{$precio(compra.pago_total)}}</td>
                <td>{{compra.estadoStr}}</td>
                <td>{{compra.entrega}}</td>
                <td>
                    <router-link :to="{path:'/front/admin/usuario/'+compra.user.id}" v-if="$store.getters.isRol('financiero')" data-toggle="tooltip" data-placement="top" title="Ver usuario"><i class="material-icons">person</i></router-link>
                    <router-link :to="{path:'/carrito/historial/detalle/'+compra.id}" data-toggle="tooltip" data-placement="top" title="Ver detalles de compra"><i class="material-icons">remove_red_eye</i></router-link>
                    <a href="#!" v-if="$store.getters.isRol('financiero')" @click.prevent="mostrarConfirmacionRollback(index)" data-toggle="tooltip" data-placement="top" title="Deshacer Pago"><i class="material-icons">close</i></a>
                    <a href="#!" v-if="$store.getters.isDelivery()" @click.prevent="mostrarConfirmacionEntregado(index)" data-toggle="tooltip" data-placement="top" title="Marcar como entregado"><i class="material-icons">done</i></a>
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
        <ModalConfirmarEntrega
            :modal="modalEntrega"
        ></ModalConfirmarEntrega>
    </div>
</template>
<script>
    import ModalConfirmarRollback from "./ModalConfirmarRollback";
    import ModalConfirmarEntrega from "./ModalConfirmarEntrega";
    export default {
        components: {ModalConfirmarEntrega, ModalConfirmarRollback},
        props:[
            'auth',             //para saber si esta o no iniciado
            'pendientes'
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
                },
                modalEntrega:{
                    compraEntrega:null,
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
            // estado(compra){
            //     if(compra.pagado == 1){
            //         return 'Pagado';
            //     }else if(compra.estado == 'x1'){
            //         return 'Pago online pendiente'
            //     }else if(compra.estado == 'x2'){
            //         return 'Contraentrega pendiente (d/c)';
            //     }else if(compra.estado == 'x3'){
            //         return 'Contraentrega pendiente (e)';
            //     }else if(compra.estado == 'd1'){
            //         return 'Pago Denegado Bancard';
            //     }else if(compra.estado == 'roll'){
            //         return 'Pago Cancelado';
            //     }
            //     return 'desconocido';
            // },
            leer(){
                let parametroBusqueda = {
                    page: this.paginaActual,
                    cantidad: this.paginaCantidadItem,
                    // pendientes: this.pendientes?'1':'0',
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
            mostrarConfirmacionEntregado(index){
                this.modalEntrega.compraEntrega = this.compras[index];
                this.modalEntrega.index = index;
                $('#modalConfirmarEntrega').modal();
            }
        }
    }
</script>
