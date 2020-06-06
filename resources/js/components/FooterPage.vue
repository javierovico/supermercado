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
                        Recorre los pasillos del supermercado desde la comodida de tu casa u oficina.<br><b>Súper fácil</b><br><br>Recibí tus compras en el horario más conveniente para vos.<br><b>Es rápido</b>
                        <br><br>también podes enviar tus compras a una persona muy querida.<br><b>Es seguro!</b>
                    </p>
                </mdb-col>
                <mdb-col md="2" lg="2" xl="2" class="mb-4">
                    <h6 class="text-uppercase font-weight-bold"><strong>Informacion</strong></h6>
                    <hr class="deep-purple accent-2 mb-4 mt-0 d-inline-block mx-auto" style="width: 60px" />
                    <p>Horarios de entrega</p>
                    <p>Lunes a sábados 9:00 - 18:00</p>
                    <p>Domingos 9:00 - 12:00</p>
                    <p><a @click.prevent="mostrarPopUp" href="#!">Ver Cobertura</a></p>
                </mdb-col>
                <mdb-col md="3" lg="2" xl="2" class="mb-4">
                    <h6 class="text-uppercase font-weight-bold"><strong>Links Utiles</strong></h6>
                    <hr class="deep-purple accent-2 mb-4 mt-0 d-inline-block mx-auto" style="width: 60px" />
                    <p><a href="/img/terminos.pdf">Terminos del servicio</a></p>
                    <p><a href="/img/terminos.pdf"></a></p>
                </mdb-col>
                <mdb-col md="4" lg="3" xl="3" class="mb-4">
                    <h6 class="text-uppercase font-weight-bold"><strong>Contacto</strong></h6>
                    <hr class="deep-purple accent-2 mb-4 mt-0 d-inline-block mx-auto" style="width: 60px" />
                    <p><a href="mailto:finaciero@delsuper.com.py" target="_blank"><font-awesome-icon icon="envelope" class="fas fa-envelope mr-3"></font-awesome-icon> financiero@delsuper.com.py</a></p>
                    <a href="https://wa.me/5959982425662?text=Me%20gustaría%20saber%20mas"><font-awesome-icon :icon="['fab','whatsapp']" class="fas fa-print mr-3"></font-awesome-icon> + 595 9982 425 662</a>
                </mdb-col>
            </mdb-row>
        </mdb-container>
        <div class="footer-copyright text-center py-3" style="background-color: rgba(0,0,0,0.2)">
            <mdb-container fluid>
                &copy; 2020 Copyright: <a href="https://www.facebook.com/delsupercompy-100110658389853/"> delsuper.com.py </a>
            </mdb-container>
        </div>
        <div class="modal fade" id="imagemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <img src="/img/cobertura.jpg" class="imagepreview" style="width: 100%;" >
                    </div>
                </div>
            </div>
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
        },
        methods:{
            mostrarPopUp (){
                // $('.imagepreview').attr('src', $(this).find('img').attr('src'));
                $('#imagemodal').modal('show');
            }
        }
    }
</script>

<style scoped>
    footer.page-footer a {
        color: #fff;
    }
</style>
