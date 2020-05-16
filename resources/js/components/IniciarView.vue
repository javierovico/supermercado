<template>
    <div class="row">
        <form class="col-md-12" action="" v-on:submit.prevent="iniciar()">
            <div class="form-group">
                <label for="exampleInputEmail1">Correo</label>
                <input v-model="email.valor" type="email" class="form-control" id="exampleInputEmail1" required>
                <div id="correoError" class="invalid-feedback">
                    {{email.mensajeError}}
                </div>
            </div>
            <div class="form-group">
                <label for="exampleInputPassword1">Clave</label>
                <input v-model="password.valor" type="password" class="form-control" id="exampleInputPassword1">
                <div id="passError" class="invalid-feedback">
                    {{password.mensajeError}}
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Iniciar</button>
            <button @click.prevent="$emit('registrarse')" class="btn btn-primary">Registrarse</button>
        </form>
    </div>

</template>

<script>
    export default {
        data() {
            return {
                email: {
                    valor: '',
                    error: true,
                    mensajeError: 'No puede quedar vacio',
                },
                password: {
                    valor: '',
                    error: true,
                    mensajeError: 'No puede quedar vacio',
                },
                thoughts: [],
            }
        },

        mounted() {

        },

        methods: {
            iniciar(){
                const params = {
                    password: this.password.valor,
                    email: this.email.valor
                };
                axios.post('/login',params)
                    .then((response)=>{
                        this.password.valor = '';
                        this.email.valor = '';
                        this.$emit('checkUser');
                        this.$router.push('/');
                    })
                    .catch((error) =>{
                        const errorMensaje = error.response.data.errors;
                        if(errorMensaje.email !== undefined){
                            this.password.mensajeError = errorMensaje.email[0];
                            this.email.mensajeError = errorMensaje.email[0];
                        }else{
                            this.password.mensajeError = 'Datos Inconsistentes';
                            this.email.mensajeError = 'Datos Inconsistentes';
                        }
                        $('#exampleInputEmail1').removeClass('is-valid').addClass('is-invalid');
                        $('#exampleInputPassword1').removeClass('is-valid').addClass('is-invalid');
                        console.log('error',error.response.data);
                    })
                    // .then(() => {
                    //     console.log('fin');
                    // });
            },
            addThought(thought) {
                this.thoughts.push(thought);
            },
            updateThought(index, thought) {
                this.thoughts[index] = thought;
            },
            deleteThought(index) {
                this.thoughts.splice(index, 1);
            }
        }
    }
</script>
