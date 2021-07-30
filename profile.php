<?php
/**
 * Created by PhpStorm.
 * User: Freddy Arvizu
 * Date: 02/08/2019
 * Time: 10:35 AM
 */

$title = "Profile - ";
$page = "profile";
include "includes/messages.php";
include "includes/head.php";
include "config/presence.php";
include "includes/sidebar.php";


$query = "SELECT COUNT(id) AS NumberFiles FROM upload_data WHERE user_id =" . $user_id;
//var_dump($query);
$result = mysqli_query($con, $query);
$row = mysqli_fetch_assoc($result);


?>
<div class="right_col" role="main"> <!-- page content -->
    <div class="">
        <div class="page-title">

            <br><br>
            <div class="row">
                <div class="col-md-4">
                    <div class="card card-user">
                        <div class="image">
                            <div id="img-profile"
                                 style="background: url('images/profiles/<?php echo $profile_pic; ?>')"></div>

                        </div>
                        <div class="card-body">
                        <span class="btn btn-my-button btn-file">
                            <form method="post" id="formulario" enctype="multipart/form-data">
                            Cambiar Imagen de perfil: <input type="file" name="file">
                                <input type="hidden" name="user_id" value="<?php echo $user_id ?>">
                            </form>
                        </span>
                            <div id="respuesta"></div>
                        </div>
                        <!--                        <div class="card-footer">-->
                        <!--                            <hr>-->
                        <!--                            <div class="button-container">-->
                        <!--                                <div class="row">-->
                        <!--                                    <div class="col-md-4 mr-auto">-->
                        <!--                                        <h5>-->
                        <!--                                            --><?php //echo $row['NumberFiles'] ?>
                        <!--                                            <br>-->
                        <!--                                            <small>Archivos</small>-->
                        <!--                                        </h5>-->
                        <!--                                    </div>-->
                        <!--                                    <div class="col-md-4 mr-auto">-->
                        <!--                                        <h5>-->
                        <!--                                            0-->
                        <!--                                            <br>-->
                        <!--                                            <small>Tickets</small>-->
                        <!--                                        </h5>-->
                        <!--                                    </div>-->
                        <!--                                    <div class="col-md-4 mr-auto">-->
                        <!--                                        <h5>-->
                        <!--                                            0-->
                        <!--                                            <br>-->
                        <!--                                            <small>Proyectos</small>-->
                        <!--                                        </h5>-->
                        <!--                                    </div>-->
                        <!--                                </div>-->
                        <!--                            </div>-->
                        <!--                        </div>-->
                    </div>
                </div>

                <div class="col-md-8 col-xs-12 col-sm-12">

                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Informacion personal</h2>
                            <ul class="nav navbar-right panel_toolbox">
                                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                </li>
                                <li><a class="close-link"><i class="fa fa-close"></i></a>
                                </li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <br/>
                            <form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left"
                                  action="action/upd_profile.php" method="post">
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Nombre
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input type="text" name="name" id="first-name"
                                               class="form-control col-md-7 col-xs-12" value="<?php echo $name; ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Correo
                                        electronico
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input type="text" id="last-name" name="email"
                                               class="form-control col-md-7 col-xs-12" value="<?php echo $email; ?>">
                                    </div>
                                </div>

                                <br><br><br>
                                <h2 style="padding-left: 50px">Cambiar Contraseña</h2>

                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Contraseña antigua
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input id="birthday" name="password"
                                               class="date-picker form-control col-md-7 col-xs-12" type="text"
                                               placeholder="**********">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Nueva contraseña
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input id="birthday" name="new_password"
                                               class="date-picker form-control col-md-7 col-xs-12" type="text">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Confirmar contraseña nueva
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input id="birthday" name="confirm_new_password"
                                               class="date-picker form-control col-md-7 col-xs-12" type="text">
                                    </div>
                                </div>
                                <div class="ln_solid"></div>
                                <div class="form-group">
                                    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                        <button type="submit" name="token" class="btn btn-success">Actualizar Datos
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div><!-- /page content -->

<?php include "includes/footer.php" ?>
<script>
    $(function () {
        $("input[name='file']").on("change", function () {
            var formData = new FormData($("#formulario")[0]);
            var ruta = "action/upload-profile.php";
            $.ajax({
                url: ruta,
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function (datos) {
                    $("#respuesta").html("<?php echo $success_img; ?>");

                    $('#img-profile').css('background-image', 'url("images/profiles/' + datos + '")');

                },
                complete: function () {


                    setTimeout(function () {
                        $("#respuesta").fadeOut("slow");
                    }, 4000);

                }
            });
        });
    });
</script>

</body>
</html>