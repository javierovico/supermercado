<template>
    <div class="row">
        <form class="col s12" action="" v-on:submit.prevent="iniciar()">
            <div class="row">
                <div class="input-field col s12">
                    <input id="email" type="email" class="validate" v-model="email.valor">
                    <label for="email">Email</label>
                    <span class="helper-text" :data-error="email.mensajeError" data-success="">escribi tu correo</span>
                </div>
                <div class="input-field col s12">
                    <input id="password" type="password" class="validate" required="" aria-required="true" v-model="password.valor">
                    <label for="password">Password</label>
                    <span class="helper-text" :data-error="password.mensajeError" data-success="">escribi tu contrasena</span>
                </div>

                <div class="input-field col s12">
                    <button class="btn waves-effect waves-light" type="submit">Iniciar
                        <i class="material-icons right">send</i>
                    </button>
                </div>
            </div>
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
            axios.get('/producto').then((response) => {
                this.thoughts = response.data;
            });
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
                    })
                    .catch((error) =>{
                        const errorMensaje = error.response.data.errors;
                        if(errorMensaje.email !== undefined){
                            this.password.mensajeError = errorMensaje.email;
                            this.email.mensajeError = errorMensaje.email;
                        }else{
                            this.password.mensajeError = 'Datos Inconsistentes';
                            this.email.mensajeError = 'Datos Inconsistentes';
                        }
                        $('#email').removeClass('valid').addClass('invalid');
                        $('#password').removeClass('valid').addClass('invalid');
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
