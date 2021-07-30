<?php
$title = "Reportes | ";
$page = "reports";
include "includes/head.php";
include "config/presence.php";
include "includes/sidebar.php";

$projects = mysqli_query($con, "SELECT * FROM projects");
$priorities = mysqli_query($con, "SELECT * FROM priorities");
$statuses = mysqli_query($con, "SELECT * FROM status");
$kinds = mysqli_query($con, "SELECT * FROM kinds");
$role = $global->getRol($user_id);
$group = $global->getGroupCompany($user_id);
$q = "";

if ($role['id'] == 5) //Super Customer
{
    if ((isset($_GET["status_id"]) && isset($_GET["start_at"]) && isset($_GET["finish_at"])) && ($_GET["status_id"] != "" || ($_GET["start_at"] != "" && $_GET["finish_at"] != ""))) {
        $sql = "SELECT
                        t.*
                    FROM
                        `tickets` t
                        LEFT JOIN users u ON t.user_id = u.id
                        LEFT JOIN companies c ON u.company_id = c.id
                    WHERE  c.`group`= " . $group['group'];
        if ($_GET["status_id"] != "") {
            $sql .= " and t.status_id = " . $_GET["status_id"];
        }


        if ($_GET["priority_id"] != "") {
            if ($_GET["status_id"] != "") {
                $sql .= " and ";
            }

            $sql .= " priority_id = " . $_GET["priority_id"];
        }

        if ($_GET["start_at"] != "" && $_GET["finish_at"]) {
            if ($_GET["status_id"] != "" || $_GET["priority_id"] != "") {
                $sql .= " and ";
            }

            $sql .= " ( t.created_at >= \"" . $_GET["start_at"] . "\" and t.created_at <= \"" . $_GET["finish_at"] . "\" ) ";
        }

        $q = "?group=0&status_id=" . $_GET['status_id'] . "&priority_id=" . $_GET["priority_id"] . "&start_at=" . $_GET['start_at'] . "&finish_at=" . $_GET['finish_at'] . "&status_id=" . $_GET['status_id'] . "&user_id=" . $user_id;
        $users = mysqli_query($con, $sql);


    } else {

        $q = "?group=" . $group['group'] . "&user_id=" . $user_id;
        $users = $global->getTicketsReport($group['group']);


    }

} else {

    if ((isset($_GET["status_id"]) && isset($_GET["kind_id"]) && isset($_GET["project_id"]) && isset($_GET["priority_id"]) && isset($_GET["start_at"]) && isset($_GET["finish_at"])) && ($_GET["status_id"] != "" || $_GET["kind_id"] != "" || $_GET["project_id"] != "" || $_GET["priority_id"] != "" || ($_GET["start_at"] != "" && $_GET["finish_at"] != ""))) {
        $sql = "SELECT * FROM tickets where ";
        if ($_GET["status_id"] != "") {
            $sql .= " status_id = " . $_GET["status_id"];
        }
        if ($_GET["kind_id"] != "") {
            if ($_GET["status_id"] != "") {
                $sql .= " and ";
            }
            $sql .= " kind_id = " . $_GET["kind_id"];
        }
        if ($_GET["project_id"] != "") {
            if ($_GET["status_id"] != "" || $_GET["kind_id"] != "") {
                $sql .= " and ";
            }
            $sql .= " project_id = " . $_GET["project_id"];
        }

        if ($_GET["priority_id"] != "") {
            if ($_GET["status_id"] != "" || $_GET["project_id"] != "" || $_GET["kind_id"] != "") {
                $sql .= " and ";
            }

            $sql .= " priority_id = " . $_GET["priority_id"];
        }

        if ($_GET["start_at"] != "" && $_GET["finish_at"]) {
            if ($_GET["status_id"] != "" || $_GET["project_id"] != "" || $_GET["priority_id"] != "" || $_GET["kind_id"] != "") {
                $sql .= " and ";
            }
            $sql .= " ( created_at >= \"" . $_GET["start_at"] . "\" and created_at <= \"" . $_GET["finish_at"] . "\" ) ";
        }
        $q = "?group=0&status_id=" . $_GET['status_id'] . "&start_at=" . $_GET['start_at'] . "&finish_at=" . $_GET['finish_at'] . "&status_id=" . $_GET['status_id'] . "&kind_id=" . $_GET['kind_id'] . "&project_id=" . $_GET['project_id'] . "&priority_id=" . $_GET['priority_id'] . "&user_id=" . $user_id;

        $users = mysqli_query($con, $sql);
    } else {

        $q = "?group=0&status_id=" . $_GET['status_id'] . "&start_at=" . $_GET['start_at'] . "&finish_at=" . $_GET['finish_at'] . "&status_id=" . _GET['status_id'] . "&kind_id=" . $_GET['kind_id'] . "&project_id=" . $_GET['project_id'] . "&priority_id=" . $_GET['priority_id'] . "&user_id=" . $user_id;
        $users = mysqli_query($con, "SELECT * FROM tickets order by created_at desc");
    }
}
//var_dump($sql)

