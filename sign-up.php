<?php
/**
 * Created by PhpStorm.
 * User: Freddy Arvizu
 * Date: 23/08/2019
 * Time: 07:20 PM
 */

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Regístrate </title>
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
                <form action="" method="post" id="add_user" name="add_user">
                    <h1>Regístrate</h1>
                    <div id="result_user" class="response_result"></div>
<!--                    <div id="result_register"></div>-->
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback">
                            <input name="name" required type="text" class="form-control" placeholder="Nombre">
                            <span class="fa fa-user form-control-feedback right" aria-hidden="true"></span>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback">
                            <input name="lastname" type="text" class="form-control" placeholder="Apellidos" required>
                            <span class="fa fa-user form-control-feedback right" aria-hidden="true"></span>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback">
                            <input name="email" type="text" class="form-control"
                                   placeholder="Correo Electronico" required>
                            <span class="fa fa-envelope form-control-feedback right" aria-hidden="true"></span>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback" style="display: none">
                            <select class="form-control" required name="status">
                                <option value="1" selected>Activo</option>

                            </select>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback">
                            <label for="rfc">RFC Empresa</label>
                            <input name="rfc" type="text" class="form-control has-feedback-left rfc"
                                   placeholder="AAA10101AAA" maxlength="13" required>
                            <span class="fa fa-text-width form-control-feedback right" aria-hidden="true"></span>
                        </div>

                    </div>
                    <div class="row" style="display: none">
                        <div class="col-md-6 col-sm-6 col-xs-6 form-group has-feedback">
                            <label for="rol">Rol</label>
                            <select class="form-control" name="rol" required>
                                <option value="4" selected>Externo</option>
                            </select>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-6 form-group has-feedback">
                            <label for="rol">Departamento</label>
                            <select class="form-control" required name="dpto">
                                <option value="4" selected>Customer</option>

                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <hr>
                        <div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback">
                            <label for="rol">Accesos</label>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback">
                            <input id="username" name="username" type="text" class="form-control" placeholder="Usuario">
                            <span class="fa fa-user-circle form-control-feedback right" aria-hidden="true"></span>
                        </div>

                        <div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback">
                            <input id="password" name="password" required type="password" class="form-control"
                                   placeholder="Contraseña">
                            <span class="fa fa-key form-control-feedback right" aria-hidden="true"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <button id="save_data" type="submit" class="btn btn-default">Enviar</button>
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
<script type="text/javascript" src="js/users.js"></script>
</body>
</html>
