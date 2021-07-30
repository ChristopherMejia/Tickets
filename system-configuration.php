<?php
/**
 * Created by PhpStorm.
 * User: Freddy Arvizu
 * Date: 19/11/2019
 * Time: 12:47 PM
 */

$page = "system-configuration";
$title = "Configuración del sistema - ";

include "includes/head.php";
include "includes/sidebar.php";


$sql = mysqli_query($con, $query = "SELECT * FROM notices;");
$c = mysqli_fetch_array($sql);


?>

<div class="right_col" role="main">
    <!-- page content -->
    <div class="">
        <div class="page-title">

            <!-- content  -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="dashboard">Dashboard</a></li>

                    <li class="breadcrumb-item active" aria-current="page">Configuraciòn</li>
                </ol>
            </nav>
            <div id="exTab1" class="container">
                <ul class="nav nav-pills">
                    <li class="active">
                        <a href="#a3" data-toggle="tab"> Avisos</a>
                    </li>
                    <li class="">
                        <a href="#a1" data-toggle="tab"> Generales</a>
                    </li>
                    <li class="action-hidden">
                        <a href="#a2" data-toggle="tab"> Logs</a>
                    </li>
                </ul>
                <div class="tab-content clearfix">
                    <div class="tab-pane" id="a1">
                        <div class="container">

                            <form class="form-horizontal form-label-left input_mask" method="post" id="frm_grls"
                                  name="frm_grls">

                                <div class="row grls">
                                    <div class="col-md-2">
                                        <p class="grls-text">Modo Mantenimiento</p>
                                    </div>
                                    <div class="col-md-10">
                                        <!-- Group of default radios - option 1 -->
                                        <div class="custom-control">
                                            <input type="radio" class="checkbox-inline custom-control-input" id="mainta"
                                                   name="groupMaint"
                                                <?php if ($dataConfiguration['maintenance'] == 1) { ?> checked <?php } ?>
                                                   value="1">
                                            <label class="custom-control-label" for="mainta">Activado</label>
                                        </div>

                                        <!-- Group of default radios - option 2 -->
                                        <div class="custom-control">
                                            <input type="radio" class="checkbox-inline custom-control-input" id="maintd"
                                                   name="groupMaint"
                                                <?php if ($dataConfiguration['maintenance'] == 0) { ?> checked <?php } ?>
                                                   value="0">
                                            <label class="custom-control-label" for="maintd">Desactivado</label>
                                        </div>
                                    </div>
                                </div>


                                <div class="panel-footer text-right">
                                    <button type="submit" id="save_data" class="btn btn-success pull-right">Guardar
                                    </button>
                                    <div id="result"></div>
                                </div>
                            </form>

                        </div>
                    </div>
                    <div class="tab-pane" id="a2">
                        <div class="container">

                            <div class="row">
                                <div class="col-md-2 ">
                                    <div class="col sidebar x_panel mb-3">
                                        <h3>
                                            <i class="fa fa-bug" aria-hidden="true"></i>
                                            Control de
                                            errores
                                        </h3>
                                        <p class="text-muted"><i>Intesystem</i></p>
                                        <div class="list-group div-scroll">
                                            <button class="list-group-item  llv-active" onclick='load(1);'>
                                                ALL
                                            </button>
                                            <button class="list-group-item " onclick='load(1);'>
                                                ACTIONS
                                            </button>
                                            <button class="list-group-item" onclick='load(1);'>
                                                ERRORS
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-10">
                                    <div class="x_panel">
                                        <div class="x_title">
                                            <h2>LOG´s</h2>
                                            <ul class="nav navbar-right panel_toolbox">
                                                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                                </li>
                                                <li><a class="close-link"><i class="fa fa-close"></i></a>
                                                </li>
                                            </ul>
                                            <div class="clearfix"></div>
                                        </div>

                                        <!-- form search -->
                                        <form class="form-horizontal"  id="datos_cotizacion">
                                            <div class="form-group row">
                                                <label for="q" class="col-md-2 control-label">Buscar</label>
                                                <div class="col-md-4">
                                                    <input type="text" class="form-control" id="q" placeholder="..."
                                                           onkeyup='load(1);'>
                                                </div>
                                                <div class="col-md-3">
                                                    <button type="button" class="btn btn-default"
                                                            onclick='load(1);'>
                                                        <span class="glyphicon glyphicon-search"></span> Buscar
                                                    </button>
                                                    <!-- <span id="loader"></span> -->
                                                </div>
                                            </div>
                                        </form>
                                        <!-- end form search -->

                                        <div class="x_content">
                                            <div class="table-responsive">
                                                <!-- ajax -->
                                                <div id="resultados"></div><!-- Carga los datos ajax -->
                                                <div class='outer_div'></div><!-- Carga los datos ajax -->
                                                <!-- /ajax -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                    <div class="tab-pane active" id="a3">
                        <div class="container">
                            <h2>Avisos / Notificaciones / Errores</h2>
                            <br>
                            <div class="jumbotron container">
                                <h1>Paso 1</h1>
                                <hr class="my-4">
                                <p>llene todos los campos del formulario y guarde.</p>

                            </div>

                            <form class="form-horizontal form-label-left input_mask" method="post" id="frm_info_notices"
                                  name="frm_info_notices">
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="title">Titulo:</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="title" name="title"
                                               value="<?php echo $c['title'] ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="sdescription">Descripción Corta:</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="sdescription"
                                               name="sdescription"
                                               value="<?php echo $c['short_description'] ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="error">Error:</label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control" name="error"
                                                  id="error"><?php echo $c['error'] ?></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="description">Description:</label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control" name="description"
                                                  id="description"><?php echo $c['description'] ?></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="solution">Solution:</label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control" name="solution"
                                                  id="solution"><?php echo $c['solution'] ?></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <div class="panel-footer text-right">
                                            <button type="submit" id="save_info_notices"
                                                    class="btn btn-success pull-right">Guardar
                                            </button>
                                        </div>
                                        <div id="result_info_notices"></div>
                                    </div>
                                </div>
                            </form>


                            <div class="jumbotron container">
                                <h1>Paso 2</h1>
                            </div>

                            <form class="form-horizontal form-label-left input_mask" method="post"
                                  id="frm_active_notices"
                                  name="frm_active_notices">

                                <div class="form-group">
                                    <label class="control-label col-sm-2" for="title">Puesta en escena</label>
                                    <div class="col-sm-10">
                                        <!-- Group of default radios - option 1 -->
                                        <div class="custom-control">
                                            <input type="radio" class="checkbox-inline custom-control-input"

                                                   name="groupNotices"
                                                <?php if ($c['active'] == 1) { ?> checked <?php } ?>
                                                   value="1">
                                            <label class="custom-control-label" for="mainta">Activado</label>
                                        </div>

                                        <!-- Group of default radios - option 2 -->
                                        <div class="custom-control">
                                            <input type="radio" class="checkbox-inline custom-control-input"

                                                   name="groupNotices"
                                                <?php if ($c['active'] == 0) { ?> checked <?php } ?>
                                                   value="0">
                                            <label class="custom-control-label" for="maintd">Desactivado</label>
                                        </div>
                                    </div>
                                </div>

                            </form>

                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>
<!-- /page content -->

<div class="clearfix heigth_f"></div>
<?php
include "includes/footer.php";
?>
<script type="text/javascript" src="js/configuration.js"></script>

</body>
</html>
