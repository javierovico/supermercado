<template>
    <div class="row">
        <form class="col s12" action="" v-on:submit.prevent="registrarse()">
            <div class="row">
                <div class="input-field col s12">
                    <input id="name" type="text" class="validate" v-model="nombre.valor">
                    <label for="name">Nombre</label>
                    <span class="helper-text" :data-error="nombre.mensajeError" data-success="">escribi tu Nombre</span>
                </div>
                <div class="input-field col s12">
                    <input id="apellido" type="text" class="validate" v-model="apellido.valor">
                    <label for="apellido">Apellido</label>
                    <span class="helper-text" :data-error="apellido.mensajeError" data-success="">escribi tu Apellido</span>
                </div>
                <div class="input-field col s12">
                    <input id="telefono" type="text" class="validate" v-model="telefono.valor">
                    <label for="telefono">Telefono</label>
                    <span class="helper-text" :data-error="telefono.mensajeError" data-success="">escribi tu telefono</span>
                </div>
                <div class="input-field col s12">
                    <input id="direccion" type="text" class="validate" v-model="direccion.valor">
                    <label for="direccion">direccion</label>
                    <span class="helper-text" :data-error="direccion.mensajeError" data-success="">escribi tu direccion</span>
                </div>
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
                    <input id="password-confirm" type="password" class="validate" required="" aria-required="true" v-model="passwordConfirm.valor">
                    <label for="password-confirm">Password</label>
                    <span class="helper-text" :data-error="passwordConfirm.mensajeError" data-success="">volve a escribir tu contrasena</span>
                </div>

                <div class="input-field col s12">
                    <button class="btn waves-effect waves-light" type="submit">Registrarse
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
                nombre:{
                    valor: '',
                    error: true,
                    mensajeError: 'No puede quedar vacio',
                },
                apellido:{
                    valor: '',
                    error: true,
                    mensajeError: 'No puede quedar vacio',
                },
                telefono:{
                    valor: '',
                    error: true,
                    mensajeError: 'No puede quedar vacio',
                },
                direccion:{
                    valor: '',
                    error: true,
                    mensajeError: 'No puede quedar vacio',
                },
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
                passwordConfirm:{
                    valor: '',
                    error: true,
                    mensajeError: 'No puede quedar vacio',
                },
                thoughts: [],
            }
        },

        mounted() {
            console.log('hola');
        },

        methods: {
            registrarse(){
                const params = {
                    password: this.password.valor,
                    password_confirmation: this.passwordConfirm.valor,
                    email: this.email.valor,
                    name: this.nombre.valor,
                    direccion: this.direccion.valor,
                    apellido: this.apellido.valor,
                    telefono: this.telefono.valor
                };
                axios.post('/register',params)
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
