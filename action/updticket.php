<?php
session_start();
include "../config/config.php";//DB


/* --------------------------------- */
/* -----------TESTING MODE---------- */
/* --------------------------------- */
if ($_POST["testing"]) {
    $testing = 1;
} else {
    $testing = 0;
}

if ($testing == 1) {

    $ticket_id = $_POST['ticket'];
    $asigned_id = $_POST['user_id'];

    $sqlTrack = "INSERT INTO tickets_tracking (ticket_id,user_taken_id,user_assigned_id,created_at,action ) VALUE ($ticket_id,$asigned_id,$asigned_id,NOW(),'Testing')";
    $query_new_insert_track = mysqli_query($con, $sqlTrack);


    $sqlUpd = "UPDATE tickets SET asigned_id=\"$asigned_id\", updated_at=NOW() WHERE id=$ticket_id";
    $queryUpd = mysqli_query($con, $sqlUpd);

    die();
}

/* --------------------------------- */
/* -----------ACTIVATE MODE---------- */
/* --------------------------------- */

if ($_POST["reactivate"]) {
    $reactivate = 1;
} else {
    $reactivate = 0;
}

if ($reactivate == 1) {

    $ticket_id = $_POST['ticket'];
    $asigned_id = $_POST['user_id'];

    $sqlTrack = "INSERT INTO tickets_tracking (ticket_id,user_taken_id,user_assigned_id,created_at,action ) VALUE ($ticket_id,$asigned_id,$asigned_id,NOW(),'Reactivated')";
    $query_new_insert_track = mysqli_query($con, $sqlTrack);


    $sqlUpd = "UPDATE tickets SET status_id=2, updated_at=NOW() WHERE id=$ticket_id";
    $queryUpd = mysqli_query($con, $sqlUpd);

    die();
}


