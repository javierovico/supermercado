<template>
    <div class="row">
        <div class="col s12">
            <nav>
                <div class="nav-wrapper">
                    <div class="col s12">
                        <a
                            v-for="(catAnt, index) in categoriasAnteriores"
                            :key="index"
                            @click="desapilar(index)"
                            href="#!" class="breadcrumb">
                            {{catAnt.nombre}}
                        </a>
                    </div>
                </div>
            </nav>
        </div>
        <div class="col s12">
            <div class="collection">
                <a
                    :data-index="index"
                    v-for="(categoria, index) in categorias"
                    :key="categoria.id"
                    href="#!"
                    @click="cambiarCategoria(index)"
                    class="collection-item">
                    <span class="badge">{{categoria.cant_sub_cat}}</span>{{categoria.nombre}}
                    <span v-if="categoria.cant_prod > 0" class="new badge">{{categoria.cant_prod}} Prod</span>
                </a>
            </div>
        </div>
        <lista-productos
            :_categoriaId="idPadre"
        ></lista-productos>
        <div class="col s12">
            <div class="row">
                <div class="input-field col s12">
                    <i class="material-icons prefix">textsms</i>
                    <input v-model="productoBuscado" type="text" id="autocomplete-input" class="autocomplete" v-on:change="buscarProducto">
                    <label for="autocomplete-input">Busqueda</label>
                </div>
            </div>
        </div>
        <div class="col s12">
            <ul class="collection">
                <li
                    v-for="(producto,index) in resultadoBusquedaProducto"
                    :key="index"
                    class="collection-item">
                    <p>
                        <label>
                            <input v-model="producto.seleccionado" type="checkbox" class="filled-in" />
                            <span>{{producto.nombre}}</span>
                        </label>
                    </p>
                </li>
            </ul>
            <button
                @click="enviarResultados()"
                v-if="resultadoBusquedaProducto.length>0"
                class="btn waves-effect waves-light" type="submit" name="action">Enviar
                <i class="material-icons right">send</i>
            </button>
            <ul
                v-if="cantidadTotalResultado > 0"
                class="pagination">
                <li :class="(limiteInferiorBuscado === 0 )?'disabled':'waves-effect'">
                    <a
                        @click="!(limiteInferiorBuscado===0) && paginacionClick((limiteInferiorBuscado/cantidadBuscada))"
                        href="#!">
                        <i class="material-icons">chevron_left</i>
                    </a>
                </li>
                <li
                    v-for="n in Math.ceil(cantidadTotalResultado/cantidadBuscada)"
                    :class="((limiteInferiorBuscado < (n*cantidadBuscada)) && (((n-1)*(cantidadBuscada)) <= limiteInferiorBuscado))?'active':'waves-effect'">
                    <a
                        @click="paginacionClick(n)"
                        href="#!">{{n}}</a>
                </li>
                <li :class="((cantidadTotalResultado - limiteInferiorBuscado) <= cantidadBuscada)?'disabled':'waves-effect'">
                    <a
                        @click="!((cantidadTotalResultado - limiteInferiorBuscado) <= cantidadBuscada) && paginacionClick((limiteInferiorBuscado/cantidadBuscada)+2)"
                        href="#!">
                        <i class="material-icons">chevron_right</i>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</template>
<!--@click="llamar(categoria.id)" waves-effect  70 76  7 7   70 80  7 8    70 81 7 8-->
<script>
    export default {
        props:['_idPadre','_tituloPadre'],
        data() {
            return {
                idPadre:null,
                categorias: [],
                productos: [],
                categoriasAnteriores: [],   //[{nombre:,id:}]
                productoBuscado: '',
                resultadoBusquedaProducto: [],
                // paginacionBusqueda: [],     //  []
                cantidadTotalResultado: 0,
                limiteInferiorBuscado: 0,
                cantidadBuscada: 10,
            }
        },

        mounted() {
            console.log('Creado con _idPadre: '+ this._idPadre+ ' Titulo '+this._tituloPadre);
            this.idPadre = this._idPadre;
            this.leer((this._tituloPadre)?this._idPadre:'Categoria',this._idPadre);
        },

        methods: {
            cambiarCategoria(index){
                const categoria = this.categorias[index];
                console.log('cambiando categoria a id:');
                this.leer(categoria.nombre,categoria.id);
            },
            leer(nombre, id){
                axios.get('/categoria',{params:{categoria_id:id}}).then((response) => {
                    this.categoriasAnteriores.push({nombre:nombre,id:id});
                    this.categorias = response.data;
                    this.idPadre = id;
                });
                // axios.get('/producto',{params:{categoria_id:id}}).then((response) => {
                //     this.productos = response.data.productos;
                // });

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
                    busqueda:this.productoBuscado,
                    categoria_id: this.getCategoriaIdActual(),
                    limite_inferior: this.limiteInferiorBuscado,
                    cantidad: this.cantidadBuscada
                }}).then((response) => {
                    this.resultadoBusquedaProducto = response.data.productos;
                    this.cantidadTotalResultado = response.data.cantidad;
                });
            },
            getCategoriaIdActual(){
                return (this.categoriasAnteriores.length > 0)?(this.categoriasAnteriores[this.categoriasAnteriores.length-1].id):null;
            },
            paginacionClick(n){
                this.limiteInferiorBuscado = this.cantidadBuscada * (n-1);
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
                }).catch((error)=>{
                    alert('No tenes permisos');
                    console.log(error);
                });
                console.log(valorEnviar,this.getCategoriaIdActual());
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
