<template>
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
</template>
<!--@click="llamar(categoria.id)" waves-effect  70 76  7 7   70 80  7 8    70 81 7 8-->
<script>
    export default {
        props:['cantidadTotalResultado','limiteInferiorBuscado','cantidadBuscada'],
        data() {
            return {
            }
        },

        mounted() {
            console.log('Paginacion creada con '+this.cantidadTotalResultado,this.limiteInferiorBuscado,this.cantidadBuscada);
        },

        methods: {
            paginacionClick(n){
                console.log('Paginacion pulsada '+n);
                this.$emit('paginacionClick',n);
            },
        }
    }
</script>