if (empty($_POST['ticket_id'])) {
    $errors[] = "ID vacío";
} else if (empty($_POST['asigned_id'])) {
    $errors[] = "Necesita asignar el ticket";
} else if (
    !empty($_POST['ticket_id']) &&
    !empty($_POST['asigned_id'])
) {


//  $title = $_POST["title"];
//  $description = $_POST["description"];
    $category_id = $_POST["category_id"];
    $project_id = $_POST["project_id"];
    $priority_id = $_POST["priority_id"];
    $user_id = $_SESSION["user_id"];
    $asigned_id = $_POST["asigned_id"];
    $status_id = $_POST["status_id"];
    $kind_id = $_POST["kind_id"];
    $dpto = $_POST["dpto"];
    $id = $_POST['ticket_id'];
//    $company_id = $_POST['company_id'];

    if ($asigned_id == NULL) {
        $asigned_id = 0;
    }


    /* ------------------------------------------------------------------------------- */
    /* --------------------------------------FADE----------------------------------- */
    /* ------------------------------------------------------------------------------- */
    if (!empty($_POST['module_f'])) {
        $module_f = $_POST['module_f'];
        $fade_id_p = $_POST['name_f'];

        $sqlSel = "SELECT tf.ticket_id, tf.fade_id, f.name, f.module_id FROM tickets_has_fades tf LEFT JOIN fades f ON tf.fade_id = f.id WHERE tf.ticket_id=" . $id;
        $query_sel = mysqli_query($con, $sqlSel);
        $row_sel = mysqli_fetch_array($query_sel);
        $row_cnt = mysqli_num_rows($query_sel);


        if ($row_cnt > 0) {

            $fade_id = $row_sel['fade_id'];
            $ticket_id = $row_sel['ticket_id'];
            $fade_id_p = $row_sel['name'];
            $module_f = $row_sel['module'];

            $sql = "UPDATE tickets_has_fades SET ticket_id=$id, fade_id=$fade_id= WHERE ticket_id=$ticket_id";
            $query_update = mysqli_query($con, $sql);
            $log_action->addLine($_SESSION['user_id'], getcwd(), 'FADE', 'UPDATE', array("Ticket ID:" . $id), 'El FADE fue actualizado.', 5);

        } else {

            $sqlSel = "SELECT * FROM fades WHERE id= '" . $fade_id_p . "'";
            $query_sel = mysqli_query($con, $sqlSel);
            $row_sel = mysqli_fetch_array($query_sel);


            $fade_id = $row_sel['id'];

            $sql = "INSERT INTO tickets_has_fades (ticket_id, fade_id, created_at) VALUES ($id, $fade_id, NOW())";
            $query_insert = mysqli_query($con, $sql);
            $log_action->addLine($_SESSION['user_id'], getcwd(), 'FADE', 'INSERT', array("Ticket ID:" . $id), 'El ticket ha sido agregado a un FADE.', 5);


        }


    }


    function remove_blanks($cadena)
    {
        return preg_replace(['/\s+/', '/^\s|\s$/'], [' ', ''], $cadena);
    }


    if (!empty($_POST['program-list-array'])) {

        $programs = str_replace("×", ",", $_POST['program-list-array']);
        $programs = remove_blanks($programs);
        $programs =str_replace(' ', '', $programs);
        $programs = substr($programs, 0, -1);


        $sqlSel = "SELECT *  FROM  tickets_detail WHERE ticket_id=" . $id;
        $query_sel = mysqli_query($con, $sqlSel);
        $row_cnt = mysqli_num_rows($query_sel);

        if ($row_cnt > 0) {
            $sql = "UPDATE tickets_detail SET results=\"$programs\" WHERE ticket_id=" . $id;


            $query_update = mysqli_query($con, $sql);
            $log_action->addLine($_SESSION['user_id'], getcwd(), 'TICKETS DETAIL', 'UPDATE', array("Ticket ID:" . $id), 'El ticket ha sido actualizado satisfactoriamente.', 5);

        } else {
            $sql = "INSERT INTO tickets_detail(ticket_id, results, created_at) VALUES ($id, \"$programs\", NOW())";

            $query_insert = mysqli_query($con, $sql);
            $log_action->addLine($_SESSION['user_id'], getcwd(), 'TICKETS DETAIL', 'INSERT', array("Ticket ID:" . $id), 'El ticket ha sido actualizado satisfactoriamente.', 5);
        }
    }


    if (!empty($_POST['module'])) {
        $module = $_POST['module'];

        $sqlSel = "SELECT *  FROM  tickets_detail WHERE ticket_id=$id";
        $query_sel = mysqli_query($con, $sqlSel);
        $row_cnt = mysqli_num_rows($query_sel);

        if ($row_cnt > 0) {
            $sql = "UPDATE tickets_detail SET module_id=\"$module\" WHERE ticket_id=$id";
            $query_update = mysqli_query($con, $sql);
            $log_action->addLine($_SESSION['user_id'], getcwd(), 'TICKETS DETAIL', 'UPDATE', array("Ticket ID:" . $id), 'El ticket ha sido actualizado satisfactoriamente.', 5);

        } else {
            $sql = "INSERT INTO tickets_detail(ticket_id, module_id, created_at) VALUES ($id, \"$module\", NOW())";
            $query_insert = mysqli_query($con, $sql);
            $log_action->addLine($_SESSION['user_id'], getcwd(), 'TICKETS DETAIL', 'INSERT', array("Ticket ID:" . $id), 'El ticket ha sido actualizado satisfactoriamente.', 5);

        }
    }


    if (!empty($_POST['program_name'])) {
        $program = $_POST['program_name'];

        $sqlSel = "SELECT *  FROM  tickets_detail WHERE ticket_id=$id";
        $query_sel = mysqli_query($con, $sqlSel);
        $row_cnt = mysqli_num_rows($query_sel);

        if ($row_cnt > 0) {
            $sql = "UPDATE tickets_detail SET program=\"$program\" WHERE ticket_id=$id";
            $query_update = mysqli_query($con, $sql);
            $log_action->addLine($_SESSION['user_id'], getcwd(), 'TICKETS DETAIL', 'UPDATE', array("Ticket ID:" . $id), 'El ticket ha sido actualizado satisfactoriamente.', 5);

        } else {
            $sql = "INSERT INTO tickets_detail(ticket_id, program, created_at) VALUES ($id, \"$program\", NOW())";
            $query_insert = mysqli_query($con, $sql);
            $log_action->addLine($_SESSION['user_id'], getcwd(), 'TICKETS DETAIL', 'INSERT', array("Ticket ID:" . $id), 'El ticket ha sido actualizado satisfactoriamente.', 5);

        }
    }


    $querySelect = mysqli_query($con, $sqlSelect = "SELECT * FROM `tickets` WHERE id=$id");

    foreach ($querySelect as $sel) {
        $asigned_id_sel = $sel['asigned_id'];
        $user_created_ticket = $sel['user_id'];
    }


    $querySelectTT = mysqli_query($con, $sqlSelectTT = "SELECT * FROM `tickets_tracking` ORDER BY $id DESC LIMIT 1;");

    foreach ($querySelectTT as $tt) {
        $isTest = $tt['action'];
    }


    $sql = "UPDATE tickets SET category_id=\"$category_id\",project_id=\"$project_id\",priority_id=\"$priority_id\",status_id=\"$status_id\",kind_id=\"$kind_id\",asigned_id=\"$asigned_id\", updated_at=NOW() WHERE id=$id";
    $log_action->addLine($_SESSION['user_id'], getcwd(), 'TICKETS', 'EDITAR', array("Ticket ID:" . $id), 'El ticket ha sido actualizado satisfactoriamente.', 5);


    $query_update = mysqli_query($con, $sql);
    if ($query_update) {
        $messages[] = "El ticket ha sido actualizado satisfactoriamente.";


        if ($asigned_id_sel <> $asigned_id OR $isTest <> 'Testing') {
            $sqlTrack = "INSERT INTO tickets_tracking (ticket_id,user_taken_id,user_assigned_id,created_at,action ) VALUE ($id,$user_id,$asigned_id,NOW(),'Assigned')";
            $query_new_insert_track = mysqli_query($con, $sqlTrack);
        }

//Cerrado
        if ($status_id == 3) {
            $sqlTrack = "INSERT INTO tickets_tracking (ticket_id,user_taken_id,user_assigned_id,created_at,action ) VALUE ($id,$user_id,$asigned_id,NOW(),'Finished')";
            $query_new_insert_track = mysqli_query($con, $sqlTrack);

            $sqlNoti = "INSERT INTO notifications (reference,user_id, type, created_at) VALUE ($id,$user_created_ticket,'ticket',NOW())";
            $query_new_insert_noti = mysqli_query($con, $sqlNoti);

        }
//Cancelado
        if ($status_id == 4) {
            $sqlTrack = "INSERT INTO tickets_tracking (ticket_id,user_taken_id,user_assigned_id,created_at,action ) VALUE ($id,$user_id,$asigned_id,NOW(),'Cancelled')";
            $query_new_insert_track = mysqli_query($con, $sqlTrack);

            $sqlNoti = "INSERT INTO notifications (reference,user_id, type, created_at) VALUE ($id,$user_created_ticket,'ticket',NOW())";
            $query_new_insert_noti = mysqli_query($con, $sqlNoti);
        }

        $cause = "Ajuste";
        $content = 'Se ha actualizado la información del Ticket';
        //Get Ticket Data and Send Mail

        if ($status_id <> 3 OR $status_id <> 4) {
            $getDataTicket = $notification->updTicket($id, $cause, $content, 0, '');
        }

    } else {
        $log_error->addLine($_SESSION['user_id'], getcwd(), 'TICKET', 'EDITAR', array("Ticket ID:" . $id), 'Lo siento algo ha salido mal intenta nuevamente =>' . mysqli_error($con), 2);
        $errors [] = "Lo siento algo ha salido mal intenta nuevamente." . mysqli_error($con);
    }
} else {
    $errors [] = "Error desconocido.";
}

if (isset($errors)) {

    ?>
    <div class="alert alert-danger" role="alert">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>Error!</strong>
        <?php
        foreach ($errors as $error) {
            echo $error;
        }
        ?>
    </div>
    <?php
}
if (isset($messages)) {
    ?>
    <div class="alert alert-success" role="alert">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>¡Bien hecho!</strong>
        <?php
        foreach ($messages as $message) {
            echo $message;
        }
        ?>
    </div>
    <?php
}

?>

