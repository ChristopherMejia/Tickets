<?php
/**
 * Created by PhpStorm.
 * User: Freddy Arvizu
 * Date: 08/08/2019
 * Time: 01:55 PM
 */

include('../../config/config.php');

$count = 0;
if (isset($_POST['view'])) {

    $user = $_POST['user'];

    $output = '';
    $notifBellList = $alert->listNotificationUserBell($user);
    $role = $global->getRol($user);
    $manager = false;

    if ($role['id'] <= 2) {
        $manager = true;
    }

//    var_dump($notifBellList);


    foreach ($notifBellList as $as) {

        $style = "";
        if ($as["priority_name"] == "Alta") {
            $style = "background: red";
        }

        if ($as['status_id'] == 3 OR $as['status_id'] == 4) {

            $text = 'Tu ticker fue <span class="badge badge-' . $as['badge'] . '">' . $as['status'] . '</span>';
        } else {
            $text = 'Tienes un nuevo Ticket:';
        }

        if ($as['user_assigned'] == $user OR $manager == true) {

            if (!empty($as["order_number"])) {
                $output .= '
                           <li class="des-not">
                               <a href="ticket-detail?order=' . $as["order_number"] . '">
                                   <span>' . $text . '<br>
                                    <b>' . $as["user_created"] . ' / <small >' . $as["company"] . '</small></b>
                               
                                    </span>
                                    <br />
                                   <hr>
                                   <span>
                                   Prioridad: <span class="badge badge-' . $as["priority_badge"] . '" style="' . $style . '">' . $as["priority_name"] . '</span><br />
                                   Folio: <strong>#' . $as["order_number"] . '</strong><br />
                                   <small><em>' . substr($as["title"], 0, 35) . '... <b>[ver más]</b></em></small>
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
                     <li><a href="#" class="text-bold text-italic">Sin nuevas notificaciones</a></li>
                   ';
    }


    $data = array(

        'notification' => $output,
        'unseen_notification' => $count
    );

    echo json_encode($data);

}


?>
