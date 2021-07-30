<?php
/**
 * Created by PhpStorm.
 * User: Freddy Arvizu
 * Date: 10/01/2020
 * Time: 01:38 PM
 */


session_start();
/*Inicia validacion del lado del servidor*/

if (empty($_POST['name'])) {
    $errors[] = "Nombre vacío";
} else if (empty($_POST['module_f'])) {
    $errors[] = "Modulo Vacío";
} else if (
    !empty($_POST['name']) &&
    !empty($_POST['module_f'])
) {

    include "../config/config.php";//DB

    // escaping, additionally removing everything that could be (html/javascript-) code
    $name = mysqli_real_escape_string($con, (strip_tags($_POST["name"], ENT_QUOTES)));
    $module_f = mysqli_real_escape_string($con, (strip_tags($_POST["module_f"], ENT_QUOTES)));
    $created_at = date("Y-m-d H:i:s");

    $sql = "INSERT INTO fades (module_id, name, created_at) VALUES ($module_f,'$name','$created_at')";

//        var_dump($sql);
    $query_new_insert = mysqli_query($con, $sql);

    if ($query_new_insert) {
        $messages[] = "El Fade ha sido ingresado satisfactoriamente.";

    } else {
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
