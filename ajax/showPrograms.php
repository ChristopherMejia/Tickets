<?php
/**
 * Created by PhpStorm.
 * User: Freddy Arvizu
 * Date: 17/02/2020
 * Time: 11:33 AM
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

if ($action == 'update') {

    $ticket_id = $_POST['ticket_id'];
    $group_id = $_POST['company_id'];
    $user_id = $_POST['user_id'];


    $sql = "INSERT INTO installation_control (group_id, user_id, ticket_id, created_at)
             VALUES ($group_id,$user_id, $ticket_id,NOW())";

    $query_new_insert = mysqli_query($con, $sql);


    die();
}

if ($action == 'delete') {

    $ticket_id = $_POST['ticket_id'];
    $group_id = $_POST['company_id'];
    $user_id = $_POST['user_id'];


    $sql = "DELETE FROM installation_control WHERE group_id=$group_id AND ticket_id = $ticket_id";
    $query_new_delete = mysqli_query($con, $sql);

    die();
}

if ($action == 'ajax') {


    // escaping, additionally removing everything that could be (html/javascript-) code
    $group_id = mysqli_real_escape_string($con, (strip_tags($_REQUEST['group_id'], ENT_QUOTES)));
    if ($group_id == "") {
        $group_id = 1;
    }


    $sTable = "	tickets_detail td  LEFT OUTER JOIN tickets t ON t.id = td.ticket_id";
    $sWhere = " WHERE  t.status_id = 3";

    $sWhere .= "  AND td.results IS NOT NULL 
                    AND NOT EXISTS (
                    SELECT NULL 
                    FROM
                        installation_control ic 
                    WHERE
                    t.id = ic.ticket_id 
                    AND ic.group_id = " . $group_id . " ) ORDER BY td.created_at DESC";
    include 'pagination.php'; //include pagination file
    //pagination variables
    $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page'])) ? $_REQUEST['page'] : 1;
    $per_page = 20; //how much records you want to show
    $adjacents = 4; //gap between pages after number of adjacents
    $offset = ($page - 1) * $per_page;
    //Count the total number of row in your table*/
    $count_query = mysqli_query($con, "SELECT count(*) AS numrows FROM $sTable  $sWhere");
    $row = mysqli_fetch_array($count_query);
    $numrows = $row['numrows'];
    $total_pages = ceil($numrows / $per_page);
    $reload = './installations';
    //main query to fetch the data
    $sql = "SELECT 	t.id, t.order_number, td.results, ( SELECT tt.created_at FROM tickets_tracking tt WHERE t.id = tt.ticket_id AND tt.action = 'Finished' ORDER BY tt.created_at DESC LIMIT 1) AS created_at  FROM  $sTable $sWhere LIMIT $offset,$per_page";


//    echo $sql;

    $query = mysqli_query($con, $sql);
    //loop through fetched data
    if ($numrows > 0) {
        ?>

        <table class="table table-striped jambo_table">
            <thead>
            <tr class="headings">
                <th class="column-title"></th>
                <th class="column-title">Programas</th>
                <th class="column-title">Fecha Cierre</th>
                <th class="column-title"><span class="nobr"></span>Folio<span class="nobr"></span></th>
            </tr>
            </thead>
            <tbody>

            <?php while ($p = mysqli_fetch_array($query)) { ?>
                <tr class="even pointer" id="li_<?php echo $p['id'] ?>">
                    <td>
                        <input type="checkbox" class="checked_program" value="<?php echo $p['id'] ?>">
                    </td>
                    <td class="txt_inst_<?php echo $p['id'] ?>">
                        <?php echo $p['results'] ?>
                    </td>
                    <td class="txt_date_<?php echo $p['id'] ?>">
                        <?php echo $p['created_at'] ?>
                    </td>
                    <td class="txt_inst_<?php echo $p['id'] ?>">

                        <a href="#" title="Vista Rápida" onclick="openModal('<?php echo $p['order_number'] ?>')">
                            <i class="fa fa-hand-pointer-o"></i>
                            <?php echo $p['order_number'] ?>
                        </a>
                    </td>
                </tr>

            <?php } ?>

            <tr>
                <td colspan=12>
                        <span class="pull-right">
                        <?php echo paginate($reload, $page, $total_pages, $adjacents, 'load'); ?>
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

    <?php } else { ?>

        <div class="alert alert-warning alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            <strong>Aviso!</strong> No hay datos para mostrar
        </div>

    <?php } ?>
    <!--<input type="hidden" name="program-list-array" id="program-list-array">-->

    <script>

        function openModal(order) {


            data = new FormData();
            data.append('order_number', order);
            data.append('action', "modal");

            $.ajax({
                type: 'POST',
                url: 'ajax/showPrograms.php',
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

        $(document).ready(function () {

            $(".checked_program").click(function () {
                if ($(".checked_program").is(':checked')) {

                    var value = this.value;
                    var company_id = $("#company_id").val();
                    var user_id = $("#user_installation").val();

                    var parametros = new FormData();
                    parametros.append("ticket_id", value);
                    parametros.append("company_id", company_id);
                    parametros.append("user_id", user_id);
                    parametros.append("action", "update");

                    $.ajax({
                        type: "POST",
                        url: './ajax/showPrograms.php',
                        data: parametros,
                        contentType: false,
                        cache: false,
                        processData: false,
                        beforeSend: function () {
                            $('#loader').html('<img src="./images/ajax-loader.gif"> Cargando...');
                        },
                        success: function (data) {
                            $(".txt_inst_" + value).css("text-decoration", "line-through");
                            $("#li_" + value).css("opacity", "0.5");
                        }
                    });

                } else {

                    var value = this.value;
                    var company_id = $("#company_id").val();
                    var user_id = $("#user_installation").val();


                    var parametros = new FormData();
                    parametros.append("ticket_id", value);
                    parametros.append("company_id", company_id);
                    parametros.append("user_id", user_id);
                    parametros.append("action", "delete");

                    $.ajax({
                        type: "POST",
                        url: './ajax/showPrograms.php',
                        data: parametros,
                        contentType: false,
                        cache: false,
                        processData: false,
                        beforeSend: function () {
                            $('#loader').html('<img src="./images/ajax-loader.gif"> Cargando...');
                        },
                        success: function (data) {


                            $(".txt_inst_" + value).css("text-decoration", "blink");
                            $("#li_" + value).css("opacity", "1");

                        }
                    });

                }
            });

        });
    </script>

    <?php
}
?>