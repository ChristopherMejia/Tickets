<?php

include "../config/config.php";//DB
// include "filter.php";

/*  *******************************PERMITIONS */
$page = "tickets";
$user_id = $_GET['user_id'];


$view = $permission->permissions_per_page_view($user_id, $page);
$edit = $permission->permissions_per_page_edit($user_id, $page);
$delete = $permission->permissions_per_page_delete($user_id, $page);
$role = $permission->get_rol($user_id);



    if (empty($delete['action'])) {
        echo '<script>$(".action-delete").css("display", "none");</script>';
    }

    if ($role['id'] == 4 OR $role['id'] == 5) {
        echo '<script>$(".action-hidden").css("display", "none");</script>';
    }

    if ($role['id'] == 5) {
        echo '<script>$(".super").css("display", "block");</script>';
    }


/*  ***************************END PERMITIONS */

$action = (isset($_REQUEST['action']) && $_REQUEST['action'] != NULL) ? $_REQUEST['action'] : '';
if (isset($_GET['id'])) {
    $id_del = intval($_GET['id']);
    $query = mysqli_query($con, "SELECT * FROM tickets WHERE id='" . $id_del . "'");
    $count = mysqli_num_rows($query);

    if ($delete1 = mysqli_query($con, "UPDATE tickets SET deleted_at= NOW() WHERE id='" . $id_del . "'")) {
        ?>
        <div class="alert alert-success alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            <strong>Aviso!</strong> Datos eliminados exitosamente.
        </div>
        <?php
    } else {
        ?>
        <div class="alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            <strong>Error!</strong> Lo siento algo ha salido mal intenta nuevamente.
        </div>
        <?php
    } //end else
} //end if
?>

<?php

