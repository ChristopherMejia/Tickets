<?php
/**
 * Created by PhpStorm.
 * User: Freddy Arvizu
 * Date: 30/01/2020
 * Time: 11:04 AM
 */


session_start();

include "../config/config.php";//DB

$notify = $_GET['notify'];


if ($notify == 'yes') {


    $queryNotices = "SELECT * FROM notices WHERE id=1";
    $sqlNotices = mysqli_query($con, $queryNotices);
    $rowNotices = mysqli_fetch_assoc($sqlNotices);


    if ($rowNotices['active'] == 1) {
//        $notify = "Estamos experimentando problemas que afectan las operaciones de nuestro ERP, estamos trabajando para solucionarlo a la brevedad.";
        $notify = "Tenemos un aviso muy importante para ti.";
    } else {
        $notify = "El o los problemas han sido resueltos, puede continuar con su operación normal, gracias por su comprensión";
    }

    $sqlEmail = "SELECT name, email FROM `users` WHERE role >=4 AND is_active=1  AND (deleted_at IS NULL OR deleted_at = '')";
    $query_search_email = mysqli_query($con, $sqlEmail);


    foreach ($query_search_email as $e) {

         $sendMailNotices = $notification->sendMail_notices($notify, $e['name'], $e['email'], $rowNotices['title'], $rowNotices['error'], $rowNotices['description'], $rowNotices['solution']);

    }
}

?>


