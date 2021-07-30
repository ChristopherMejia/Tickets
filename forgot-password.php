<?php
/**
 * Created by PhpStorm.
 * User: Freddy Arvizu
 * Date: 16/10/2019
 * Time: 16:27 PM
 */

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>¿Olvidaste tu Contraseña?</title>
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
            $invalid = sha1(md5("contrasena y email invalido"));
            if (isset($_GET['invalid']) && $_GET['invalid'] == $invalid) {
                echo "<div class='alert alert-danger alert-dismissible fade in' role='alert'>
                                <strong>Error!</strong> Contraseña o correo Electrónico invalido
                                </div>";
            }
            ?>
            <section class="login_content">
                <form action="" method="post" id="forgot_pass" name="forgot_pass" class="forgot">
                    <h1>Recupera tu cuenta de SO Intesys</h1>

                    <div id="result_forgot" class="response_result"></div>

                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback">
                            <input name="username" id="username" type="text" class="form-control"
                                   placeholder="Usuario" required>
                            <span class="fa fa-user form-control-feedback right" aria-hidden="true"></span>
                        </div>
                        <div class="col-md-12 form-group">
                            <p>
                                Si has olvidado tu contraseña, sigue estos pasos para recuperar tu cuenta de SO Intesys.
                                De esta manera, podrás usar
                                servicios como Atención a clientes
                            </p>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback" style="display: none">
                            <select class="form-control" required name="status">
                                <option value="1" selected>Activo</option>

                            </select>
                        </div>

                    </div>

                    <div class="form-group">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <button id="save_data" type="submit" class="btn btn-default">Recuperar</button>
                        </div>
                    </div>

                    <div class="clearfix"></div>
                    <div class="separator">
                        <div align="center">
                            <i class="fa fa-caret-left register" aria-hidden="true"></i>
                            <a class="register" href="login">Regresar</a>
                        </div>
                        <div class="clearfix"></div>
                        <br/>
                        <div>
                            <h1><i class="fa fa-ticket"></i> Intesystem</h1>
                            <p>
                                <!-- helloworld -->
                            </p>
                        </div>
                    </div>
                </form>
            </section>
        </div>
    </div>
</div>
<!-- jQuery -->
<script src="js/jquery/dist/jquery.min.js"></script>
<script type="text/javascript" src="js/password.js"></script>
</body>
</html>
