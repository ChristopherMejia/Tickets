<?php


session_start();

if (empty($_POST['name'])) {
    $errors[] = "Nombre vacío";
} else if (empty($_POST['email'])) {
    $errors[] = "Correo Vacio vacío";
} else if ($_POST['status'] == "") {
    $errors[] = "Selecciona el estatus";
} else if (
    !empty($_POST['name']) &&
    !empty($_POST['email']) &&
    $_POST['status'] != ""
) {

    include "../config/config.php";//DB


    $rfc = mysqli_real_escape_string($con, (strip_tags($_POST["rfc"], ENT_QUOTES)));

    /* search RFC*/
    $sqlRFC = "SELECT * FROM `companies` WHERE RFC = '$rfc'";
    $query_search_rfc = mysqli_query($con, $sqlRFC);

    foreach ($query_search_rfc as $qsr) {
        $company_id = $qsr['id'];
        $company_name = $qsr['name'];
    }


    if (empty($company_id)) {
        $errors[] = "RFC no valido";
    } else {

        $id = $_POST['mod_id'];
        $name = mysqli_real_escape_string($con, (strip_tags($_POST["name"], ENT_QUOTES)));
        $email = $_POST["email"];
        $status = intval($_POST["status"]);
        $rol = intval($_POST["rol"]);
        $dpto = intval($_POST["dpto"]);
        $password = mysqli_real_escape_string($con, (strip_tags(sha1(md5($_POST["password"])), ENT_QUOTES)));

        $sql = "UPDATE users SET name=\"$name\", email=\"$email\",is_active=$status,role=$rol,company_id=$company_id  WHERE id=$id";
        $query_update = mysqli_query($con, $sql);
        if ($query_update) {
            $messages[] = "Datos actualizados satisfactoriamente.";

            //update departments
            $sqlR = "UPDATE users_has_departments SET department_id= $dpto WHERE user_id=$id";
            $query_new_updatedR = mysqli_query($con, $sqlR);

            // update password
            if ($_POST["password"] != "") {
                $update_passwd = mysqli_query($con, "update users set password=\"$password\" where id=$id");
                if ($update_passwd) {
                    $messages[] = " Y la Contraseña ah sido actualizada.";
                }
            }

        } else {
            $errors [] = "Lo siento algo ha salido mal intenta nuevamente." . mysqli_error($con);
        }
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