<?php
$page = "categories";
$title = "Categorias | ";
include "includes/head.php";
include "config/presence.php";
include "includes/sidebar.php";

?>

<div class="right_col" role="main"><!-- page content -->
    <div class="">
        <div class="page-title">
            <div class="clearfix"></div>
            <div class="col-md-12 col-sm-12 col-xs-12">
                <?php
                include("modal/new_category.php");
                include("modal/upd_category.php");
                ?>
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Categorias </h2>
                        <ul class="nav navbar-right panel_toolbox">
                            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                            </li>
                            <li><a class="close-link"><i class="fa fa-close"></i></a>
                            </li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>

                    <!-- form search -->
                    <form class="form-horizontal" id="category_expence">
                        <div class="form-group row">
                            <label for="q" class="col-md-2 control-label">Nombre</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" id="q" placeholder="Nombre de la categoria"
                                       onkeyup='load(1);'>
                            </div>
                            <div class="col-md-3">
                                <button type="button" class="btn btn-default" onclick='load(1);'>
                                    <span class="glyphicon glyphicon-search"></span> Buscar
                                </button>
                                <span id="loader"></span>
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


<?php include "includes/footer.php" ?>

<script type="text/javascript" src="js/category.js"></script>

<script>

</script>