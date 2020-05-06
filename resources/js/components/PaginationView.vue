<template>
    <nav v-if="total > 1" aria-label="...">
        <ul class="pagination justify-content-center">
            <li :class="'page-item'+((actual === 1)?' disabled':'')">
                <a
                    @click="!(actual===1) && paginacionClick(actual-1)"
                    class="page-link" href="#" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
            <li
                v-for="n in arrayIndice"
                :class="'page-item'+((n===actual)?' active':'')" :aria-current="n===actual?'page':''">
                <a v-if="n!==actual"
                   @click="paginacionClick(n)"
                   class="page-link" href="#">
                    {{n}}
                </a>
                <span v-else class="page-link">
                    {{n}}
                    <span class="sr-only">(current)</span>
                </span>
            </li>
            <li :class="'page-item'+((actual === total)?' disabled':'')">
                <a
                    @click="!(actual === total) && paginacionClick(actual + 1)"
                    class="page-link" href="#" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        </ul>
    </nav>
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
