<?php
//Contiene las variables de configuracion para conectar a la base de datos
include "../config/config.php";

session_start();


if (isset($_SESSION['user_id']) OR empty($_SESSION['user_id'])) {

    //Destroy token
    $user_id = $_SESSION['user_id'];
    $sqlupd = mysqli_query($con, $queryUpd = "UPDATE users SET token=Null WHERE id=" . $user_id);

    $sqlupdPresence = mysqli_query($con, $queryUpdPresence = "REPLACE INTO `users_presence` (user_id, page) VALUES($user_id,'');");

    session_destroy();
    header("location: ../login"); //estemos donde estemos nos redirije al login
}
?>