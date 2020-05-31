<template>
    <!-- Footer -->
    <mdb-footer color="unique-color-dark" class="page-footer font-small pt-0" style="background-color: #1c2331 !important; color: white">
        <div style="backgroundColor: #6351ce">
            <mdb-container class="text-left">
                <mdb-row class="py-4 d-flex align-items-center">
                    <mdb-col md="6" lg="5" class="text-center text-md-left mb-4 mb-md-0">
                        <h6 class="mb-0 white-text">
                            Mantente conectado con nosotros
                        </h6>
                    </mdb-col>
                    <mdb-col md="6" lg="7" class="text-center text-md-right">
                        <a class="fb-ic ml-0" style="color: white" href="https://www.facebook.com/delsupercompy-100110658389853/" target="_blank">
                            <font-awesome-icon :icon="['fab','facebook']" size="2x"/>
                        </a>
                        <a class="fb-ic ml-0" style="color: white" href="http://www.instagram.com/delsuper.com.py" target="_blank">
                            <font-awesome-icon :icon="['fab','instagram']" size="2x"/>
                        </a>
                    </mdb-col>
                </mdb-row>
            </mdb-container>
        </div>
        <mdb-container class="mt-5 mb-4 text-center text-md-left">
            <mdb-row class="mt-3">
                <mdb-col md="3" lg="4" xl="3" class="mb-4">
                    <h6 class="text-uppercase font-weight-bold"><strong>
                        Delsuper
                    </strong></h6>
                    <hr class="deep-purple accent-2 mb-4 mt-0 d-inline-block mx-auto" style="width: 60px" />
                    <p>
                        Nacemos por que queremos cuidarte.
                        En medio de una pandemia que afecta a todo el mundo, y mensajes que nos piden que nos quedemos en casa, vimos la necesidad proteger la salud de las personas mas vulnerables.
                        Buscamos hacer las compras del supermercado, mas facil, rapido y accesible.
                        desde ahora quedate tranqui en casa, todo lo que necesitas llega hasta la puerta de tu casa. facil - Rapido - seguro
                    </p>
                </mdb-col>
                <mdb-col md="2" lg="2" xl="2" class="mb-4">
                    <h6 class="text-uppercase font-weight-bold"><strong>Productos</strong></h6>
                    <hr class="deep-purple accent-2 mb-4 mt-0 d-inline-block mx-auto" style="width: 60px" />
                    <p><a href="#!">MDBootstrap</a></p>
                    <p><a href="#!">MDWordPress</a></p>
                    <p><a href="#!">BrandFlow</a></p>
                    <p><a href="#!">Bootstrap Angular</a></p>
                </mdb-col>
                <mdb-col md="3" lg="2" xl="2" class="mb-4">
                    <h6 class="text-uppercase font-weight-bold"><strong>Links Utiles</strong></h6>
                    <hr class="deep-purple accent-2 mb-4 mt-0 d-inline-block mx-auto" style="width: 60px" />
                    <p><a href="/img/terminos.pdf">Terminos del servicio</a></p>
                    <p><a href="/img/terminos.pdf">Politicas de Privacidad</a></p>
                    <p><a href="#!">Shipping Rates</a></p>
                    <p><a href="#!">Help</a></p>
                </mdb-col>
                <mdb-col md="4" lg="3" xl="3" class="mb-4">
                    <h6 class="text-uppercase font-weight-bold"><strong>Contacto</strong></h6>
                    <hr class="deep-purple accent-2 mb-4 mt-0 d-inline-block mx-auto" style="width: 60px" />
                    <p><font-awesome-icon icon="home" class="fas fa-home mr-3"></font-awesome-icon> Colon 520 c/ ytororo - villeta</p>
                    <p><a href="mailto:sacdelsuper@gmail.com" target="_blank"><font-awesome-icon icon="envelope" class="fas fa-envelope mr-3"></font-awesome-icon> sacdelsuper@gmail.com</a></p>
                    <p><a href="tel:+5959982425662"><font-awesome-icon icon="phone" class="fas fa-phone mr-3"></font-awesome-icon> + 595 9982 425 662</a></p>
                    <p><font-awesome-icon icon="print" class="fas fa-print mr-3"></font-awesome-icon> + 595 9982 425 662</p>
                    <a href="https://wa.me/5959982425662?text=Me%20gustaría%20saber%20mas"><font-awesome-icon :icon="['fab','whatsapp']" class="fas fa-print mr-3"></font-awesome-icon> + 595 9982 425 662</a>
                </mdb-col>
            </mdb-row>
        </mdb-container>
        <div class="footer-copyright text-center py-3" style="background-color: rgba(0,0,0,0.2)">
            <mdb-container fluid>
                &copy; 2020 Copyright: <a href="https://www.facebook.com/delsupercompy-100110658389853/"> delsuper.com.py </a>
            </mdb-container>
        </div>
    </mdb-footer>
    <!-- Footer -->
</template>

<script>
    import { library } from '@fortawesome/fontawesome-svg-core'
    import { faHome, faEnvelope, faPhone, faPrint } from '@fortawesome/free-solid-svg-icons'
    import { faFacebook, faInstagram, faWhatsapp } from "@fortawesome/free-brands-svg-icons";
    library.add(faInstagram, faFacebook,faWhatsapp, faHome, faEnvelope, faPhone, faPrint);
    import { mdbFooter, mdbContainer, mdbRow, mdbCol } from 'mdbvue';
    export default {
        name: 'FooterPage',
        components: {
            mdbFooter,
            mdbContainer,
            mdbRow,
            mdbCol
        }
    }
</script>

<style scoped>
    footer.page-footer a {
        color: #fff;
    }
</style>
