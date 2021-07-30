<?php

session_start();

include "config/config.php";

if (isset($_SESSION['user_id']) && $_SESSION !== null) {
    header("location: dashboard");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Iniciar Sesión </title>
    <link href=favicon.ico rel="shortcut icon">
    <!-- Bootstrap -->
    <link href="css/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="css/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="css/nprogress/nprogress.css" rel="stylesheet">
    <!-- Animate.css -->
    <link href="css/animate.css/animate.min.css" rel="stylesheet">
    <!-- Custom Theme Style -->
    <link href="css/custom.min.css" rel="stylesheet">

</head>
<body class="login">
<div>
    <a class="hiddenanchor" id="signup"></a>
    <a class="hiddenanchor" id="signin"></a>
    <div class="login_wrapper">
        <div class="animate form login_form">
            <?php
            $invalid = sha1(md5("contrasena y/o usuario invalido"));
            if (isset($_GET['invalid']) && $_GET['invalid'] == $invalid) {
                echo "<div class='alert alert-danger alert-dismissible fade in' role='alert'>
                                <strong>Error!</strong> Contraseña o correo Electrónico invalido
                                </div>";
            }
            ?>
            <section class="login_content">
                <form action="action/login.php" method="post" autocomplete="off" class="animated flipInX delay-2s">
                    <h1>Iniciar Sesión</h1>
                    <div>
                        <input type="text" name="username" class="form-control" placeholder="Usuario" autofocus
                               required/>
                    </div>
                    <div>
                        <input type="password" name="password" class="form-control" placeholder="Contraseña" required/>
                    </div>
                    <div>
                        <button type="submit" name="token" value="Login" class="btn btn-default">Iniciar Sesion</button>
                        <a class="reset_pass" href="forgot-password">Olvidaste Tu contraseña?</a>
                    </div>
                    <div class="clearfix"></div>
                    <div class="separator">
                        <div align="center">

                            <a class="register" href="sign-up">Regístrate</a>

                        </div>
                        <div class="clearfix"></div>
                        <br/>
                        <div>
                            <h1 class="animated bounceInLeft delay-2s">
                                <i class="fa fa-ticket"></i>
                                Intesystem
                            </h1>
                            <p>
                                <a class="register" href="#" data-toggle="modal" data-target="#md_help">
                                    <i class="fa fa-info-circle fa-4x animated bounceInUp delay-2s" aria-hidden="true"
                                       title="¿Necesitas Ayuda?"></i>
                                </a>
                                <!-- helloworld -->
                            </p>
                        </div>
                    </div>
                </form>
            </section>
        </div>
    </div>
</div>
<!-- Modal -->
<div id="md_help" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"> Creación de usuarios</h4>
            </div>
            <div class="modal-body">
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
            <div class="modal-footer">
                <!--                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
            </div>
        </div>

    </div>
</div>
<!-- jQuery -->
<script src="js/jquery/dist/jquery.min.js"></script>
<!--<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>-->
<!-- Bootstrap -->
<script src="css/bootstrap/dist/js/bootstrap.min.js"></script>
<!--<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>-->
</body>
</html>
