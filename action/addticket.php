<?php
session_start();
include "../config/config.php";//DB

$user_id = $_POST["user_id"];
$role = $permission->get_rol($user_id);

/*Inicia validacion del lado del servidor*/

if (empty($_POST['title'])) {
    $errors[] = "Titulo vacío";
} else if (empty($_POST['description'])) {
    $errors[] = "Description vacío";
} else if (empty($_POST['asigned_id']) AND ($role['id'] <> 4 AND $role['id'] <> 5)) {
    $errors[] = "Asignación de ticket vacía";
} else if (
    !empty($_POST['title']) &&
    !empty($_POST['description'])

) {


    $user_id = $_POST["user_id"];
    $company_id = $_POST["company_id"];
    $title = $_POST["title"];
    $description = htmlspecialchars($_POST["description"]);
    //optional


    if (!empty($_POST['category_id'])) {
        $category_id = $_POST["category_id"];
    } else {
        $category_id = 1;
    }

    if (!empty($_POST['project_id'])) {
        $project_id = $_POST["project_id"];
    } else {
        $project_id = 1;
    }

    if (!empty($_POST['priority_id'])) {
        $priority_id = $_POST["priority_id"];
    } else {
        $priority_id = 3;
    }
    if (!empty($_POST['status_id'])) {
        $status_id = $_POST["status_id"];
    } else {
        $status_id = 1;
    }

    if (!empty($_POST['kind_id'])) {
        $kind_id = $_POST["kind_id"];
    } else {
        $kind_id = 1;
    }

    if (!empty($_POST['asigned_id'])) {
        $asigned_id = $_POST["asigned_id"];
    } else {
        $asigned_id = 0;
    }


    $created_at = "NOW()";

    $role = $permission->get_rol($user_id);


    /* ------------------------------------------------------------------------------- */
    /* --------------------------------------VAR´s DETAIL----------------------------------- */
    /* ------------------------------------------------------------------------------- */
    $observations = htmlspecialchars($_POST["observations"]);
    $program = $_POST["program"];
    $module_id = $_POST["module"];


    /* ------------------------------------------------------------------------------- */
    /* --------------------------------------GET FOLIO----------------------------------- */
    /* ------------------------------------------------------------------------------- */

    $order = mysqli_query($con, $queryOrder = "SELECT series1,series2,folio FROM `orders` WHERE series1='T'");

//    echo $queryOrder;

    foreach ($order as $o) {
        $series1 = $o['series1'];
        $series2 = $o['series2'];
        $folio = $o['folio'];
    }
    $order_number = $series1 . $series2 . '-' . $folio;


    $sql = "INSERT INTO tickets (company_id,order_number,title,description,category_id,project_id,priority_id,user_id,asigned_id,status_id,kind_id, created_at) VALUES ($company_id,\"$order_number\",\"$title\",\"$description\",$category_id,$project_id,$priority_id,$user_id,$asigned_id,$status_id,$kind_id,$created_at)";
    $query_new_insert = mysqli_query($con, $sql);
    $ticket = mysqli_insert_id($con);


    if ($kind_id == 2) {
        $module_id = $_POST["module_f"];
        $name_f = $_POST["name_f"];

        $fades = mysqli_query($con, "SELECT * FROM `fades` WHERE id='$name_f'");

        foreach ($fades as $o) {
            $id_f = $o['id'];
        }

        $sqlFade = "INSERT INTO tickets_has_fades (ticket_id, fade_id, created_at) VALUES ($ticket,$id_f,$created_at)";
        $query_new_insert_fade = mysqli_query($con, $sqlFade);

//        var_dump($sqlFade);
    }

    /* ------------------------------------------------------------------------------- */
    /* --------------------------------------DETAIL----------------------------------- */
    /* ------------------------------------------------------------------------------- */


    if (!empty($module_id) AND !empty($program)) {
        $sql_detail = "INSERT INTO tickets_detail (ticket_id,module_id,program,observations, created_at) VALUES ($ticket,$module_id,\"$program\",\"$observations\",$created_at)";
        $query_detail_insert = mysqli_query($con, $sql_detail);
//        var_dump($sql_detail);
    }
    if ($query_detail_insert) {
        $log_action->addLine($_SESSION['user_id'], getcwd(), 'TICKET', 'AGREGAR DETALLE', array("Ticket ID:" . $ticket), 'El detalle del ticket ha sido ingresado satisfactoriamente', 5);
    } else {
        $log_error->addLine($_SESSION['user_id'], getcwd(), 'TICKET', 'AGREGAR DETALLE', array("Ticket ID:" . $id), 'Hubo un problema con la insercción del detalle: ' . mysqli_error($con), 1);
    }

//    var_dump($query_detail_insert);


    $folder = '../public/attach/' . $ticket;
    if (!file_exists($folder)) {
        mkdir($folder, 0777, true);
    }


    $query_status = mysqli_query($con, "SELECT * FROM status WHERE id=" . $status_id);
    foreach ($query_status as $s) {
        $status = $s['name'];
    }

    if ($query_new_insert) {
        $log_action->addLine($_SESSION['user_id'], getcwd(), 'TICKET', 'AGREGAR', array("Ticket ID:" . $ticket), 'Tu ticket ha sido ingresado satisfactoriamente', 5);
        $messages[] = "Tu ticket ha sido ingresado satisfactoriamente.";
        $sqlTrack = "INSERT INTO tickets_tracking (ticket_id,user_taken_id,user_assigned_id,created_at,action ) VALUES ($ticket,$user_id,$asigned_id,$created_at,'Created')";
        $query_new_insert_track = mysqli_query($con, $sqlTrack);

        $newFolio = $folio + 1;
        /* update folio order */
        $upd_folio = "UPDATE orders SET folio=$newFolio WHERE series1 = 'T'";
        $query_upd_folio = mysqli_query($con, $upd_folio);

//        echo $upd_folio;

        if (!empty($_FILES['files'])) {
            // File upload configuration
            $targetDir = "../public/attach/$ticket/";
            $allowTypes = array('jpg', 'png', 'jpeg', 'gif', 'pdf', 'xlsx', 'xls', 'xml', 'csv', 'docx', 'doc', 'xml', 'JPG', 'PNG', 'JPEG', 'GIF', 'PDF', 'XLSX', 'XLS', 'CSV', 'DOCX', 'DOC', 'XML');

            $files_arr = array();
            foreach ($_FILES['files']['name'] as $key => $val) {
                $image_name = $_FILES['files']['name'][$key];
                $tmp_name = $_FILES['files']['tmp_name'][$key];
                $size = $_FILES['files']['size'][$key];
                $type = $_FILES['files']['type'][$key];
                $error = $_FILES['files']['error'][$key];

                // File upload path
                $fileName = basename($_FILES['files']['name'][$key]);
                $targetFilePath = $targetDir . $fileName;

                // Check whether file type is valid
                $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
                if (in_array($fileType, $allowTypes)) {
                    // Store files on the server
                    if (move_uploaded_file($_FILES['files']['tmp_name'][$key], $targetFilePath)) {
                        $files_arr[] = $targetFilePath;

                        if (!empty($fileName)) {
                            $sqlData = "INSERT INTO upload_data (user_id,ticket_id, filename, created_at) VALUES ('$user_id','$ticket','$fileName',NOW())";
                            $query_new_insert_data = mysqli_query($con, $sqlData);
                            $log_action->addLine($_SESSION['user_id'], getcwd(), 'TICKET', 'SUBIR ARCHIVO(s)', array("Ticket ID:" . $ticket), 'Archivo cargado correctamente', 5);
                        }
                    } else {
//                            $msg_files = "Hubo un problema al cargar el archivo (" . $fileType . ") seleccionado.";
//                            $errors [] = "Hubo un problema al cargar el archivo (" . $fileType . ") seleccionado.";
                        $log_error->addLine($_SESSION['user_id'], getcwd(), 'TICKET', 'SUBIR ARCHIVO(s)', array("Ticket ID:" . $id), 'Hubo un problema al cargar el archivo (' . $fileType . ') seleccionado.', 1);
                    }
                } else {
//                        $errors [] = "La extención del archivo (" . $fileType . ") no es admitida por el sistema";
                    $log_error->addLine($_SESSION['user_id'], getcwd(), 'TICKET', 'EXTENCION NO ADMITIDA', array("Ticket ID:" . $id), 'Hubo un problema al cargar el archivo (' . $fileType . ') seleccionado.', 1);

                }
            }
        }


        if ($role['id'] == 3 OR $role['id'] == 4 OR $role['id'] == 5) { //Developer - Customers - Super Customer

            //Notify all users of technical support and higher level
            $sql = "SELECT * FROM `users` WHERE `role` IN ('1', '2') AND (deleted_at IS NULL OR deleted_at = '')";
            $query = mysqli_query($con, $sql);

            foreach ($query as $q) {
                //Notifications
                $user_notification = $q['id'];
                $sqlNoti = "INSERT INTO notifications (reference,user_id, type, created_at) VALUES ($ticket,$user_notification,'ticket',$created_at)";
                $query_new_insert_noti = mysqli_query($con, $sqlNoti);
                $log_action->addLine($_SESSION['user_id'], getcwd(), 'TICKET', 'NOTIFICACIONES', array("Ticket ID:" . $ticket, "User Notification: " . $user_notification), '', 5);

            }


        } else {

            //Notify only person asigned
            $user_notification = $asigned_id;
            $sqlNoti = "INSERT INTO notifications (reference,user_id, type, created_at) VALUES ($ticket,$user_notification,'ticket',$created_at)";
            $query_new_insert_noti = mysqli_query($con, $sqlNoti);
            $log_action->addLine($_SESSION['user_id'], getcwd(), 'TICKET', 'NOTIFICACIONES', array("Ticket ID:" . $ticket, "User Notification: " . $user_notification), '', 5);

        }


        //Send Email
        $getDataTicket = $notification->openTicket($ticket);


//        var_dump($getDataTicket);
        //Send SMS
//        $sendSMS = $notification->sendSMS_newTicket($user_name, $user_email,$user_phone);


    } else {
        $log_error->addLine($_SESSION['user_id'], getcwd(), 'TICKET', 'AGREGAR', array("Ticket ID:" . $id), 'Lo siento algo ha salido mal intenta nuevamente =>' . mysqli_error($con), 2);
        $errors [] = "Lo siento algo ha salido mal intenta nuevamente." . mysqli_error($con);
    }
} else {
    $errors [] = "Error desconocido.";
}

if (isset($errors)) {
    echo 0;
}
if (isset($messages)) {

  echo $ticket;
}

?>