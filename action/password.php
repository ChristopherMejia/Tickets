<?php
/**
 * Created by PhpStorm.
 * User: Freddy Arvizu
 * Date: 16/10/2019
 * Time: 05:24 PM
 */
include "../config/config.php";//DB

$action = $_POST['action'];
$token = bin2hex(random_bytes(64));

if ($action == 'forgot') {


    $username = $_POST['u'];

    $data = mysqli_query($con, $query = "SELECT * FROM users WHERE username='$username' LIMIT 1");
    if ($row = mysqli_fetch_array($data)) {

        $fullname = $row['name'];
        $email = $row['email'];
        $created_at = 'NOW()';

        if (isset($email)) {
            $notification->sendMail_forgotPass($username, $token, $fullname, $email);
            $dataRe = mysqli_query($con, $query = "INSERT INTO recover_password (username, token, status, created_at) VALUES ('$username','$token',0,$created_at)");
            ?>
            <div class="alert alert-success alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <strong>Aviso!</strong> Un correo fue enviado a: <br><?php echo substr($email, 0, 4); ?>
                ******<?php echo substr($email, -10); ?>
            </div>
            <?php
            die();
        } else {
            ?>
            <div class="alert alert-danger alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <strong>Aviso!</strong> No se puede recuperar esta cuenta, contacte al administrador.
            </div>

            <?php
        }
    } else {
        ?>
        <div class="alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            <strong>Aviso!</strong> El usuario es incorrecto
        </div>
        <?php
    }


} elseif ($action == 'reset') {


    $pass1 = $_POST['p1'];
    $pass2 = $_POST['p2'];
    $tokenu = $_POST['t'];


    if ($pass1 <> $pass2) {

        ?>
        <div class="alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            <strong>Aviso!</strong> Las contraseñas no coinciden
        </div>
        <?php
        die();
    }


    $dataR = mysqli_query($con, $queryR = "SELECT * FROM recover_password WHERE token='$tokenu' AND  `status` = 0 LIMIT 1");
    if ($rowR = mysqli_fetch_array($dataR)) {

        $username = $rowR['username'];
        $created_at = $rowR['created_at'];
        $status = $rowR['status'];


        $date_now = date("d-m-Y H:i:s");

//        echo "created_at: " . strtotime($created_at);
//        echo "date_now: " . strtotime($date_now);


        if (strtotime($created_at) > strtotime($date_now . "+ 1 hour")) {

            ?>
            <div class="alert alert-danger alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <strong>Aviso!</strong> El enlace al que intentas acceder ha caducado
            </div>
            <?php
            die();
        }

        echo $diff = date_diff($created_at, $date_now);

        if ($status == 1) {
            ?>
            <div class="alert alert-danger alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <strong>Aviso!</strong> El enlace al que intentas acceder ya fue utilizado
            </div>
            <?php
            die();
        }

    }


    $data = mysqli_query($con, $query = "SELECT * FROM users WHERE username='$username' LIMIT 1");


    if ($row = mysqli_fetch_array($data)) {

        $fullname = $row['name'];
        $email = $row['email'];


        if (isset($email)) {

            $password = strip_tags(sha1(md5($pass1)));
            $update_passwd = mysqli_query($con, $qu = "UPDATE users SET password=\"$password\" WHERE username=\"$username\"");


            $dataRe = mysqli_query($con, $qur = "UPDATE recover_password SET status=1 WHERE token=\"$tokenu\"");

            $notification->sendMail_resetPass($fullname, $email);
            ?>
            <div class="alert alert-success alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <strong>Aviso!</strong> Tu contraseña fue actualizada
            </div>
            <?php
            die();
        } else {
            ?>
            <div class="alert alert-danger alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <strong>Aviso!</strong> No se puede recuperar esta cuenta, contacte al administrador.
            </div>

            <?php
        }
    } else {
        ?>
        <div class="alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            <strong>Aviso!</strong> El usuario es incorrecto
        </div>
        <?php
    }


}