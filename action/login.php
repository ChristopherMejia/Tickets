<?php


// initialize session variables

// $ttl = strtotime('+1 day') - time(); # otra forma de hacerlo
//$ttl = 60 * 60 * 24;
//session_set_cookie_params($ttl); # set del tiempo de vida

session_start([
    'cookie_lifetime' => 86400
]);
if (isset($_POST['token']) && $_POST['token'] !== '') {

    //Contiene las variables de configuracion para conectar a la base de datos
    include "../config/config.php";

    $username = mysqli_real_escape_string($con, (strip_tags($_POST["username"], ENT_QUOTES)));
    $password = sha1(md5(mysqli_real_escape_string($con, (strip_tags($_POST["password"], ENT_QUOTES)))));

    $sql = mysqli_query($con, $query = "SELECT * FROM users WHERE  username='$username' AND password = '$password' AND is_active = 1 AND  (deleted_at IS NULL OR deleted_at = '')");


    //    die($query);
    if ($row = mysqli_fetch_array($sql)) {
        $_SESSION['user_id'] = $row['id'];
        $token = bin2hex(random_bytes(64));
        $_SESSION['token'] = $token;
        $sqlupd = mysqli_query($con, $queryUpd = "UPDATE users SET token='$token' WHERE username='$username'");
        $log_action->addLine($username, getcwd(), 'LOGIN', 'Access', array("Usuario:" . $username), '', 5);


//        $_SESSION['logged_in_user_id'] = $row['id'];
//        $_SESSION['logged_in_user_name'] = $username;

        header("location: ../dashboard");

    } else {
        $invalid = sha1(md5("contrasena y/o usuario invalido"));
        $log_error->addLine($username, getcwd(), 'LOGIN', 'Access', array("Usuario:" . $username), 'Contrasena y / o usuario invalido', 5);
        header("location: ../login?invalid=$invalid");
    }
} else {
    header("location: ../");
}

?>