<?php
/**
 * Created by PhpStorm.
 * User: Freddy Arvizu
 * Date: 17/09/2019
 * Time: 03:15 PM
 */


$page = "dashboard";
$activity = $global->recent_activity($user_id);
$role = $global->getRol($user_id);


$StatusData = mysqli_query($con, "SELECT DISTINCT * FROM status");
$complement = '';
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

$TicketData = mysqli_query($con, "SELECT * FROM tickets");
$ProjectData = mysqli_query($con, "SELECT * FROM projects");
$CategoryData = mysqli_query($con, "SELECT * FROM categories");
$UserData = mysqli_query($con, "SELECT * FROM users ORDER BY created_at DESC");
?>
<div class="right_col" role="main">
    <!-- page content -->
    <div class="">
        <div class="page-title">
            <!-- content  -->
            <br><br>

            <div class="row">
                <div class="col-md-2 col-sm-12 col-xs-12">
                    <div class="x_panel lgo">

                        <img src="images/logos/<?php echo $company_id ?>.png" class="img-responsive logo-company"
                             class="img-responsive" onerror="this.onerror=null;this.src='images/default.png';">

                    </div>
                </div>
                <div class="col-md-10 col-sm-12 col-xs-12">
                    <div class="x_panel lgo">
                        <h1>Bienvenido <b><?php echo $name; ?></b></h1>
                        <h6><?php echo $company; ?></h6>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="x_panel sub-companies">
                        <?php if (isset($subCompanies)) {
                            foreach ($subCompanies as $sc) { ?>
                                <img src="images/logos/<?php echo $sc['id'] ?>.png" title="<?php echo $sc['name'] ?>"
                                     class="img-responsive" onerror="this.onerror=null;this.src='images/default.png';">
                            <?php }
                        } ?>
                    </div>
                </div>
            </div>

            <div class="col-md-8 col-sm-12 col-xs-12">
                <div id="graph"></div>
                <!--                <div id="graph_circle" style="height: 310px; "></div>-->
                <div class="panel">
                    <div class="panel-heading">
                        <h3 class="panel-title">Últimos tickets</h3>
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
                            <div class="col-md-6">
                                <span class="panel-note">
                                    <i class="fa fa-clock-o"></i> <?php echo date("d") . " /" . date("m") . " / " . date("Y"); ?>
                                </span>
                            </div>
                            <div class="col-md-6 text-right">
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
                        <h3 class="panel-title">Actividad reciente</h3>
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
include "modal/new_ticket.php";
?>
