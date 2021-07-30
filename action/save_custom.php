<?php
/**
 * Created by PhpStorm.
 * User: Freddy Arvizu
 * Date: 27/11/2019
 * Time: 02:42 PM
 */

session_start();
/*Inicia validacion del lado del servidor*/


include "../config/config.php";//DB

// escaping, additionally removing everything that could be (html/javascript-) code
$theme = $_POST["groupTheme"];
$user_id = $_POST["user_id"];


$sql = "REPLACE INTO custom (user_id, theme, updated_at) VALUES('$user_id','$theme', NOW())";
//var_dump($sql);
$query_upd_conf = mysqli_query($con, $sql);
if ($query_upd_conf) {
    $messages[] = "La nueva configuración fue guardad con éxito";
} else {
    $errors[] = "Hubo un problema al guardar la configuración";
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