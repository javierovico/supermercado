<template>
    <mdb-container class="mt-5">
        <mdb-card>
            <mdb-card-title class="mt-4 h2">Contacto</mdb-card-title>
            <mdb-card-body>
                <mdb-card-text class="pr-5 pl-5" mr-4 ml-4>
                    Tenes alguna duda? Nuestro equipo esta siempre disponible.
                </mdb-card-text>
                <form class="needs-validation" novalidate @submit.prevent="submitForm">
                    <mdb-row>
                        <mdb-col md="8">
                            <mdb-row>
                                <mdb-col>
                                    <mdb-input label="Tu nombre" v-model="fields.nombre" required />
                                </mdb-col>
                                <mdb-col>
                                    <mdb-input type="email" label="Tu correo" v-model="fields.email" required />
                                </mdb-col>
                            </mdb-row>
                            <mdb-row>
                                <mdb-col>
                                    <mdb-input label="Asunto" v-model="fields.asunto" required />
                                </mdb-col>
                            </mdb-row>
                            <mdb-row>
                                <mdb-col>
                                    <mdb-input
                                        type="textarea"
                                        label="Tu mensaje"
                                        v-model="fields.mensaje"
                                        required
                                    />
                                </mdb-col>
                            </mdb-row>
                        </mdb-col>
                        <mdb-col md="4">
                            <mdb-row>
                                <mdb-col class="mt-4">
                                    <font-awesome-icon icon="map-marked-alt" size="2x" />
                                </mdb-col>
                            </mdb-row>
                            <mdb-row>
                                <mdb-col class="mt-4">
                                    <font-awesome-icon icon="phone" size="2x" />
                                    <p>+ 595 9982 425 662</p>
                                </mdb-col>
                            </mdb-row>
                            <mdb-row>
                                <mdb-col class="mt-4">
                                    <font-awesome-icon icon="envelope" size="2x" />
                                    <p>financiero@delsuper.com.py</p>
                                </mdb-col>
                            </mdb-row>
                        </mdb-col>
                    </mdb-row>
                    <mdb-btn color="primary" type="submit" class="float-left">Enviar</mdb-btn>
                </form>
            </mdb-card-body>
        </mdb-card>
    </mdb-container>
</template>

<script>

    import { library } from '@fortawesome/fontawesome-svg-core'
    import { faMapMarkedAlt, faPhone, faEnvelope } from '@fortawesome/free-solid-svg-icons'
    library.add( faEnvelope, faPhone, faMapMarkedAlt);
    import {
        mdbContainer,
        mdbInput,
        mdbCard,
        mdbCardHeader,
        mdbCardTitle,
        mdbCardText,
        mdbCardBody,
        mdbIcon,
        mdbBtn,
        mdbRow,
        mdbCol
    } from "mdbvue";

    export default {
        components: {
            mdbContainer,
            mdbInput,
            mdbCard,
            mdbCardHeader,
            mdbCardTitle,
            mdbCardBody,
            mdbCardText,
            mdbIcon,
            mdbBtn,
            mdbRow,
            mdbCol
        },
        data() {
            return {
                cargando: false,
                fields: {
                    nombre: "",
                    email: "",
                    asunto: "",
                    mensaje: ""
                }
            };
        },
        methods: {
            submitForm(event) {
                this.cargando = true;
                event.target.classList.add("was-validated");
                console.log(this.fields);
                axios.post('/sugerencia',this.fields).then((response)=>{
                    this.fields.asunto = '';
                    this.fields.mensaje = '';
                    $.notify({
                        title: 'Sugerencia Enviada',
                        message: '',
                    },{
                        type: 'success',
                    })
                }).catch((error)=>{
                    $.notify({
                        title: error.response.data.message,
                        message: ''
                    })
                }).finally(()=>{
                    this.cargando = false;
                });
            }
        }
    };
</script>

<style scoped>
    .container {
        text-align: center;
    }
</style>
