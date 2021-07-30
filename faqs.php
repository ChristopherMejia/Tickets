<?php
/**
 * Created by PhpStorm.
 * User: Freddy Arvizu
 * Date: 29/10/2019
 * Time: 01:12 PM
 */

$page = "faqs";
$title = "FAQs | ";
include "includes/head.php";
include "config/presence.php";
include "includes/sidebar.php";


?>


<div class="right_col" role="main"><!-- page content -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="dashboard">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Preguntas frecuentes</li>
        </ol>
    </nav>
    <div class="container">
        <div class="page-title">
            <div class="container">
                <div class="accordion-option">
                    <h3 class="title">FAQ´s</h3>
                    <a href="javascript:void(0)" class="toggle-accordion active" accordion-id="#accordion"></a>
                </div>
                <div class="clearfix"></div>
                <div class="panel-group faqs" id="accordion" role="tablist" aria-multiselectable="true">
                    <div class="panel panel-default">
                        <div class="panel-heading" role="tab" id="headingOne">
                            <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne"
                               aria-expanded="true" aria-controls="collapseOne">
                                <h4 class="panel-title">
                                    Creación de usuarios.
                                </h4>
                            </a>
                        </div>

                        <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel"
                             aria-labelledby="headingOne">
                            <div class="panel-body">
                                <p>
                                    Ingresar a la página de tickets: <a href="http://soporte.intesystem.net/"
                                                                        target="_blank">http://soporte.intesystem.net/</a>
                                    Dar clic en la opción de <b>“regístrate”</b>
                                </p>
                                <img src="images/faq/1a_1.png" class="img-responsive">
                                <p>
                                    Nos llevará a la siguiente ventana, en la que deberemos registrar los datos del
                                    usuario.
                                </p>
                                <img src="images/faq/1a_2.png" class="img-responsive">
                                <p>
                                    En el campo “RFC Empresa” ingresar el RFC perteneciente a cualquier empresa del
                                    grupo en el que el usuario labore.<br><br>

                                    En los accesos ingresar un nombre de usuario y una contraseña, recuerde anotarla
                                    en algún lugar seguro para no olvidarla, la contraseña puede ser tan complicada
                                    o sencilla como el usuario desee.<br><br>

                                    Después de haber llenado los datos seleccionar el botón de enviar.
                                </p>
                                <img src="images/faq/1a_3.png" class="img-responsive">
                                <p>
                                    Si los datos han sido llenados exitosamente el siguiente mensaje nos aparecerá y
                                    llegará una confirmación al correo electrónico registrado.
                                </p>
                                <img src="images/faq/1a_4.png" class="img-responsive">
                                <p>
                                    En caso contrario revisar los datos ingresados e intentar de nuevo.<b></b>
                                    Después en la parte inferior del formulario hacemos clic en el botón de <b>“regresar”</b>
                                    o podemos volver a seguir los pasos anteriores para registrar más usuarios.
                                </p>
                                <img src="images/faq/1a_5.png" class="img-responsive">
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="headingTwo">
                                <a class="collapsed" role="button" data-toggle="collapse"
                                   data-parent="#accordion"
                                   href="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                                    <h4 class="panel-title">
                                        Creación de ticket
                                    </h4>
                                </a>
                            </div>
                            <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel"
                                 aria-labelledby="headingTwo">
                                <div class="panel-body">
                                    <p>
                                        Ingresar al portal de tickets <a href="http://soporte.intesystem.net/"
                                                                         target="_blank">http://soporte.intesystem.net/</a>
                                        con sus credenciales.
                                    </p>
                                    <img src="images/faq/2a_1.png" class="img-responsive">
                                    <p>
                                        De la pagina principal, o Dashboard, podemos crear un ticket accediendo
                                        mediante al menú de la izquierda al apartado de tickets.
                                    </p>
                                    <img src="images/faq/2a_2.png" class="img-responsive">
                                    <p>
                                        Una vez en la pantalla de tickets podemos crear un nuevo ticket dando clic
                                        en el botón con el signo de <b>“mas”</b> en la parte inferior derecha de la
                                        pantalla.
                                    </p>
                                    <img src="images/faq/2a_3.png" class="img-responsive">
                                    <p>Aparecerá una ventana emergente en la que tendremos que llenar los siguientes
                                        datos que solicita. </p>
                                    <img src="images/faq/2a_4.png" class="img-responsive">
                                    <p>
                                        Del menú desplegable en empresa seleccionaremos la empresa de la cual
                                        estamos expidiendo el ticket, después le daremos un titulo a nuestro ticket
                                        y en el cuadro de texto de descripción daremos detalles del problema o de la
                                        razón por la cual se esta creando este ticket. Si existieran imágenes o
                                        capturas de pantalla que pudieran ayudar a mejor ejemplificar el problema se
                                        podrán anexar dando clic en el botón de elegir archivos. Una vez completados
                                        estos datos damos clic en el botón guardar y nuestro ticket será creado.
                                    </p>
                                    <img src="images/faq/2a_5.png" class="img-responsive">
                                    <p>
                                        Un correo de confirmación de la creación del ticket será enviado al usuario.<br>
                                        Podemos ver el status y actualizaciones del ticket en esta pantalla.

                                    </p>
                                    <img src="images/faq/2a_6.png" class="img-responsive">
                                    <p>
                                        Puede también, haciendo clic en el icono del ojo ver a mayor detalle el
                                        estatus de cada ticket y hay una ventana para intercambiar comentarios con
                                        el equipo de soporte de intesystem en caso de haber alguna duda. Y en el
                                        mismo podrá enviar más archivos adjuntos en caso de ser necesario.
                                    </p>
                                    <img src="images/faq/2a_7.png" class="img-responsive">
                                    <p>
                                        Intesystem trabajará para solucionar su ticket de la manera más rápida
                                        posible.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="headingThree">
                                <a class="collapsed" role="button" data-toggle="collapse"
                                   data-parent="#accordion"
                                   href="#collapseThree" aria-expanded="true" aria-controls="collapseThree">
                                    <h4 class="panel-title">
                                        Recuperación de contraseña
                                    </h4>
                                </a>
                            </div>
                            <div id="collapseThree" class="panel-collapse collapse" role="tabpanel"
                                 aria-labelledby="headingThree">
                                <div class="panel-body">
                                    <p>
                                        Si olvidamos la contraseña para acceder al programa de tickets es posible
                                        reestablecerla fácilmente.<br><br>
                                        En la pagina principal de http://soporte.intesystem.net/login dar clic en la
                                        opción de <b>“¿olvidaste tu contraseña?”</b>
                                    </p>
                                    <img src="images/faq/3a_1.png" class="img-responsive">
                                    <p>
                                        En la siguiente pantalla nos pedirá el nombre de usuario y después hacer
                                        clic en recuperar.
                                    </p>
                                    <img src="images/faq/3a_2.png" class="img-responsive">
                                    <p>
                                        Un correo electrónico será enviado a la dirección registrada.<br><br>
                                        Abrir el correo que les fue enviado y dar clic en <b>“cambia tu
                                            contraseña”</b>

                                    </p>
                                    <img src="images/faq/3a_3.png" class="img-responsive">
                                    <p>
                                        Esto nos mandara a una página web en la que podremos cambiar nuestra
                                        contraseña, no hay requerimientos para la contraseña puede ser tan simple o
                                        complicada como desee. Una vez ingresada la nueva contraseña dar clic en
                                        reestablecer.
                                    </p>
                                    <img src="images/faq/3a_4.png" class="img-responsive">
                                    <p>
                                        Aparecerá una notificación indicando que el cambio fue realizado
                                        exitosamente y ahora podemos regresar e ingresar con nuestras nuevas
                                        credenciales.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>

        </div><!-- /page content -->
    </div>
</div>
<?php
include "includes/footer.php";
?>
<script src="js/faqs.js"></script>

</body>
</html>
