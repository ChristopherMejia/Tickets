<?php
/**
 * Created by PhpStorm.
 * User: Freddy Arvizu
 * Date: 17/09/2019
 * Time: 03:15 PM
 */


$activity = $global->recent_activity($user_id);
$role = $global->getRol($user_id);

$StatusData = mysqli_query($con, "SELECT DISTINCT * FROM status");
foreach ($StatusData as $k) {
    $complement .= "COUNT(CASE WHEN t.status_id = '" . $k['id'] . "' THEN s.name END) '" . $k['name'] . "',";
}
$complement = substr($complement, 0, strlen($complement) - 1);

if ($role['id'] == 4 OR $role['id'] == 5) {
    $complementRole = "  t.user_id = $user_id AND (t.deleted_at IS NULL OR t.deleted_at = '')";

} else {
    $complementRole = "  t.asigned_id = $user_id AND (t.deleted_at IS NULL OR t.deleted_at = '')";

}
$TicketQuery = "SELECT " . $complement . " FROM tickets t  LEFT JOIN `status` s ON t.status_id = s.id WHERE" . $complementRole;


$TicketStatus = mysqli_query($con, $TicketQuery);

$TicketDataRecent = mysqli_query($con, $queryRecent = "SELECT t.order_number, t.title, t.created_at, s.name AS status, s.badge FROM tickets t LEFT JOIN `status` s ON s.id = t.status_id WHERE " . $complementRole . " AND t.created_at BETWEEN DATE_SUB(NOW(), INTERVAL 1 MONTH) AND NOW() AND (t.deleted_at IS NULL OR t.deleted_at = '') ORDER BY t.created_at DESC LIMIT 5");


$TicketsPending = mysqli_query($con, $queryPending = "SELECT t.order_number, t.title, t.created_at, s.name AS status, u.name AS created_by, c.name as company, c.id AS company_id, s.badge FROM tickets t LEFT JOIN `status` s ON s.id = t.status_id LEFT JOIN users u ON t.user_id = u.id LEFT JOIN companies c ON u.company_id = c.id WHERE t.asigned_id = 0 AND t.created_at BETWEEN DATE_SUB(NOW(), INTERVAL 1 MONTH) AND NOW() AND t.status_id= 1 ORDER BY t.created_at DESC LIMIT 8");


$TicketsTesting = mysqli_query($con, $queryTesting = "SELECT t.order_number, t.title, t.created_at, s.name AS status, u.name AS created_by, c.name as company, c.id AS company_id, s.badge FROM tickets t LEFT JOIN `status` s ON s.id = t.status_id LEFT JOIN users u ON t.user_id = u.id LEFT JOIN companies c ON u.company_id = c.id WHERE  t.status_id= 5 ORDER BY t.created_at DESC LIMIT 8");

//var_dump($queryPending);

$TicketData = mysqli_query($con, "SELECT * FROM tickets WHERE status_id NOT IN ('3','4')");
$ProjectData = mysqli_query($con, "SELECT * FROM projects");
$CategoryData = mysqli_query($con, "SELECT * FROM categories");
$UserData = mysqli_query($con, "SELECT * FROM users ORDER BY created_at DESC");

