<template>
    <input :class="'form-control'+((nacimiento.error === false)?'':(nacimiento.error===''?(' is-valid'):(' is-invalid')))" id="nacimiento" placeholder="Fecha/anho" required type="text" v-datepicker :value="value" @input="update($event.target.value)">
</template>

<script>
    export default {
        directives: {
            datepicker: {
                inserted (el, binding, vNode) {
                    $(el).datepicker({
                        autoclose: true,
                        format: 'yyyy-mm-dd'
                    }).on('changeDate',function(e){
                        vNode.context.$emit('input', e.format(0))
                    })
                }
            }
        },
        props: ['value','nacimiento'],
        methods: {
            update (v){
                this.$emit('input', v)
            }
        }
    }
</script>
