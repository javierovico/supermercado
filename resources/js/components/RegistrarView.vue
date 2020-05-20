<template>
    <div class="row">
        <form class="col-md-12" action="" v-on:submit.prevent="!cargando && registrarse()">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="nombre">Nombre</label>
                    <input v-model="nombre.valor" type="text" :class="'form-control'+((nombre.error === false)?'':(nombre.error===''?(' is-valid'):(' is-invalid')))" id="nombre" placeholder="Nombre" required>
                    <div class="invalid-feedback">
                        {{nombre.error}}
                    </div>
                </div>
                <div class="form-group col-md-6">
                    <label for="apellido">Apellido</label>
                    <input v-model="apellido.valor" type="text" :class="'form-control'+((apellido.error === false)?'':(apellido.error===''?(' is-valid'):(' is-invalid')))" id="apellido" placeholder="Apellido" required>
                    <div class="invalid-feedback">
                        {{apellido.error}}
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="inputEmail4">Email</label>
                    <input v-model="email.valor" type="email" :class="'form-control'+((email.error === false)?'':(email.error===''?(' is-valid'):(' is-invalid')))" id="inputEmail4" placeholder="Email" required>
                    <div id="correoError" class="invalid-feedback">
                        {{email.error}}
                    </div>
                </div>
                <div class="form-group col-md-6">
                    <label for="telefono">Telefono</label>
                    <input v-model="telefono.valor" type="text" :class="'form-control'+((telefono.error === false)?'':(telefono.error===''?(' is-valid'):(' is-invalid')))" id="telefono" placeholder="Telefono" required>
                    <div class="invalid-feedback">
                        {{telefono.error}}
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="contrasenha">Contrasena</label>
                    <input v-model="password.valor" type="password" :class="'form-control'+((password.error === false)?'':(password.error===''?(' is-valid'):(' is-invalid')))" id="contrasenha" placeholder="Contrasenha" required>
                    <div class="invalid-feedback">
                        {{password.error}}
                    </div>
                </div>
                <div class="form-group col-md-6">
                    <label for="confirmarContrasenha">Confirmar Contrasenha</label>
                    <input v-model="passwordConfirm.valor" type="password" :class="'form-control'+((passwordConfirm.error === false)?'':(passwordConfirm.error===''?(' is-valid'):(' is-invalid')))" id="confirmarContrasenha" placeholder="Contrasenha" required>
                    <div class="invalid-feedback">
                        {{passwordConfirm.error}}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="inputAddress">Direccion</label>
                <input v-model="direccion.valor" type="text" :class="'form-control'+((direccion.error === false)?'':(direccion.error===''?(' is-valid'):(' is-invalid')))" id="inputAddress" placeholder="1234 Central" required>
                <div class="invalid-feedback">
                    {{direccion.error}}
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="nacimiento">Nacimiento</label>
                    <input v-model="nacimiento.valor" type="text" :class="'form-control'+((nacimiento.error === false)?'':(nacimiento.error===''?(' is-valid'):(' is-invalid')))" id="nacimiento" placeholder="Fecha/anho" required>
                    <div class="invalid-feedback">
                        {{nacimiento.error}}
                    </div>
                </div>
            </div>
            <button class="btn btn-primary" type="submit" :disabled="cargando">
                <span v-if="cargando" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                {{!cargando?'Registrarse':'Registrando'}}
            </button>
            <button @click="$emit('iniciar')" class="btn btn-primary" :disabled="cargando">
                <span v-if="cargando" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                {{!cargando?'Iniciar Sesion':'Registrando'}}
            </button>
        </form>
    </div>

</template>

<script>
    export default {
        data() {
            return {
                cargando: false,
                nombre:{
                    valor: '',
                    error: false,
                    mensajeError: 'No puede quedar vacio',
                },
                apellido:{
                    valor: '',
                    error: false,
                    mensajeError: 'No puede quedar vacio',
                },
                telefono:{
                    valor: '',
                    error: false,
                    mensajeError: 'No puede quedar vacio',
                },
                direccion:{
                    valor: '',
                    error: false,
                    mensajeError: 'No puede quedar vacio',
                },
                email: {
                    valor: '',
                    error: false,
                    mensajeError: 'No puede quedar vacio',
                },
                password: {
                    valor: '',
                    error: false,
                    mensajeError: 'No puede quedar vacio',
                },
                passwordConfirm:{
                    valor: '',
                    error: false,
                    mensajeError: 'No puede quedar vacio',
                },
                nacimiento:{
                    valor: '',
                    error: false,
                    mensajeError: 'No puede quedar vacio',
                },
                thoughts: [],
            }
        },

        mounted() {
            console.log('hola');
            $('#nacimiento').datepicker();
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
                    telefono: this.telefono.valor,
                    nacimiento: $('#nacimiento').datepicker('getDate'),
                };
                this.cargando = true;
                axios.post('/register',params)
                    .then((response)=>{
                        this.password.valor = '';
                        this.email.valor = '';
                        this.$emit('checkUser');
                    })
                    .catch((error) =>{
                        const errorMensaje = error.response.data.errors;
                        this.nombre.error = (errorMensaje.nombre !== undefined)?errorMensaje.nombre[0]:'';
                        this.apellido.error = (errorMensaje.apellido !== undefined)?errorMensaje.apellido[0]:'';
                        this.telefono.error = (errorMensaje.telefono !== undefined)?errorMensaje.telefono[0]:'';
                        this.direccion.error = (errorMensaje.direccion !== undefined)?errorMensaje.direccion[0]:'';
                        this.nacimiento.error = (errorMensaje.nacimiento !== undefined)?errorMensaje.nacimiento[0]:'';
                        this.password.error = (errorMensaje.password !== undefined)?errorMensaje.password[0]:'';
                        this.passwordConfirm.error = (errorMensaje.password !== undefined)?errorMensaje.password[0]:'';
                        this.email.error = (errorMensaje.email !== undefined)?errorMensaje.email[0]:'';
                    }).finally(()=>{
                        this.cargando=false;
                    });
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
