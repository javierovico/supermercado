<template>
    <ul
        v-if="total > 1"
        class="pagination">
        <li :class="(actual === 1 )?'disabled':'waves-effect'">
            <a
                @click="!(actual===0) && paginacionClick(actual-1)"
                href="#!">
                <i class="material-icons">chevron_left</i>
            </a>
        </li>
        <li
            v-for="n in arrayIndice"
            :class="(n===actual)?'active':'waves-effect'">
            <a
                @click="paginacionClick(n)"
                href="#!">{{n}}</a>
        </li>
        <li :class="(actual === total)?'disabled':'waves-effect'">
            <a
                @click="!(actual === total) && paginacionClick(actual + 1)"
                href="#!">
                <i class="material-icons">chevron_right</i>
            </a>
        </li>
    </ul>
</template>
<!--@click="llamar(categoria.id)" waves-effect  70 76  7 7   70 80  7 8    70 81 7 8-->
<script>
    export default {
        props:['total','actual','slidingEndingSize'],
        computed:{
            arrayIndice: function () {
                let array = [1];

                let contador = ((this.total < this.slidingEndingSize)?this.total:this.slidingEndingSize)-1;
                let negativo = this.actual;
                let positivo = this.actual;
                while(contador> 0){
                    if((positivo+1)<=this.total){
                        contador--;
                        positivo++;
                    }
                    if((negativo -1)>=1 && contador>0){
                        contador--;
                        negativo--;
                    }
                }
                if(negativo > 2){
                    array.push('...');
                }
                for(let i = negativo;i<=positivo;i++){
                    if(i > 1 && i<this.total){
                        array.push(i);
                    }
                }
                if(positivo < this.total-1){
                    array.push('...');
                }
                array.push(this.total);
                return array;
            }
        },
        mounted() {
            console.log('Paginacion creada con '+this.total,this.actual);
        },
        methods: {
            paginacionClick(n){
                if(n === parseInt(n)){
                    console.log('Paginacion pulsada '+n);
                    this.$emit('paginacionClick',n);
                }
            },
        }
    }
</script>