if ($action == 'ajax' ) {
    // var_dump($queryFull);

    // escaping, additionally removing everything that could be (html/javascript-) code
    $entries = mysqli_real_escape_string($con, (strip_tags($_REQUEST['entries'], ENT_QUOTES)));
    $search_word = $_GET['search_word'];
    $search_who = $_GET['search_who'];
    $module = $_GET['module'];

    $filter = (isset($_REQUEST['search_for']) && !empty($_REQUEST['search_for'])) ? $_REQUEST['search_for'] : 0;

    if ($filter == 'Titulo') {
        $tab = "t.title";
    } elseif ($filter == 'Estatus') {
        $tab = "SELECT count(*) FROM status s WHERE t.status_id = s.id AND s.`name`";
    } elseif ($filter == 'Prioridad') {
        $tab = "SELECT count(*) FROM priorities p WHERE t.priority_id = p.id AND p.`name`";
    } elseif ($filter == 'Empresa') {
        $tab = "SELECT count(*) FROM companies c WHERE t.company_id = c.id AND c.`name`";
    } elseif ($filter == 'Creado') {
        $tab = "SELECT count(*) FROM users uc WHERE t.user_id = uc.id AND uc.`name`";
    } elseif ($filter == 'Asignado') {
        $tab = "SELECT count(*) FROM users ua WHERE t.asigned_id = ua.id AND ua.`name`";
    }

    $aColumns = array($tab);//Columnas de busqueda
    //    $complementManage = " OR(tt.user_assigned_id = 0 OR tt.user_taken_id = 0)";
    $sTable = "tickets t";
    $sWhere = "LEFT JOIN tickets_detail td ON t.id = td.ticket_id";
    if ($role['id'] == 3) {
        $sWhere .= " LEFT JOIN tickets_tracking tt ON t.id = tt.ticket_id WHERE (t.deleted_at IS NULL OR t.deleted_at = '') AND (t.asigned_id =" . $user_id . ")";

    } elseif ($role['id'] >= 4) {
        $sWhere .= " LEFT JOIN tickets_tracking tt ON t.id = tt.ticket_id WHERE (t.deleted_at IS NULL OR t.deleted_at = '') AND (t.user_id =" . $user_id . ")";
    } else {
        $sWhere .= " LEFT JOIN tickets_tracking tt ON t.id = tt.ticket_id WHERE (t.deleted_at IS NULL OR t.deleted_at = '') AND (t.user_id =" . $user_id . " OR t.asigned_id =" . $user_id . ")";
    }

    if ($_GET['search_for'] != "null") {
        $sWhere .= " AND (";
        for ($i = 0; $i < count($aColumns); $i++) {
            $sWhere .= $aColumns[$i] . " LIKE '%" . $search_word . "%' ";
        }
        $sWhere .= ")";

    }
    if ($_GET['search_who'] != "null" && isset($_REQUEST['search_who'])) {
        $sWhere .= " AND ";
        for ($i = 0; $i < count($aColumns); $i++) {
            $sWhere .= " t.asigned_id ='" . $search_who . "' ";
        }
    }

    if ($_GET['module'] != "null") {
        $sWhere .= " AND (";
        for ($i = 0; $i < count($aColumns); $i++) {
            $sWhere .= $aColumns[$i] . " td.module_id =" . $module;
        }
        $sWhere .= ")";

    }

    $sWhere .= " ORDER BY t.created_at DESC";
    $sWhere .= " ,t.priority_id ASC";

    include 'pagination.php'; //include pagination file
    //pagination variables
    $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page'])) ? $_REQUEST['page'] : 1;
    $per_page = $entries; //how much records you want to show
    $adjacents = 4; //gap between pages after number of adjacents
    $offset = ($page - 1) * $per_page;
    //Count the total number of row in your table*/
    $count_query = mysqli_query($con, "SELECT count(*) AS numrows FROM $sTable  $sWhere");
    $row = mysqli_fetch_array($count_query);
    $numrows = $row['numrows'];
    $total_pages = ceil($numrows / $per_page);
    $reload = './expences.php';
    //main query to fetch the data
    $sql = "SELECT DISTINCT t.order_number, t.title, t.priority_id, t.status_id, t.user_id, td.module_id, t.asigned_id, t.created_at, t.updated_at FROM  $sTable $sWhere LIMIT $offset,$per_page";
    //var_dump($sql);
    $query = mysqli_query($con, $sql); //VAlidamos para verificar cual query usar
    if ($numrows > 0 ) {
        ?>
        <div class="panel-body no-padding" id="tablaPrincipal">
            <div class="table-responsive">
                <table class="table table-striped jambo_table bulk_action">
                    <thead>
                    <tr class="headings">
                        <th>#</th>
                        <th class="column-title">Titulo</th>
                        <th class="column-title action-hidden">Prioridad</th>
                        <th class="column-title">Empresa</th>
                        <th class="column-title">Creado por</th>
                        <th class="column-title action-hidden">Asignado a</th>
                        <th class="column-title action-hidden">Estatus</th>
                        <th class="column-title action-hidden">Modulo</th>
                        <th>Creado</th>
                        <th>Última Actualización</th>
                        <th class="column-title no-link last"><span class="nobr"></span></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    while ($r = mysqli_fetch_array($query)) {
                        // var_dump($r);
                        $id = $r['id'];
                        $order = $r['order_number'];
                        $created_at = date('d/m/Y', strtotime($r['created_at']));
                        $updated_at = date('d/m/Y H:i:s', strtotime($r['updated_at']));
                        $description = $r['description'];
                        $title = $r['title'];
                        $project_id = $r['project_id'];
                        $priority_id = $r['priority_id'];
                        $status_id = $r['status_id'];
                        $kind_id = $r['kind_id'];
                        $category_id = $r['category_id'];
                        $asigned_id = $r['asigned_id'];
                        $created_id = $r['user_id'];
                        $module_id = $r['module_id'];

                        $sqlPro = mysqli_query($con, "SELECT * FROM projects WHERE id=" . $project_id);
                        $rowPro = mysqli_fetch_array($sqlPro);

                        $sqlPri = mysqli_query($con, "SELECT * FROM priorities WHERE id=" . $priority_id);
                        $rowPri = mysqli_fetch_array($sqlPri);

                        $sqlSta = mysqli_query($con, "SELECT * FROM status WHERE id=" . $status_id);
                        $rowSta = mysqli_fetch_array($sqlSta);

                        $sqlCre = mysqli_query($con, "SELECT * FROM users WHERE id=" . $created_id);
                        $rowCre = mysqli_fetch_array($sqlCre);


                        $sqlAsi = mysqli_query($con, "SELECT * FROM users WHERE id=" . $asigned_id);
                        $rowAsi = mysqli_fetch_array($sqlAsi);

                        $sqlModule = mysqli_query($con, "SELECT * FROM modules WHERE id=" . $module_id);
                        $rowModule = mysqli_fetch_array($sqlModule);


                        $company_id = $rowCre['company_id'];

                        ?>

                        <tr class="even pointer">
                            <td>#<?php echo $order; ?></td>
                            <td><?php echo $title; ?></td>
                            <td class="action-hidden"><?php echo $rowPri['name']; ?></td>
                            <td>
                                <div class="mini-logo"
                                     style="background: url('images/logos/<?php echo $company_id ?>.png') no-repeat center center;  background-size: cover; ">

                                </div>
                            </td>
                            <td>
                                <?php echo $rowCre['name'] ?>
                            </td>
                            <td class="action-hidden">
                                <?php if (isset($rowAsi['name'])) {
                                    echo $rowAsi['name'];
                                } else {
                                    echo "Pendiente";
                                };
                                ?>
                            </td>

                            <td class="action-hidden">
                                <span class="lb-custom label label-<?php echo $rowSta['badge']; ?>"><?php echo $rowSta['name']; ?></span>
                            </td>
                            <td>
                                <?php if (isset($rowModule['name'])): ?>
                                    <span class="label label-default"> <?php echo strtoupper($rowModule['name']); ?></span>
                                <?php else: ?>
                                    <span class="label label-warning"> Sin asignar</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo $created_at; ?></td>
                            <td><?php echo $updated_at; ?></td>
                            <td>
                            <span class="pull-right">
                            <a href="ticket-detail?order=<?php echo $order; ?>" class='btn btn-default'
                               title='Ver detalle'>
                                <i class="glyphicon glyphicon-eye-open"></i>
                            </a>


                            <a href="#" class='btn btn-default action-delete' title='Eliminar ticket'
                               onclick="eliminar('<?php echo $id; ?>')">
                                <i class="glyphicon glyphicon-trash"></i>
                            </a>
                            </span>
                            </td>
                        </tr>
                        <?php
                    } //en while
                    ?>
                    <tr>
                        <td colspan=12>
                            <span class="pull-right">
                            <?php echo paginate($reload, $page, $total_pages, $adjacents, 'load'); ?>
                        </span>
                        </td>
                    </tr>
                </table>

            </div>
        </div>
    <?php
    } 
    else {
        ?>
        <div class="alert alert-warning alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            <strong>Aviso!</strong> No hay datos para mostrar!
        </div>
        <?php
    }
}
?>
<script type="text/javascript" src="js/filter.js"></script> 




