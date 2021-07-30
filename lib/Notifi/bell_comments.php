<?php
/**
 * Created by PhpStorm.
 * User: Freddy Arvizu
 * Date: 02/10/2019
 * Time: 02:09 PM
 */

include('../../config/config.php');


if (isset($_POST['view'])) {

    $user = $_POST['user'];
    $output = '';
    $count = 0;
    $notifBellList = $alert->listNotificationUserMsgBell($user);

//    $sql = "SELECT * FROM comments WHERE ticket_id = 19 ORDER BY id DESC LIMIT 1";


//    var_dump($notifBellList);
    $role = $global->getRol($user);
    $manager = false;

    if ($role['id'] <= 2) {
        $manager = true;
    }


    foreach ($notifBellList as $as) {

        if ($as['user_assigned'] == $user OR $manager == true) {

            if (!empty($as["order_number"])) {
                $output .= '
                           <li class="des-not">
                               <a href="ticket-detail?order=' . $as["order_number"] . '">
                                    Tienes un nuevo <b>Comentario</b></span><br />
                                   <hr>
                                   <span>
                                    Ticket: <strong>#' . $as["order_number"] . '</strong><br />
                                   <small><em>' . $as["comment"] . '</em></small>
                                   </span>
                               </a>
                           </li>
                           ';

                $count++;
            }
        }
    }

    if ($count == 0 OR empty($count) OR $count == NULL) {
        $output .= '
                     <li><a href="#" class="text-bold text-italic">Sin nuevos comentarios</a></li>
                   ';
    }

    $data = array(
        'notification' => $output,
        'unseen_notification_msg' => $count
//        'unseen_notification_msg' => $countNumber
    );

    echo json_encode($data);

}

?>