?>

<div class="right_col" role="main"><!-- page content -->
    <div class="">
        <div class="page-title">
            <div class="clearfix"></div>
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Usuarios</h2>
                        <ul class="nav navbar-right panel_toolbox">
                            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                            </li>
                            <li><a class="close-link"><i class="fa fa-close"></i></a>
                            </li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>

                    <!-- form search -->
                    <form class="form-horizontal">
                        <input type="hidden" name="view" value="reports">
                        <div class="form-group">
                            <div class="col-lg-3 action-hidden">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-male"></i></span>
                                    <select name="project_id" class="form-control">
                                        <option value="">PROYECTO</option>
                                        <?php foreach ($projects as $p): ?>
                                            <option value="<?php echo $p['id']; ?>" <?php if (isset($_GET["project_id"]) && $_GET["project_id"] == $p['id']) {
                                                echo "selected";
                                            } ?>><?php echo $p['name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-support"></i></span>
                                    <select name="priority_id" class="form-control">
                                        <option value="">PRIORIDAD</option>
                                        <?php foreach ($priorities as $p): ?>
                                            <option value="<?php echo $p['id']; ?>" <?php if (isset($_GET["priority_id"]) && $_GET["priority_id"] == $p['id']) {
                                                echo "selected";
                                            } ?>><?php echo $p['name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="input-group">
                                    <span class="input-group-addon">INICIO</span>
                                    <input type="date" name="start_at"
                                           value="<?php if (isset($_GET["start_at"]) && $_GET["start_at"] != "") {
                                               echo $_GET["start_at"];
                                           } ?>" class="form-control" placeholder="Palabra clave">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="input-group">
                                    <span class="input-group-addon">FIN</span>
                                    <input type="date" name="finish_at"
                                           value="<?php if (isset($_GET["finish_at"]) && $_GET["finish_at"] != "") {
                                               echo $_GET["finish_at"];
                                           } ?>" class="form-control" placeholder="Palabra clave">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-3">
                                <div class="input-group">
                                    <span class="input-group-addon">ESTADO</span>
                                    <select name="status_id" class="form-control">
                                        <?php foreach ($statuses as $p): ?>
                                            <option value="<?php echo $p['id']; ?>" <?php if (isset($_GET["status_id"]) && $_GET["status_id"] == $p['id']) {
                                                echo "selected";
                                            } ?>><?php echo $p['name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3 action-hidden">
                                <div class="input-group">
                                    <span class="input-group-addon">TIPO</span>
                                    <select name="kind_id" class="form-control">
                                        <?php foreach ($kinds as $p): ?>
                                            <option value="<?php echo $p['id']; ?>" <?php if (isset($_GET["kind_id"]) && $_GET["kind_id"] == $p['id']) {
                                                echo "selected";
                                            } ?>><?php echo $p['name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <button class="btn btn-primary btn-block">Procesar</button>
                            </div>
                        </div>
                    </form>


                    <div class="col-md-12 float-right">
                        <div class="export-icons">
                            <a href="public/reports/report_tickets.php<?php echo $q; ?>">
                                <img src="images/download-excel-icon.jpg" class="mini-logo" title="Exportar">
                            </a>
                        </div>
                    </div>
                    <!-- end form search -->


                    <?php
                    if (@mysqli_num_rows($users) > 0){
                    // si hay reportes
                    $_SESSION["report_data"] = $users;


                    ?>
                    <div class="x_content">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                <th>Folio</th>
                                <th>Empresa</th>
                                <th>Creado por</th>
                                <th>Asunto</th>
                                <th class="action-hidden">Proyecto</th>
                                <th class="action-hidden">Tipo</th>
                                <th class="action-hidden">Categoria</th>
                                <th>Prioridad</th>
                                <th>Estado</th>
                                <th>Fecha</th>
                                <th>Solución</th>
                                </thead>
                                <?php
                                $total = 0;
                                foreach ($users as $user) {
                                    $ticket_id = $user['id'];
                                    $order_number = $user['order_number'];
                                    $created_name = $user['user_id'];
                                    $project_id = $user['project_id'];
                                    $priority_id = $user['priority_id'];
                                    $kind_id = $user['kind_id'];
                                    $category_id = $user['category_id'];
                                    $status_id = $user['status_id'];

                                    $cname = mysqli_query($con, "SELECT * FROM users where id=" . $created_name);
                                    $status = mysqli_query($con, "SELECT * FROM status where id=" . $status_id);
                                    $category = mysqli_query($con, "SELECT * FROM categories where id=" . $category_id);
                                    $kinds = mysqli_query($con, "SELECT * FROM kinds where id=" . $kind_id);
                                    $project = mysqli_query($con, "SELECT * FROM projects where id=" . $project_id);
                                    $medic = mysqli_query($con, "SELECT * FROM priorities where id=" . $priority_id);
                                    $lastComment = mysqli_query($con, "SELECT * FROM `comments` WHERE ticket_id = $ticket_id ORDER BY id DESC LIMIT 1");
                                    ?>
                                    <tr>
                                        <td><?php echo $order_number ?></td>
                                        <?php foreach ($cname as $cre) { ?>
                                            <td>
                                                <img src="images/logos/<?php echo $cre['company_id'] ?>.png"
                                                     title="<?php echo $cre['name'] ?>"
                                                     class="img-responsive mini-logo"
                                                     onerror="this.onerror=null;this.src='images/default.png';">
                                            </td>
                                            <td><?php echo $cre['name'] ?></td>
                                        <?php } ?>

                                        <td><?php echo $user['title'] ?></td>
                                        <?php foreach ($project as $pro) { ?>
                                            <td class="action-hidden"><?php echo $pro['name'] ?></td>
                                        <?php } ?>
                                        <?php foreach ($kinds as $kind) { ?>
                                            <td class="action-hidden"><?php echo $kind['name'] ?></td>
                                        <?php } ?>
                                        <?php foreach ($category as $cat) { ?>
                                            <td class="action-hidden"><?php echo $cat['name']; ?></td>
                                        <?php } ?>
                                        <?php foreach ($medic as $medics) { ?>
                                            <td><?php echo $medics['name']; ?></td>
                                        <?php } ?>
                                        <?php
                                        foreach ($status as $stat) {
                                            $statusTicket = $stat['id'];
                                            ?>
                                            <td>
                                                <span class="lb-custom label label-<?php echo $stat['badge']; ?>"><?php echo $stat['name']; ?></span>
                                            </td>
                                        <?php } ?>
                                        <td><?php echo $user['created_at']; ?></td>

                                        <td>
                                            <?php
                                            foreach ($lastComment as $comment) {
                                                if ($statusTicket == 3 OR $statusTicket == 4) { ?>
                                                    <?php echo $comment['comment'] ?>
                                                <?php }
                                            }
                                            ?>

                                        </td>
                                    </tr>
                                    <?php

                                }

                                ?>
                                <?php

                                } else {
                                    echo "<p class='alert alert-danger'>No hay tickets</p>";
                                }
                                ?>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div><!-- /page content -->

<?php include "includes/footer.php" ?>
