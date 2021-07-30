<?php
/**
 * Created by PhpStorm.
 * User: Freddy Arvizu
 * Date: 13/12/2019
 * Time: 01:15 PM
 */

include "../config/config.php";//DB


$action = (isset($_REQUEST['action']) && $_REQUEST['action'] != NULL) ? $_REQUEST['action'] : '';


if ($action == 'modal') {
    $order = $_POST['order_number'];

    $response = "<div class='embed-responsive embed-responsive-16by9'>";
    $response .= "<iframe class='embed-responsive-item'src='fast-view?order=" . $order . "' allowfullscreen></iframe>";
    $response .= "</div>";

    echo $response;
    die();
}

if ($action == 'modalPrograms') {
    $programs = $_POST['programs'];

    $response = "<ul class='form-control to-do' id='program-list'>";
    if (!empty($programs)) {

        $w = strtok($programs, " ,");
        while ($w !== false) {
            $response .= "<li class='li-program'>" . $w . "</li>";

            $w = strtok(" ,");
        }
    }
    $response .= "</ul>";

    echo $response;
    die();
}

if ($action == 'ajax') {
// escaping, additionally removing everything that could be (html/javascript-) code
    $entries = mysqli_real_escape_string($con, (strip_tags($_REQUEST['entries'], ENT_QUOTES)));
    $search_word = $_GET['search_word'];

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

    $sTable = "`tickets` t";
    $sWhere = "	LEFT JOIN tickets_has_fades tf ON t.id = tf.ticket_id
	        LEFT JOIN fades f ON tf.fade_id = f.id
	        LEFT JOIN modules m ON f.module_id = m.id 
            LEFT JOIN installation_control ic ON ic.ticket_id = t.id 
	        LEFT JOIN tickets_detail td ON td.ticket_id = t.id
    WHERE
        f.`name` ='" . $_GET['file'] . "'";

    if ($_GET['search_for'] != "null") {
        $sWhere .= " AND (";
        for ($i = 0; $i < count($aColumns); $i++) {
            $sWhere .= $aColumns[$i] . " LIKE '%" . $search_word . "%' ";
        }
        $sWhere .= ")";

    }


    $sWhere .= " order by t.updated_at DESC";
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
    $reload = './users.php';
//main query to fetch the data
    $sql = "SELECT DISTINCT t.id, t.order_number, t.title, t.description, t.status_id, t.user_id, t.asigned_id, td.results, t.created_at, t.updated_at  FROM  $sTable $sWhere LIMIT $offset,$per_page";

//echo($sql);

    $query = mysqli_query($con, $sql);
//loop through fetched data
    if ($numrows > 0) {
        ?>
        <table class="table table-striped jambo_table bulk_action">
            <thead>
            <tr class="headings">

                <th class="column-title">Creado por</th>
                <th class="column-title">Titulo</th>
                <th class="column-title">Descripción</th>
                <th class="column-title">Fecha Creación</th>
                <th class="column-title">Solución</th>
                <th class="column-title">Estatus</th>
                <th class="column-title">Asignado a</th>
                <th class="column-title">Última modificación</th>
                <th class="column-title "><span class="nobr"></span>Folio<span class="nobr"></span></th>
                <th class="column-title"><span class="nobr"></span></th>
            </tr>
            </thead>
            <tbody>
            <?php
            while ($r = mysqli_fetch_array($query)) {
                $ticket_id = $r['id'];
                $order_number = $r['order_number'];
                $created_user = $r['user_id'];
                $asigned_id = $r['asigned_id'];
                $title = $r['title'];
                $description = $r['description'];
                $created_at = date('d/m/Y', strtotime($r['created_at']));
                $updated_at = date_format(date_create($r['updated_at']), "d/m/Y H:i:s");
                $status_id = $r['status_id'];
                $programs = $r['results'];
                $lastComment = mysqli_query($con, $queryComment = "SELECT * FROM `comments` WHERE ticket_id = $ticket_id ORDER BY id DESC LIMIT 1");


                $cname = mysqli_query($con, "SELECT * FROM users where id=$created_user");


                $sqlSta = mysqli_query($con, "SELECT * FROM status WHERE id=" . $status_id);
                $rowSta = mysqli_fetch_array($sqlSta);


                $sqlAsi = mysqli_query($con, "SELECT * FROM users WHERE id=" . $asigned_id);
                $rowAsi = mysqli_fetch_array($sqlAsi);

//            var_dump($cname);
                ?>
                <input type="hidden" value="<?php echo $order_number; ?>" id="order_number<?php echo $order_number; ?>">
                <input type="hidden" value="<?php echo $title; ?>" id="title<?php echo $title; ?>">
                <input type="hidden" value="<?php echo $description; ?>" id="description<?php echo $description; ?>">
                <input type="hidden" value="<?php echo $created_at; ?>" id="created_at<?php echo $created_at; ?>">
                <input type="hidden" value="<?php echo $updated_at; ?>" id="updated_at<?php echo $updated_at; ?>">

                <tr class="even pointer">
                    <td>
                        <?php
                        foreach ($cname as $u) {
                            echo $u['name'];
                        }
                        ?>
                    </td>
                    <td>
                        <?php echo $title; ?>
                    </td>
                    <td>
                        <?php echo $description; ?>
                    </td>
                    <td>
                        <?php echo $created_at; ?>
                    </td>
                    <td>
                        <?php
                        foreach ($lastComment as $comment) {
                            if ($status_id == 3 OR $status_id == 4) { ?>
                                <?php echo $comment['comment'] ?>
                            <?php }
                        }
                        ?>
                    </td>
                    <td class="action-hidden">
                        <span class="lb-custom label label-<?php echo $rowSta['badge']; ?>"><?php echo $rowSta['name']; ?></span>
                    </td>
                    <td class="action-hidden">
                        <?php if (isset($rowAsi['name'])) {
                            echo $rowAsi['name'];
                        } else {
                            echo "";
                        };
                        ?>
                    </td>
                    <td>
                        <?php echo $updated_at ?>
                    </td>
                    <td>
                        <a href="#" title="Vista Rápida" onclick="openModal('<?php echo $order_number ?>')">

                            <i class="fa fa-hand-pointer-o"></i>
                            <?php echo $order_number ?>
                        </a>
                    </td>
                    <td align="center">

                        <?php if (empty($programs)) { ?>

                            <i class="fa fa-ban" aria-hidden="true"></i>

                        <?php } else { ?>

                            <a href="#" onclick="openModal_Programs('<?php echo $programs ?>')">
                                <i class="fa fa-bug"></i>
                            </a>

                        <?php } ?>


                    </td>
                </tr>

                <?php
            } //end while
            ?>
            <tr>
                <td colspan=12>
                <span class="pull-right">
                   <?php echo paginate($reload, $page, $total_pages, $adjacents, 'load_detail'); ?>
                </span>
                </td>
            </tr>
        </table>
        <div class="modal fade" id="modal_preview" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" style="height: 400px;">
                <div class="modal-content">
                    <form class="form-horizontal form-label-left input_mask" method="post"
                          id="frm-add-ticket-detail"
                          name="frm-add-ticket"
                          enctype="multipart/form-data">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Información Ticket</h4>
                        </div>
                        <div class="modal-body">

                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal fade" id="md_programsInstall" tabindex="-1" role="dialog" aria-hidden="true"
             aria-hidden="true">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <form class="form-horizontal form-label-left input_mask" method="post"
                          id="frm-add-ticket-detail"
                          name="frm-add-ticket"
                          enctype="multipart/form-data">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Programas a Instalar</h4>
                        </div>
                        <div class="modal-body">

                        </div>

                    </form>
                </div>

            </div>
        </div>
        <script>

            function openModal(order) {


                data = new FormData();
                data.append('order_number', order);
                data.append('action', "modal");

                $.ajax({
                    type: 'POST',
                    url: 'ajax/fade-detail.php',
                    data: data,
                    processData: false,  // tell jQuery not to process the data
                    contentType: false,   // tell jQuery not to set contentType
                    cache: false,
                    beforeSend: function () {
                    },
                    success: function (response) {


                        $('.modal-body').html(response);
                        // Display Modal
                        $('#modal_preview').modal('show');

                    }, error: function (data) {
                        // alert(data);

                    }
                });

            }

            function openModal_Programs(programs) {


                data = new FormData();
                data.append('programs', programs);
                data.append('action', "modalPrograms");

                $.ajax({
                    type: 'POST',
                    url: 'ajax/fade-detail.php',
                    data: data,
                    processData: false,  // tell jQuery not to process the data
                    contentType: false,   // tell jQuery not to set contentType
                    cache: false,
                    beforeSend: function () {
                    },
                    success: function (response) {
                        
          
                        $('.modal-body').html(response);
                        // Display Modal
                        $('#md_programsInstall').modal('show');

                    }, error: function (data) {
                        // alert(data);

                    }
                });

            }
        </script>
        <?php
    } else {
        ?>
        <div class="alert alert-warning alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            <strong>Aviso!</strong> No hay datos para mostrar
        </div>
        <?php
    }
}
?>

