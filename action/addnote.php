<?php
/**
 * Created by PhpStorm.
 * User: Freddy Arvizu
 * Date: 26/07/2019
 * Time: 05:15 PM
 */


session_start();
include "../config/config.php";//DB


/*Inicia validacion del lado del servidor*/
if (empty($_POST['title'])) {
    $errors[] = "Titulo vacío";
} else if (empty($_POST['description'])) {
    $errors[] = "Description vacío";
} else if (
    !empty($_POST['title']) &&
    !empty($_POST['description'])

) {


    $title = $_POST["title"];
    $description = $_POST["description"];
    $user_id = $_POST["user"];
    $ticket_id = $_POST["ticket"];
    $created_at = "NOW()";


    $sql = "INSERT INTO notes (title,note,user_id,ticket_id,created_at) VALUE (\"$title\",\"$description\",\"$user_id\",\"$ticket_id\",$created_at)";
    $query_new_insert = mysqli_query($con, $sql);
    $ticket = mysqli_insert_id($con);


    if ($query_new_insert) {
        $messages[] = "Tu nota ha sido ingresado satisfactoriamente.";


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