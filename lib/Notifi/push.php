<?php

include('../../config/config.php');

$user = $_POST['user'];
$array = array();
$rows = array();
$notifList = $alert->listNotificationUserPush($user);
$role = $global->getRol($user);
$manager = false;

if ($role['id'] <= 2) {
    $manager = true;
}
//

$record = 0;

foreach ($notifList as $as) {


    if ($as['user_assigned'] == $user OR $manager == true) {

            $data['name'] = $as['user_created'] . ' creo un nuevo ticket';
            $data['title'] = $as['title'];
            $data['msg'] = substr(strip_tags($as['company']), 0, 23);
            $data['icon'] = 'http://soporte.intesystem.net/images/profiles/' . $as['profile_pic'];
            $data['url'] = 'http://soporte.intesystem.net/ticket-detail?order=' . $as['order_number'];
            $rows[] = $data;


            $alert->updateNotificationPush($as['ticket_id'], $user);
            $record++;


            $array['notif'] = $rows;
            $array['count'] = $record;
            $array['result'] = true;
            echo json_encode($array);

    }
}


?>