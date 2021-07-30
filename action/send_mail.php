<?php
/**
 * Created by PhpStorm.
 * User: Freddy Arvizu
 * Date: 19/09/2019
 * Time: 04:47 PM
 */

include "../config/config.php";//DB


$msg = $_GET['msg'];
$user_id = $_GET['user_id'];
$ticket_id = $_GET['ticket_id'];

$result = $notification->closeTicket($msg, $ticket_id);

$view = "ext";
$sqlComment = "INSERT INTO comments (comment, ticket_id, user_id,view, created_at) VALUES ('$msg','$ticket_id','$user_id','$view',NOW())";
$query_new_insert_comment = mysqli_query($con, $sqlComment);


