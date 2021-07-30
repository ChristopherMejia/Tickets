<?php
/**
 * Created by PhpStorm.
 * User: Freddy Arvizu
 * Date: 19/11/2019
 * Time: 02:55 PM
 */

session_start();
/*Inicia validacion del lado del servidor*/


include "../config/config.php";//DB

$active = $_POST["active"];
$notices = $_POST["notices"];


if ($active != null) {


    $sql = "UPDATE `notices` SET active=$active WHERE id = 1";
//    var_dump($sql);
    $query_upd_conf = mysqli_query($con, $sql);
    if ($query_upd_conf) {

        if ($active == 1) {
            $messages[] = "El aviso fue publicado con éxito";
        } else {
            $messages[] = "El aviso fue desactivado con éxito";
        }
    } else {
        $errors[] = "Hubo un problema al guardar la configuración";
    }


} else if ($notices != null) {

    $title = $_POST["title"];
    $error = $_POST["error"];
    $sdescription = $_POST["sdescription"];
    $description = $_POST["description"];
    $solution = $_POST["solution"];


    $sql = "UPDATE `notices` SET title='$title', error='$error', short_description='$sdescription', description='$description', solution='$solution', created_at=NOW() WHERE id = 1";
//    var_dump($sql);
    $query_upd_conf = mysqli_query($con, $sql);
    if ($query_upd_conf) {
        $messages[] = "La nueva configuración fue guardad con éxito";
    } else {
        $errors[] = "Hubo un problema al guardar la configuración";
    }

} else {


// escaping, additionally removing everything that could be (html/javascript-) code
    $maintenance = $_POST["groupMaint"];


    $sql = "UPDATE configuration set maintenance=$maintenance, updated_at =NOW()";
//    var_dump($sql);
    $query_upd_conf = mysqli_query($con, $sql);
    if ($query_upd_conf) {
        $messages[] = "La nueva configuración fue guardada con éxito";
    } else {
        $errors[] = "Hubo un problema al actualizar el estado";
    }


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