echo(mysqli_num_rows($UserData))
?>
<div class="right_col" role="main">
    <!-- page content -->
    <div class="">
        <div class="page-title">
            <div class="row top_tiles">
                <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="tile-stats">
                        <div class="icon"><i class="fa fa-ticket"></i></div>
                        <div class="count"><?php echo mysqli_num_rows($TicketData) ?></div>
                        <h3><a href="tickets">Tickets Activos</a></h3>
                    </div>
                </div>
                <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="tile-stats">
                        <div class="icon"><i class="fa fa-list-alt"></i></div>
                        <div class="count"><?php echo mysqli_num_rows($ProjectData) ?></div>
                        <h3><a href="projects">Proyectos</a></h3>
                    </div>
                </div>
                <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="tile-stats">
                        <div class="icon"><i class="fa fa-th-list"></i></div>
                        <div class="count"><?php echo mysqli_num_rows($CategoryData) ?></div>
                        <h3><a href="categories">Categorias</a></h3>
                    </div>
                </div>
                <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="tile-stats">
                        <div class="icon"><i class="fa fa-users"></i></div>
                        <div class="count"><?php echo mysqli_num_rows($UserData) ?></div>
                        <h3><a href="users">Usuarios</a></h3>
                    </div>
                </div>
            </div>
            <!-- content  -->
            <br><br>
            <div class="row">
                <div class="col-md-2">
                    <div class="x_panel lgo">
                        <img src="images/logos/<?php echo $company_id ?>.png" class="img-responsive logo-company"
                             title="<?php echo $sc['name'] ?>" class="img-responsive"
                             onerror="this.onerror=null;this.src='images/default.png';">
                    </div>
                </div>
                <div class="col-md-10 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <h1>Bienvenido <b><?php echo $name; ?></b></h1>
                        <h6><?php echo $company; ?></h6>
                    </div>
                </div>

            </div>


            <div class="col-md-8 col-sm-12 col-xs-12">
                <div class="panel" <?php if (mysqli_num_rows($TicketsPending) == 0){ ?>style="display: none"<?php } ?>>
                    <div class="panel-heading">
                        <h3 class="panel-title">Pendientes por asignar</h3>
                    </div>
                    <div class="panel-body no-padding">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>Folio</th>
                                    <th>Titulo</th>
                                    <th>Empresa</th>
                                    <th>Creado por</th>
                                    <th>Fecha</th>
                                    <th>Estatus</th>
                                    <th>#</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($TicketsPending as $tp) { ?>
                                    <tr>
                                        <td>
                                            <a href="ticket-detail?order=<?php echo $tp['order_number'] ?>"><?php echo $tp['order_number'] ?></a>
                                        </td>
                                        <td><?php echo $tp['title'] ?></td>
                                        <td>
                                            <div class="mini-logo"
                                                 style="background: url('images/logos/<?php echo $tp['company_id'] ?>.png') no-repeat center center;  background-size: cover; ">

                                            </div>

                                        </td>
                                        <td><?php echo $tp['created_by'] ?></td>
                                        <td><?php echo $tp['created_at'] ?></td>
                                        <td>
                                            <span class="label label-<?php echo $tp['badge'] ?>"><?php echo $tp['status'] ?></span>
                                        </td>
                                        <td>
                                            <a href="ticket-detail?order=<?php echo $tp['order_number'] ?>">
                                                <i class="fa fa-external-link fa-2x" title="Asignar"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>


                <div id="graph"></div>
                <!--                <div id="graph_circle" style="height: 310px; "></div>-->
            </div>
            <div class="col-md-4 col-sm-12 col-xs-12">
                <div class="panel" <?php if (mysqli_num_rows($TicketsTesting) == 0){ ?>style="display: none"<?php } ?>>
                    <div class="panel-heading">
                        <h3 class="panel-title">Pruebas</h3>
                    </div>
                    <div class="panel-body no-padding">
                        <table class="table table-striped paleBlueRows">
                            <thead>
                            <tr>
                                <th>Folio</th>
                                <th>Fecha</th>
                                <th>Estatus</th>
                                <th>#</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($TicketsTesting as $tt) { ?>
                                <tr>
                                    <td>
                                        <a href="ticket-detail?order=<?php echo $tt['order_number'] ?>"><?php echo $tt['order_number'] ?></a>
                                    </td>

                                    <td><?php echo $tt['created_at'] ?></td>
                                    <td>
                                        <span class="label label-<?php echo $tt['badge'] ?>"><?php echo $tt['status'] ?></span>
                                    </td>
                                    <td>
                                        <a href="ticket-detail?order=<?php echo $tt['order_number'] ?>">
                                            <i class="fa fa-external-link fa-2x" title="Asignar"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="panel">
                    <div class="panel-heading">
                        <h3 class="panel-title">Mis últimos tickets</h3>
                    </div>
                    <div class="panel-body no-padding">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>Folio</th>
                                    <th>Titulo</th>
                                    <th>Fecha</th>
                                    <th>Estatus</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($TicketDataRecent as $tr) { ?>
                                    <tr>
                                        <td>
                                            <a href="ticket-detail?order=<?php echo $tr['order_number'] ?>"><?php echo $tr['order_number'] ?></a>
                                        </td>
                                        <td><?php echo $tr['title'] ?></td>
                                        <td><?php echo $tr['created_at'] ?></td>
                                        <td>
                                            <span class="label label-<?php echo $tr['badge'] ?>"><?php echo $tr['status'] ?></span>
                                        </td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <span class="panel-note">
                                    <i class="fa fa-clock-o"></i> <?php echo date("d") . " /" . date("m") . " / " . date("Y"); ?>
                                </span>
                            </div>
                            <div class="col-md-6 col-sm-12 col-xs-12 text-right">
                                <a href="tickets" class="btn btn-primary">
                                    Ver todos los tickets
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-12 col-xs-12">
                <div class="panel panel-scrolling">
                    <div class="panel-heading">
                        <h3 class="panel-title">Actividad reciente del usuario</h3>
                    </div>
                    <div class="slimScrollDiv"
                         style="position: relative; overflow: hidden; width: auto; height: 430px;">
                        <div class="panel-body" style="overflow: hidden; width: auto; height: 430px;">
                            <ul class="list-unstyled activity-list">


                                <?php foreach ($activity as $a) {

                                    foreach ($a['arrayTickets'] as $at) {
                                        if (strlen($at['order_number']) > 0) {
                                            ?>

                                            <li>
                                                <img src="images/profiles/<?php echo $profile_pic; ?>" alt="Avatar"
                                                     class="img-circle pull-left avatar">
                                                <p>
                                                    Se ha creado un <b>ticket</b> con el
                                                    folio <a
                                                            href="ticket-detail?order=<?php echo $at['order_number'] ?>">
                                                        <b><?php echo $at['order_number'] ?></b>
                                                    </a>
                                                    <span class="timestamp"><?php echo $time = $global->timeElapsedDates($at['created_at'], date("Y-m-d H:i:s")) ?></span>
                                                </p>
                                            </li>

                                            <?php
                                        } else {
//                                            echo "<br>No hay nuevos tickets...";
                                        }
                                    }

                                    foreach ($a['arrayNotes'] as $an) {
                                        if (strlen($an['title']) > 0) {
                                            ?>
                                            <li>
                                                <img src="images/profiles/<?php echo $profile_pic; ?>"
                                                     alt="Avatar"
                                                     class="img-circle pull-left avatar">
                                                <p>
                                                    Se ha creado una <b>nota</b>
                                                    personal
                                                    con
                                                    el
                                                    titulo <b><?php echo $an['title'] ?></b>

                                                    <span class="timestamp"><?php echo $time = $global->timeElapsedDates($an['created_at'], date("Y-m-d H:i:s")) ?></span>
                                                </p>
                                            </li>
                                        <?php } else {
//                                            echo "<br>No hay nuevas notas...";
                                        }
                                    }

                                    foreach ($a['arrayComments'] as $ac) {

//                                        var_dump($ac['order_number']);
                                        if (strlen($ac['order_number']) > 0) {
                                            ?>
                                            <li>
                                                <img src="images/profiles/<?php echo $profile_pic; ?>"
                                                     alt="Avatar"
                                                     class="img-circle pull-left avatar">
                                                <p>
                                                    Se ha agregado un nuevo
                                                    <b>comentario</b>
                                                    al ticket con el folio
                                                    <a href="ticket-detail?order=<?php echo $ac['order_number'] ?>">
                                                        <b><?php echo $ac['order_number'] ?></b>
                                                    </a>
                                                    <span class="timestamp"><?php echo $time = $global->timeElapsedDates($ac['created_at'], date("Y-m-d H:i:s")) ?></span>
                                                </p>
                                            </li>
                                            <?php
                                        } else {
//                                            echo "<br>No hay nuevos comentarios...";
                                        }
                                    }
                                    foreach ($a['arrayUploads'] as $au) {
                                        if (strlen($au['filename']) > 0) {
                                            ?>
                                            <li>
                                                <img src="images/profiles/<?php echo $profile_pic; ?>"
                                                     alt="Avatar"
                                                     class="img-circle pull-left avatar">
                                                <p>
                                                    Se ha subido un <b>archivo</b>
                                                    con el nombre <b><?php echo $au['filename'] ?></b>
                                                    <span class="timestamp"><?php echo $time = $global->timeElapsedDates($au['created_at'], date("Y-m-d H:i:s")) ?></span>
                                                </p>
                                            </li>

                                        <?php } else {
//                                            echo "<br>No hay nuevos archivos...";
                                        }
                                    }
                                } ?>
                            </ul>
                        </div>
                        <div class="slimScrollBar"
                             style="background: rgb(0, 0, 0); width: 7px; position: absolute; top: 0px; opacity: 0.4; display: none; border-radius: 7px; z-index: 99; right: 1px; height: 324.956px;"></div>
                        <div class="slimScrollRail"
                             style="width: 7px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; background: rgb(51, 51, 51); opacity: 0.2; z-index: 90; right: 1px;"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-8 col-sm-12 col-xs-12"></div>
        </div>
    </div>
</div>
<!-- /page content -->


<?php
//include("modal/new_ticket.php");
?>
