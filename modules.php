<?php
/**
 * Created by PhpStorm.
 * User: Freddy Arvizu
 * Date: 11/12/2019
 * Time: 06:02 PM
 */

$title = "Modulos | ";
$page = "modules";
include "includes/head.php";
//include "config/presence.php";
include "includes/sidebar.php";


if (empty($_GET['n'])) {
    $modules = mysqli_query($con, $query="SELECT * FROM modules ORDER BY name ASC");

    ?>
    <div class="right_col" role="main"> <!-- page content -->
        <div class="">
            <div class="page-title">
                <div class="clearfix"></div>
                <div class="col-md-12 col-sm-12 col-xs-12">

                    <div class="x_panel">
                        <div class="x_title">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="dashboard">Dashboard</a></li>

                                <li class="breadcrumb-item active" aria-current="page">Modulos</li>
                            </ol>
                            <ul class="nav navbar-right panel_toolbox">
                                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                </li>
                                <li><a class="close-link"><i class="fa fa-close"></i></a>
                                </li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <div class="row">
                                <?php foreach ($modules as $m) { ?>
                                    <div class="col-md-4 col-lg-4 col-xs-6 block-fades">
                                        <a href="modules?n=<?php echo $m['name']; ?>">
                                            <i class="fa fa-folder fa-4x" aria-hidden="true"></i>
                                            <p> <?php echo $m['name']; ?></p>
                                        </a>
                                    </div>
                                <?php }; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- /page content -->
    <?php
    include "modal/new_module.php";
} else {
    $infoModule = mysqli_query($con, $queryModule = "SELECT * FROM modules WHERE name='" . $_GET['n'] . "' ORDER BY name ASC");
    $rowModule = mysqli_fetch_array($infoModule);
//    var_dump($queryModule);
    ?>
    <div class="right_col" role="main"><!-- page content -->
        <div class="">
            <div class="page-title">
                <div class="clearfix"></div>
                <div class="col-md-12 col-sm-12 col-xs-12">

                    <div class="x_panel">
                        <div class="x_title">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="dashboard">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="modules">Modulos</a></li>

                                    <li class="breadcrumb-item active" aria-current="page">
                                        <?php echo $rowModule['name']; ?> ( <?php echo $rowModule['description']; ?> )
                                    </li>
                                </ol>
                            </nav>

                            <input type="hidden" id="module" value="<?php echo $_GET['n']; ?>">
                            <ul class="nav navbar-right panel_toolbox">
                                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                </li>
                                <li><a class="close-link"><i class="fa fa-close"></i></a>
                                </li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>

                        <!-- form search -->
                        <form class="form-horizontal" id="datos_cotizacion">
                            <div class="form-group row">
                                <label for="q" class="col-md-2 control-label">Buscar</label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" id="q"
                                           placeholder="..."
                                           onkeyup='load(1);'>
                                </div>
                                <div class="col-md-3">
<!--                                    <button type="button" class="btn btn-default" onclick='load(1);'>-->
<!--                                        <span class="glyphicon glyphicon-search"></span> Buscar-->
<!--                                    </button>-->
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
    </div><!-- /page content -->

    <?php
    include "modal/new_fade.php";
}
include "includes/footer.php";

?>

<script type="text/javascript" src="js/modules.js"></script>
<script type="text/javascript" src="js/fades.js"></script>

</body>
</html>
