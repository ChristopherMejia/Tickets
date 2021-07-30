<?php
session_start();
/*Inicia validacion del lado del servidor*/

if (empty($_POST['name'])) {
    $errors[] = "Nombre vacío";
} else if (empty($_POST['lastname'])) {
    $errors[] = "Apellidos vacío";
} else if (empty($_POST['email'])) {
    $errors[] = "Correo Vacio vacío";
} else if ($_POST['status'] == "") {
    $errors[] = "Selecciona el estado";
} else if (empty($_POST['password'])) {
    $errors[] = "Contraseña vacío";
} else if (
    !empty($_POST['name']) &&
    !empty($_POST['lastname']) &&
    $_POST['status'] != "" &&
    !empty($_POST['password'])
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


        // escaping, additionally removing everything that could be (html/javascript-) code
        $name = mysqli_real_escape_string($con, (strip_tags($_POST["name"], ENT_QUOTES)));
        $username = mysqli_real_escape_string($con, (strip_tags($_POST["username"], ENT_QUOTES)));
        $lastname = mysqli_real_escape_string($con, (strip_tags($_POST["lastname"], ENT_QUOTES)));
        $email = $_POST["email"];
        $password = mysqli_real_escape_string($con, (strip_tags(sha1(md5($_POST["password"])), ENT_QUOTES)));
        $status = intval($_POST['status']);
        $end_name = $name . " " . $lastname;
        $created_at = date("Y-m-d H:i:s");
        $user_id = $_SESSION['user_id'];
        $profile_pic = "default.png";


        $rol = mysqli_real_escape_string($con, (strip_tags($_POST["rol"], ENT_QUOTES)));
        $dpto_id = mysqli_real_escape_string($con, (strip_tags($_POST["dpto"], ENT_QUOTES)));


        $is_admin = 0;
        if (isset($_POST["is_admin"])) {
            $is_admin = 1;
        }


        $sql = "INSERT INTO users (company_id, name, username, password, email, profile_pic, is_active, role, created_at) VALUES ($company_id, '$end_name','$username','$password','$email','$profile_pic',$status,$rol,'$created_at')";

//        var_dump($sql);
        $query_new_insert = mysqli_query($con, $sql);

        if ($query_new_insert) {
            $last_id = mysqli_insert_id($con);

            $sqlR = "INSERT INTO users_has_departments (user_id, department_id) VALUES ($last_id, '$dpto_id')";
            $query_new_insertR = mysqli_query($con, $sqlR);

            $messages[] = "El usuario ha sido ingresado satisfactoriamente.";

            $sendMailUser = $notification->sendMail_newUser($name . ' ' . $lastname, $email, $_POST["password"], $username, $company_name);

        } else {
            $errors [] = "Lo siento algo ha salido mal intenta nuevamente." . mysqli_error($con);

            //Aquí va la validación para usuario repetido if mysqli_error($con) == 1062 algo así
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