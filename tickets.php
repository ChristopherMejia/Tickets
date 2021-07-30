<?php

$title = "Tickets | ";
$page = "tickets";
include "includes/head.php";
include "config/presence.php";
include "includes/sidebar.php";

$user_id = $_SESSION['user_id'];

$status = mysqli_query($con, "SELECT * FROM status ");
$workers = mysqli_query($con, "SELECT * FROM users WHERE role <= 3 ");
$workers_test = mysqli_query($con, "SELECT * FROM users WHERE role <= 2 ");
$modules = mysqli_query($con, "SELECT * FROM modules ");

?>

<div class="right_col" role="main"><!-- page content -->
    <div class="">

        <div id="exTab1" class="container">
            <ul class="nav nav-pills">
                <li class="active">
                    <a href="#tickets" data-toggle="tab"><i class="fa fa-ticket" aria-hidden="true"></i> Tickets</a>
                </li>
                <li class="action-hidden">
                    <a href="#slopes" data-toggle="tab"><i class="fa fa-clock-o" aria-hidden="true"></i> Sin Asignar</a>
                </li>
                <li class="action-hidden">
                    <a href="#tests" data-toggle="tab"><i class="fa fa-check-square-o" aria-hidden="true"></i> Pruebas
                    </a>
                </li>
                <li class="action-hidden super">
                    <a href="#all" data-toggle="tab"><i class="fa fa-th" aria-hidden="true"></i> Todos</a>
                </li>
            </ul>

            <div class="tab-content clearfix">
                <div class="tab-pane active" id="tickets">
                    <!-- Tickets -->
                    <div class="page-title">
                        <div class="clearfix"></div>
                        <div class="col-md-12 col-sm-12 col-xs-12">

                            <div class="x_panel">
                                <div class="x_title">
                                    <h2>Mis Tickets</h2>
                                    <ul class="nav navbar-right panel_toolbox">
                                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                        </li>
                                        <li><a class="close-link"><i class="fa fa-close"></i></a>
                                        </li>
                                    </ul>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="x_content filters" style="margin-bottom: 2px;">
                                    <form class="form-inline" onsubmit="event.preventDefault();">
                                        <div class="form-group">
                                            <label for="filets">Buscar por:</label>
                                            <select class="form-control filter-sel" id="search_for">
                                                <option selected disabled value="">Selecciona una opción</option>
                                                <option value="Titulo">Titulo</option>
                                                <option value="Prioridad">Prioridad</option>
                                                <option value="Empresa">Empresa</option>
                                                <option value="Creado">Creado por</option>
                                                <option class="action-hidden dev" value="Estatus">Estatus</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="search_word" placeholder="...">
                                            <button type="button" title="Agregar Filtro" class="btn btn-link" id="confirm" onclick="addFilter()">
                                                <i class="fa fa-plus" aria-hidden="true"></i> 
                                            </button>
                                        </div>
                                        <div class="form-group">
                                            <button type="button" class="btn btn-primary" onclick="aplicar(<?php echo $user_id ?>)">Buscar</button>
                                        </div>

                                        <div class="form-group">
                                            <span class="action-hidden">Asignado a:</span>
                                            <select class="form-control filter-sel action-hidden" id="search_who"
                                                    onchange="load(1);">
                                                <option selected value="null">-- Todos --</option>
                                                <?php foreach ($workers as $w): ?>
                                                    <option value="<?php echo $w['id'] ?>"><?php echo $w['name']; ?></option>
                                                <?php endforeach; ?>

                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <span class="action-hidden">Modulo:</span>
                                            <select class="form-control filter-sel action-hidden" id="module"
                                                    onchange="load(1);">
                                                <option selected value="null">-- Todos --</option>
                                                <?php foreach ($modules as $m): ?>
                                                    <option value="<?php echo $m['id'] ?>"><?php echo $m['name']; ?></option>
                                                <?php endforeach; ?>

                                            </select>
                                        </div>

                                        <select class="form-control float-right" onchange="load(1);" id="entries">
                                            <option value="20">20</option>
                                            <option value="50">50</option>
                                            <option value="100">100</option>
                                            <option value="500">500</option>
                                        </select>

                                        <div class="input-group-prepend" style="margin-top: 1%; display: flex;" id="new">
                                        </div>

                                    </form>
                                </div>
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
                <div class="tab-pane" id="slopes">
                    <!-- Pending -->
                    <div class="page-title action-hidden">
                        <div class="clearfix"></div>
                        <div class="col-md-12 col-sm-12 col-xs-12">

                            <div class="x_panel">
                                <div class="x_title">
                                    <h2>Tickets Pendientes por Asignar</h2>
                                    <ul class="nav navbar-right panel_toolbox">
                                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                        </li>
                                        <li><a class="close-link"><i class="fa fa-close"></i></a>
                                        </li>
                                    </ul>
                                    <div class="clearfix"></div>
                                </div>

                                <div class="x_content filters">
                                    <form class="form-inline">
                                        <div class="form-group">
                                            <label for="filets">Buscar por:</label>
                                            <select class="form-control filter-sel" id="search_for_pending">
                                                <option selected disabled value="">-- Selecciona --</option>
                                                <option value="null">Ninguno</option>
                                                <option value="Titulo">Titulo</option>
                                                <option class="action-hidden" value="Prioridad">Prioridad</option>
                                                <option value="Empresa">Empresa</option>
                                                <option value="Creado">Creado por</option>
                                                <!--                                                <option class="action-hidden" value="Asignado">Asignado a</option>-->
                                                <option class="action-hidden" value="Estatus">Estatus</option>

                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="search_word_pending"
                                                   placeholder="..."
                                                   onkeyup='load_pending(1);'>
                                        </div>
                                        <div class="form-group">
                                            <span class="action-hidden">Asignado a:</span>
                                            <select class="form-control filter-sel action-hidden"
                                                    id="search_who_pending" onchange="load_pending(1);">
                                                <option selected value="null">-- Todos --</option>
                                                <?php foreach ($workers as $w): ?>
                                                    <option value="<?php echo $w['id'] ?>"><?php echo $w['name']; ?></option>
                                                <?php endforeach; ?>

                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <span class="action-hidden">Modulo:</span>
                                            <select class="form-control filter-sel action-hidden" id="module_pending"
                                                    onchange="load_pending(1);">
                                                <option selected value="null">-- Todos --</option>
                                                <?php foreach ($modules as $m): ?>
                                                    <option value="<?php echo $m['id'] ?>"><?php echo $m['name']; ?></option>
                                                <?php endforeach; ?>

                                            </select>
                                        </div>
                                        <select class="form-control float-right" onchange="load_pending(1);"
                                                id="entries_pending">
                                            <option value="20">20</option>
                                            <option value="50">50</option>
                                            <option value="100">100</option>
                                            <option value="500">500</option>
                                        </select>
                                    </form>
                                </div>

                                <div class="x_content">
                                    <div class="table-responsive">
                                        <!-- ajax -->
                                        <div id="resultados_m"></div><!-- Carga los datos ajax -->
                                        <div class='outer_div_m'></div><!-- Carga los datos ajax -->
                                        <!-- /ajax -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="all">
                    <!-- All -->
                    <div class="page-title action-hidden super">
                        <div class="clearfix"></div>
                        <div class="col-md-12 col-sm-12 col-xs-12">

                            <div class="x_panel">
                                <div class="x_title">
                                    <h2>Todos los Tickets</h2>
                                    <ul class="nav navbar-right panel_toolbox">
                                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                        </li>
                                        <li><a class="close-link"><i class="fa fa-close"></i></a>
                                        </li>
                                    </ul>
                                    <div class="clearfix"></div>
                                </div>

                                <div class="x_content filters">
                                    <form class="form-inline">
                                        <div class="form-group">
                                            <label for="filets">Buscar por:</label>
                                            <select class="form-control filter-sel" id="search_for_all">
                                                <option selected disabled value="">-- Selecciona --</option>
                                                <option value="null">Ninguno</option>
                                                <option value="Titulo">Titulo</option>
                                                <option class="action-hidden" value="Prioridad">Prioridad</option>
                                                <option value="Empresa">Empresa</option>
                                                <option value="Creado">Creado por</option>
                                                <!--                                                <option class="action-hidden" value="Asignado">Asignado a</option>-->
                                                <option class="action-hidden" value="Estatus">Estatus</option>

                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="search_word_all"
                                                   placeholder="..."
                                                   onkeyup='load_all(1);'>
                                        </div>
                                        <div class="form-group">
                                            <span class="action-hidden">Asignado a:</span>
                                            <select class="form-control filter-sel action-hidden" id="search_who_all"
                                                    onchange="load_all(1);">
                                                <option selected value="null">-- Todos --</option>
                                                <?php foreach ($workers as $w): ?>
                                                    <option value="<?php echo $w['id'] ?>"><?php echo $w['name']; ?></option>
                                                <?php endforeach; ?>

                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <span class="action-hidden">Modulo:</span>
                                            <select class="form-control filter-sel action-hidden" id="module_all"
                                                    onchange="load_all(1);">
                                                <option selected value="null">-- Todos --</option>
                                                <?php foreach ($modules as $m): ?>
                                                    <option value="<?php echo $m['id'] ?>"><?php echo $m['name']; ?></option>
                                                <?php endforeach; ?>

                                            </select>
                                        </div>
                                        <select class="form-control float-right" onchange="load_all(1);"
                                                id="entries_all">
                                            <option value="20">20</option>
                                            <option value="50">50</option>
                                            <option value="100">100</option>
                                            <option value="500">500</option>
                                        </select>
                                    </form>
                                </div>

                                <div class="x_content">
                                    <div class="table-responsive">
                                        <!-- ajax -->
                                        <div id="resultados_a"></div><!-- Carga los datos ajax -->
                                        <div class='outer_div_a'></div><!-- Carga los datos ajax -->
                                        <!-- /ajax -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane" id="tests">
                    <!-- Testing -->
                    <div class="page-title action-hidden">
                        <div class="clearfix"></div>
                        <div class="col-md-12 col-sm-12 col-xs-12">

                            <div class="x_panel">
                                <div class="x_title">
                                    <h2>Pruebas</h2>
                                    <ul class="nav navbar-right panel_toolbox">
                                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                        </li>
                                        <li><a class="close-link"><i class="fa fa-close"></i></a>
                                        </li>
                                    </ul>
                                    <div class="clearfix"></div>
                                </div>

                                <div class="x_content filters">
                                    <form class="form-inline">
                                        <div class="form-group">
                                            <label for="filets">Buscar por:</label>
                                            <select class="form-control filter-sel" id="search_for_testing">
                                                <option selected disabled value="">-- Selecciona --</option>
                                                <option value="null">Ninguno</option>
                                                <option value="Titulo">Titulo</option>
                                                <option class="action-hidden" value="Prioridad">Prioridad</option>
                                                <option value="Empresa">Empresa</option>
                                                <option value="Creado">Creado por</option>
                                                <!--                                                <option class="action-hidden" value="Asignado">Asignado a</option>-->
                                                <option class="action-hidden" value="Estatus">Estatus</option>

                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="search_word_testing"
                                                   placeholder="..."
                                                   onkeyup='load_testing(1);'>
                                        </div>
                                        <div class="form-group">
                                            <span class="action-hidden">Asignado a:</span>
                                            <select class="form-control filter-sel action-hidden"
                                                    id="search_who_testing" onchange="load_testing(1);">
                                                <option selected value="null">-- Todos --</option>
                                                <?php foreach ($workers_test as $w): ?>
                                                    <option value="<?php echo $w['id'] ?>"><?php echo $w['name']; ?></option>
                                                <?php endforeach; ?>

                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <span class="action-hidden">Modulo:</span>
                                            <select class="form-control filter-sel action-hidden" id="module_testing"
                                                    onchange="load_testing(1);">
                                                <option selected value="null">-- Todos --</option>
                                                <?php foreach ($modules as $m): ?>
                                                    <option value="<?php echo $m['id'] ?>"><?php echo $m['name']; ?></option>
                                                <?php endforeach; ?>

                                            </select>
                                        </div>
                                        <select class="form-control float-right" onchange="load_testing(1);"
                                                id="entries_testing">
                                            <option value="20">20</option>
                                            <option value="50">50</option>
                                            <option value="100">100</option>
                                            <option value="500">500</option>
                                        </select>
                                    </form>
                                </div>

                                <div class="x_content">
                                    <div class="table-responsive" id="tableResponse">
                                        <!-- ajax -->
                                        <div id="resultados_t"></div><!-- Carga los datos ajax -->
                                        <div class='outer_div_t'></div><!-- Carga los datos ajax -->
                                        <!-- /ajax -->
                                        <!-- table with js -->
                                        <table id="example" class="display" width="100%"></table>
                                    </div>
                                    <!-- table with js -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div><!-- /page content -->

<?php
include("modal/new_ticket.php");
include("includes/footer.php");
?>
<script type="text/javascript" src="js/ticket.js"></script>
<script type="text/javascript" src="js/fades.js"></script>
<script type="text/javascript" src="js/filter.js"></script> 

