<?php
/**
 * Created by PhpStorm.
 * User: Freddy Arvizu
 * Date: 18/02/2020
 * Time: 06:17 PM
 */

include "../config/config.php";//DB


$action = (isset($_REQUEST['action']) && $_REQUEST['action'] != NULL) ? $_REQUEST['action'] : '';

if ($action == 'delete') {

    $id = $_POST['id'];

    $sql = "DELETE FROM installation_control WHERE id=$id";
    $query_new_delete = mysqli_query($con, $sql);
    die();
}

if ($action == 'ajax') {
    // escaping, additionally removing everything that could be (html/javascript-) code
    $company_id = mysqli_real_escape_string($con, (strip_tags($_REQUEST['company_id'], ENT_QUOTES)));
    $start_at = mysqli_real_escape_string($con, (strip_tags($_REQUEST['start_at'], ENT_QUOTES)));
    $finish_at = mysqli_real_escape_string($con, (strip_tags($_REQUEST['finish_at'], ENT_QUOTES)));

    $sTable = "	tickets_detail td LEFT OUTER JOIN tickets t ON t.id = td.ticket_id  LEFT JOIN installation_control ic ON t.id = ic.ticket_id ";
    $sWhere = " WHERE  ic.group_id = " . $company_id;


    if ($company_id == "") {
        $company_id = 1;
    }

    if (!empty($start_at) && !empty($finish_at)) {
        $sWhere .= " AND (";

        $sWhere .= " ic.created_at BETWEEN'" . $start_at . "' AND '" . $finish_at . "'";


        $sWhere .= ')';
    }


    $sWhere .= " ORDER BY td.created_at DESC";
    include 'pagination.php'; //include pagination file
    //pagination variables
    $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page'])) ? $_REQUEST['page'] : 1;
    $per_page = 10; //how much records you want to show
    $adjacents = 4; //gap between pages after number of adjacents
    $offset = ($page - 1) * $per_page;
    //Count the total number of row in your table*/
    $count_query = mysqli_query($con, "SELECT count(*) AS numrows FROM $sTable  $sWhere");
    $row = mysqli_fetch_array($count_query);
    $numrows = $row['numrows'];
    $total_pages = ceil($numrows / $per_page);
    $reload = './installations';
    //main query to fetch the data
    $sql = "SELECT 	ic.id, t.order_number, td.results, ic.user_id, ic.created_at FROM  $sTable $sWhere LIMIT $offset,$per_page";

//    echo($sql);

    $query = mysqli_query($con, $sql);
    //loop through fetched data
    if ($numrows > 0) {

        ?>
        <table class="table table-striped jambo_table bulk_action">
            <thead>
            <tr class="headings">
                <th class="column-title">Folio</th>
                <th class="column-title">Programas Instalados</th>
                <th class="column-title">Usuario</th>
                <th class="column-title">Fecha</th>
                <th class="column-title no-link last"><span class="nobr"></span></th>
            </tr>
            </thead>
            <tbody>
            <?php
            while ($r = mysqli_fetch_array($query)) {


                $id = $r['id'];
                $order_number = $r['order_number'];
                $results = $r['results'];
                $user_id = $r['user_id'];
                $created_at = $r['created_at'];


                $user = mysqli_query($con, $queryu = "SELECT * FROM users WHERE id=" . $user_id);
                $u = mysqli_fetch_array($user);

                ?>
                <input type="hidden" value="<?php echo $order_number; ?>" id="order_number_<?php echo $id; ?>">
                <input type="hidden" value="<?php echo $results; ?>" id="programs_<?php echo $id; ?>">
                <input type="hidden" value="<?php echo $u['name']; ?>" id="name_<?php echo $id; ?>">
                <input type="hidden" value="<?php echo $created_at; ?>" id="created_at_<?php echo $id; ?>">


                <tr class="even pointer"  id="li_<?php echo $id ?>">
                    <td class="txt_inst_<?php echo $id ?>"><?php echo $order_number; ?></td>
                    <td class="txt_inst_<?php echo $id ?>"><?php echo $results; ?></td>
                    <td class="txt_inst_<?php echo $id ?>"><?php echo $u['name']; ?></td>
                    <td class="txt_inst_<?php echo $id ?>"><?php echo $created_at; ?></td>
                    <td class="txt_inst_<?php echo $id ?>" >
                          <span class="pull-right">
                            <a href="#" class='btn btn-default' title='Borrar registro de instalación'
                               onclick="deleteInstallation(<?php echo $id; ?>)">
                               <i class="glyphicon glyphicon-trash"></i>
                            </a>
                          </span>
                    </td>
                </tr>
                <?php
            } //end while

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
        <script>
            function deleteInstallation(id) {


                var parametros = new FormData();
                parametros.append("id", id);
                parametros.append("action", "delete");

                $.ajax({
                    type: "POST",
                    url: './ajax/historical-installation.php',
                    data: parametros,
                    contentType: false,
                    cache: false,
                    processData: false,
                    beforeSend: function () {
                        $('#loader').html('<img src="./images/ajax-loader.gif"> Cargando...');
                    },
                    success: function (data) {

                        $(".txt_inst_" + id).css("text-decoration", "line-through");
                        $("#li_" + id).css("opacity", "0.5");

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