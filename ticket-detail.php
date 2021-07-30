<?php
/**
 * Created by PhpStorm.
 * User: Freddy Arvizu
 * Date: 25/07/2019
 * Time: 01:04 PM
 */

$title = "Ticket | ";
include "includes/head.php";


$role = $global->getRol($user_id);
$order_number = $_GET["order"];
$page = "ticket-detail?order=" . $order_number;

include "modal/new_note.php";
include "config/presence.php";
$div_usersPrecense = 'block';
include "includes/sidebar.php";


$idTicket = $global->return_id_ticket($order_number);
$infoTicket = $global->getInfoTicket($idTicket);
$infoDetailTicket = $global->getInfoDetailTicket($idTicket);
$infoFade = $global->getInfoFade($idTicket);
$modules = mysqli_query($con, "SELECT * FROM modules ORDER BY name ASC");

$alert->updateNotificationBell($idTicket, $user_id);
$alert->upd_bell_noti_msg($idTicket, $user_id);


$sqlTest = mysqli_query($con, $queryTest = "SELECT tt.action, u.`name` FROM `tickets_tracking` tt LEFT JOIN users u ON tt.user_assigned_id = u.id WHERE	tt.ticket_id = " . $idTicket . " ORDER BY tt.id DESC LIMIT 1;");
if ($c = mysqli_fetch_array($sqlTest)) {
    $mod_test_action = $c['action'];
    $mod_test_name = $c['name'];
}

if ($infoTicket['status_id'] == 3 OR $infoTicket['status_id'] == 4) {
    $disabled = 'disabled';
}

if ($infoTicket['status_id'] == 3 OR $infoTicket['status_id'] == 4 && $role['id'] <= 2) {
    $reactivate = 'inline-block';
} else {
    $reactivate = 'none';
}

if ($infoDetailTicket['ticket_id'] <> null) {
    $disabled_detail = '';
} else {
    $disabled_detail = 'disabled';
}


$sqlTrack = mysqli_query($con, $queryTrack = "SELECT tt.action, ut.`name` do, ua.`name` assigned, tt.created_at FROM `tickets_tracking` tt LEFT JOIN users ut ON tt.user_taken_id = ut.id LEFT JOIN users ua ON tt.user_assigned_id = ua.id WHERE tt.ticket_id =" . $idTicket);


$sqlTrack_Prio = mysqli_query($con, $queryTrackPrio = "SELECT DISTINCT tt.action, ut.`name` do, ua.`name` assigned, t.priority_id, tt.created_at FROM `tickets_tracking` tt LEFT JOIN users ut ON tt.user_taken_id = ut.id LEFT JOIN users ua ON tt.user_assigned_id = ua.id LEFT JOIN tickets t ON t.id = tt.ticket_id WHERE tt.ticket_id =" . $idTicket . " AND tt.action = 'Assigned'");
$rowTrackPri = mysqli_fetch_array($sqlTrack_Prio);

$sqlPri = mysqli_query($con, "SELECT * FROM priorities WHERE id=" . $rowTrackPri['priority_id']);
$rowPri = mysqli_fetch_array($sqlPri);

$has_permissions = $permission->token_permissions($infoTicket['user_id'], $_SESSION['token'], $infoTicket['status_id']);


