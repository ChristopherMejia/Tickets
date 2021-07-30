<?php
/**
 * Created by PhpStorm.
 * User: Freddy Arvizu
 * Date: 13/12/2019
 * Time: 12:56 PM
 */


$title = $_GET['file'] . " | ";
$page = "ticket-detail?f=" . $_GET['file'];

include "includes/head.php";
include "config/presence.php";
include "includes/sidebar.php";


$infoFade = mysqli_query($con, $queryModule = "SELECT m.name AS moduleName, m.description FROM modules m
                                                 LEFT JOIN fades f ON m.id = f.module_id
                                                 WHERE f.name='" . $_GET['file'] . "' ");
$rowFade = mysqli_fetch_array($infoFade);

?>
<div class="right_col" role="main"><!-- page content -->
    <div class="">
        <div class="page-title">
            <div class="clearfix"></div>
            <div class="col-md-12 col-sm-12 col-xs-12">
                <?php
                include("modal/new_user.php");
                include("modal/upd_user.php");
                ?>
                <div class="x_panel">
                    <div class="x_title">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="dashboard">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="modules">Modulos</a></li>
                            <li class="breadcrumb-item"><a
                                        href="modules?module=<?php echo $rowFade['moduleName'] ?>"><?php echo $rowFade['description'] ?></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page"> <?php echo $_GET['file']; ?> </li>
                        </ol>

                        <input type="hidden" id="file" value="<?php echo $_GET['file']; ?>">
                        <ul class="nav navbar-right panel_toolbox">
                            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                            </li>
                            <li><a class="close-link"><i class="fa fa-close"></i></a>
                            </li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>

                    <!-- form search -->
                    <div class="x_content filters">
                        <form class="form-inline">
                            <div class="form-group">
                                <label for="filets">Buscar por:</label>
                                <select class="form-control filter-sel" id="search_for_detail">
                                    <option selected disabled value="">-- Selecciona --</option>
                                    <option value="null">Ninguno</option>
                                    <option value="Titulo">Titulo</option>
                                    <option value="Creado">Creado por</option>
                                    <option class="action-hidden" value="Asignado">Asignado a</option>
                                    <option class="action-hidden" value="Estatus">Estatus</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" id="search_word_detail"
                                       placeholder="..."
                                       onkeyup='load_detail(1);'>
                            </div>
                            <select class="form-control float-right" onchange="load_detail(1);" id="entries_detail">
                                <option value="20">20</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                                <option value="500">500</option>
                            </select>
                        </form>
                    </div>
                    <!-- end form search -->
                    <div class="x_content">
                        <div class="table-responsive">
                            <!-- ajax -->
                            <div id="resultados"></div><!-- Carga los datos ajax -->
                            <div class='outer_div_detail'></div><!-- Carga los datos ajax -->
                            <!-- /ajax -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div><!-- /page content -->
<?php
include "includes/footer.php"
?>

<script type="text/javascript" src="js/fades.js"></script>