if (!$has_permissions) {
    echo "<script>setTimeout(function(){ window.history.back();}, 3000);</script>";
} else {

    ?>

    <div> <!-- Modal -->
        <a data-toggle="modal" data-target=".bs-example-modal-lg-add" class="btn-floating"
           id="a-add-note" title="Agregar Nota">
            <i class="fa fa-plus" aria-hidden="true"></i>
        </a>
    </div>
    <div class="right_col" role="main"><!-- page content -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="tickets">Tickets</a></li>
                <li class="breadcrumb-item active" aria-current="page">Order Number - <?php echo $order_number ?></li>

            </ol>
        </nav>

        <div class="container-fluid">
            <div class="page-title">


                <div id="exTab1" class="container">
                    <ul class="nav nav-pills">
                        <li class="active">
                            <a href="#a1" data-toggle="tab"> Generales</a>
                        </li>
                        <li class="action-hidden">
                            <a href="#a2" data-toggle="tab"> Track</a>
                        </li>

                    </ul>

                    <div class="tab-content clearfix">
                        <div class="tab-pane active" id="a1">
                            <div class="x_panel created-info">
                                <div class="card-header-tab">
                                    <small> Creado por:</small>
                                </div>
                                <div class="col-md-2">
                                    <img src="images/logos/<?php echo $infoTicket['company_id'] ?>.png"
                                         class="img-responsive">
                                </div>
                                <div class="col-md-6">
                                    <h4><?php echo $infoTicket['name'] ?></h4>
                                    <p><?php echo $infoTicket['company'] ?></p>
                                    <small><?php echo $infoTicket['created_at'] ?></small>

                                </div>
                                <div class="col-md-2"></div>

                                <?php if ($infoTicket['status_id'] == 5) { ?>
                                    <div class="col-md-2 takencl action-hidden">

                                        <?php if ($mod_test_action == 'Testing') { ?>


                                            <button type="button" id="btn_attendTicket"
                                                    class="btn btn-xs btn-primary float-right btn-take-ticket disabled"
                                                    ondblclick="tackeTicket(true)"
                                                    title="Doble click para forzar el auto-asignado">
                                                <i class="fa fa-pause" aria-hidden="true"></i>
                                                En Pruebas
                                            </button>
                                            <small class="float-right ">Tomado por: <b><?php echo $mod_test_name ?></b>
                                            </small>

                                        <?php } else { ?>
                                            <button type="button" id="btn_attendTicket"
                                                    class="btn btn-xs btn-primary float-right btn-take-ticket"
                                                    onclick="tackeTicket()">
                                                <i class="fa fa-bug animated rubberBand infinite"
                                                   aria-hidden="true"></i>
                                                Atender
                                            </button>
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                            </div>
                            <div class="clearfix"></div>
                            <div class="row <?php echo $disabled; ?>">
                                <!-- 4 Cerrado-->
                                <div class="column left">
                                    <li class="list-group-item action action-hidden-dev">
                                        <button type="button" class="btn-primary-outline"
                                                onclick="getDataTicket('<?php echo $id; ?>');"
                                                class="action-edit"
                                                id="action-edit-info-ticket">
                                            <i class="fa fa-pencil-square" aria-hidden="true"></i> Editar
                                        </button>
                                        &nbsp;
                                        &nbsp;
                                        <button type="button" class="btn-primary-outline" <?php echo $disabled_detail ?>
                                                data-toggle="modal"
                                                data-target="#md_ticketDetail">
                                            <i class="fa fa-plus" aria-hidden="true"></i> Detalles Adicionales
                                        </button>

                                        <button type="button" class="btn-primary-outline action-hidden"
                                                onclick="reactivateTicket()" style="display: <?php echo $reactivate ?>">
                                            <i class="fa fa-toggle-on" aria-hidden="true"></i> Reactivar
                                        </button>
                                    </li>

                                    <div id="result_ticket">


                                    </div>
                                </div>

                                <div class="column middle">
                                    <div class="x_panel">
                                        <div class="x_title">
                                            <h2>
                                                <?php echo $title; ?>
                                                -
                                                <a href="#">#<?php echo $order_number ?></a>
                                            </h2>
                                            <ul class="nav navbar-right panel_toolbox">
                                                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                                </li>
                                                <li><a class="close-link"><i class="fa fa-close"></i></a>
                                                </li>
                                            </ul>
                                            <div class="clearfix"></div>
                                        </div>


                                        <ul class="nav nav-tabs">
                                            <li class="active">
                                                <a href="#1" data-toggle="tab">
                                                    <i class="fa fa-commenting-o" aria-hidden="true"></i> Comentarios
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#2" data-toggle="tab">
                                                    <i class="fa fa-sticky-note-o" aria-hidden="true"></i> Notas
                                                    Personales
                                                </a>
                                            </li>

                                        </ul>

                                        <div class="tab-content ">
                                            <div class="tab-pane active" id="1">
                                                <div class="mesgs">
                                                    <div class="msg_history" id="msg_history">
                                                        <!--MSJ HISTORY-->
                                                    </div>
                                                    <div class="add-new-comment">
                                                        <form class="form-horizontal form-label-left input_mask"
                                                              method="post"
                                                              id="add-comment"
                                                              name="add-comment" autocomplete="off">
                                                            <div class="type_msg">
                                                                <div class="input_msg_write">
                                                                        <textarea class="form-control write_msg"
                                                                                  id="write_msg"
                                                                                  name="write_msg"
                                                                                  placeholder="Escriba un comentario..."
                                                                                  onInput="edValueKeyPress()" <?php echo $disabled ?>
                                                                               autofocus></textarea>

                                                                    <!--                                                                   <code name="write_msg_send" id="write_msg_send"></code>-->

                                                                    <input type="hidden" id="imagesComment"
                                                                           name="imagesComment">

                                                                    <input type="hidden" id="comment_user" name="user"
                                                                           value="<?php echo $user_id ?>"/>

                                                                    <input type="hidden" id="comment_ticket"
                                                                           value="<?php echo $idTicket ?>"
                                                                           name="ticket"/>
                                                                    <input type="hidden" id="status_ticket"
                                                                           value="<?php echo $infoTicket['status_id'] ?>"
                                                                           name="status"/>

                                                                    <input type="hidden" id="ccustomer"
                                                                           value="0" name="ccustomer"/>
                                                                    <button class="msg_send_btn" type="submit">
                                                                        <i class="fa fa-paper-plane-o"
                                                                           aria-hidden="true"></i>
                                                                    </button>
                                                                    <span id="result"></span>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>


                                                    <div class="functions">
                                                        <i class="fa fa-paperclip fa-2x attach-file" aria-hidden="true"
                                                           title="Adjuntar Archivo" disabled=""></i>
                                                        <!--                                        <i class="fa fa-user-plus fa-2x" aria-hidden="true" style="cursor:not-allowed;"-->
                                                        <!--                                           title="Función no disponible"></i>-->
                                                        <form action="#" method="post" id="upload-files"
                                                              enctype="multipart/form-data">
                                                            <input type="file"
                                                                   class="form-control-file hide-element fileupload"
                                                                   name="files[]"
                                                                   id="files"
                                                                   multiple>
                                                            <input type="hidden" class="form-control" name="user_id"
                                                                   value="<?php echo $user_id ?>">
                                                            <input type="hidden" class="form-control" name="ticket_id"
                                                                   id="ticket_id"
                                                                   value="<?php echo $idTicket ?>">
                                                        </form>
                                                        <small><b>Archivos Admitidos:</b> 'jpg', 'png', 'jpeg', 'gif',
                                                            'pdf', 'xlsx', 'xls', 'xml', 'csv',
                                                            'docx','doc','log'
                                                        </small>

                                                    </div>
                                                    <div class="form-group action-hidden">
                                                        <label class="checkbox-inline">
                                                            <input type="checkbox" id="chk_ccustomer"
                                                                   onchange="sendCustomer()"> ¿Responder al
                                                            cliente?
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane" id="2">
                                                <h3>Notas Personales</h3>
                                                <div id="notes"></div>
                                            </div>
                                        </div>
                                    </div>


                                </div>

                                <div class="column right">

                                    <div class="card-hover-shadow-2x mb-3 card">
                                        <div class="card-header-tab card-header">
                                            Adjuntos
                                        </div>
                                        <div class="card-body">
                                            <div id="result_files"></div>
                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="tab-pane" id="a2">
                            <div class="x_panel created-info">
                                <div class="container">
                                    <div class="timeline-centered">
                                        <h1>
                                                    <span class="badge badge-danger bg-<?php echo $rowPri['badge']; ?>">
                                                        <?php echo $rowPri['name']; ?>
                                                    </span>
                                            
                                            <span class="badge badge-danger">
                                            <?php echo $rowTrackPri['created_at']; ?>
                                            </span>
                                            <span class="badge badge-danger">
                                            <?php echo $rowTrackPri['do']; ?>
                                            </span>
                                        </h1>

                                        <?php
                                        $class = 0;
                                        foreach ($sqlTrack AS $t) {

                                            if ($t['action'] == 'Created') {
                                                $bg = 'bg-success';
                                            } elseif ($t['action'] == 'Assigned') {
                                                $bg = 'bg-warning';
                                            } elseif ($t['action'] == 'Cancelled') {
                                                $bg = 'bg-danger';
                                            } elseif ($t['action'] == 'Finished') {
                                                $bg = 'bg-primary';
                                            } elseif ($t['action'] == 'Testing') {
                                                $bg = 'bg-info';
                                            } elseif ($t['action'] == 'Reactivate') {
                                                $bg = 'bg-success';
                                            }

                                            $hour = date("h:i:s A", strtotime($t['created_at']));
                                            $date = date("Y/m/d", strtotime($t['created_at']));

                                            $class++;


                                            ?>


                                            <article
                                                    class="timeline-entry <?php if ($class % 2 != 0) { ?> right-aligned  <?php } else { ?> left-aligned <?php } ?>">

                                                <div class="timeline-entry-inner">
                                                    <time class="timeline-time" datetime="2014-01-10T03:45">
                                                        <span><?php echo $hour ?></span>
                                                        <span><?php echo $date ?></span></time>

                                                    <div class="timeline-icon <?php echo $bg ?>">
                                                        <i class="entypo-suitcase"></i>
                                                    </div>

                                                    <div class="timeline-label">
                                                        <h2>

                                                            <a href="#">
                                                                <b><?php echo $t['action'] ?></b>
                                                                <span><?php echo $t['do'] ?></span>
                                                            </a>
                                                        </h2>
                                                        <p>
                                                            <?php if ($t['action'] == 'Assigned') { ?>
                                                                <?php echo $t['do']; ?>
                                                                <i class="fa fa-share"></i>
                                                                <?php echo $t['assigned'] ?>
                                                            <?php } ?>
                                                        </p>
                                                    </div>
                                                </div>

                                            </article>


                                        <?php } ?>


                                        <!-- left-aligned %factor 2% -->
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div><!-- /page content -->
    </div>
    <div class="modal fade " tabindex="-1" role="dialog" id="md_ticketDetail" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <!-- Modal content-->
            <div class="modal-content">
                <form class="form-horizontal form-label-left input_mask" method="post" id="frm-add-ticket-detail"
                      name="frm-add-ticket"
                      enctype="multipart/form-data">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Detalles</h4>
                    </div>
                    <div class="modal-body">
                        <div id="result"></div>
                        <div class="form-group ">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="module">
                                Modulo

                            </label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <select class="form-control" name="module_id" id="module_id_r" disabled>
                                    <option value="" selected disabled>-[Seleccione una opción]-</option>
                                    <?php foreach ($modules as $m) { ?>
                                        <option value="<?php echo $m['id']; ?>"
                                            <?php if ($m['id'] == $infoDetailTicket['module_id']) {
                                                echo 'selected';
                                            } else echo '' ?>
                                        ><?php echo $m['name']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group ">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="module">
                                Menu

                            </label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <input class="form-control typeahead" placeholder="Escribe para buscar..." type="text"
                                       autocomplete="off" name="program_name" id="program_name_r"
                                       value="<?php echo $infoDetailTicket['program'] ?>" readonly>
                            </div>
                        </div>
                        <div class="form-group ">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">
                                Observaciones
                            </label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <textarea style=" min-height: 200px;resize: vertical;" name="observations"
                                          id="observations_r"
                                          class="text-custom form-control col-md-7 col-xs-12"
                                          placeholder="Añada una observacion detallada..."
                                          readonly><?php echo $infoDetailTicket['observations'] ?></textarea>
                            </div>
                        </div>

                        <?php if (!empty($infoDetailTicket['results'])) { ?>
                            <div class="form-group ">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">
                                    Programas a Instalar
                                </label>
                                <div class="col-md-9 col-sm-9 col-xs-12">
                                <textarea style=" min-height: 200px;resize: vertical;" name="programs"
                                          id="programs_r"
                                          class="text-custom form-control col-md-7 col-xs-12"
                                          placeholder="..."
                                          readonly><?php echo $infoDetailTicket['results'] ?></textarea>
                                </div>
                            </div>
                        <?php } ?>
                    </div>

                </form>
            </div>

        </div>
    </div>
    <input type="hidden" value="<?php echo $role['id'] ?>" id="role_user">

    <?php

    include "modal/uploads.php";
    include("modal/upd_ticket.php");
    include "includes/footer.php";

    ?>

    <script>
        function sendCustomer() {

            if ($('#chk_ccustomer').prop('checked')) {
                Swal.fire({
                    title: '¿Estás seguro de continuar?',
                    text: "El seleccionar esta opción tu comentario será visible para el cliente",
                    type: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#d6972e',
                    cancelButtonColor: '#c1c1c1',
                    confirmButtonText: '¡Sí, continuar!'
                }).then((result) => {
                    if (result.value) {


                        $('#ccustomer').val(1);


                    } else {
                        $('#chk_ccustomer').prop('checked', false)
                    }
                });

            } else {
                $('#ccustomer').val(0);
            }

        }
    </script>

    <script type="text/javascript" src="js/ticket.js"></script>
    <script type="text/javascript" src="js/fades.js"></script>
    <?php

}

?>
<style>
    .bg-danger {
        background: red !important;
    }
</style>
</body>
</html>

