<?php
/**
 * Created by PhpStorm.
 * User: Freddy Arvizu
 * Date: 08/07/2019
 * Time: 06:48 PM
 */

session_start();


/*Inicia validacion del lado del servidor*/
if (empty($_POST['msg'])) {
    $errors[] = "Commentario vacío";
} else if (
!empty($_POST['msg'])
) {

    include "../config/config.php";//DB


    $user = mysqli_real_escape_string($con, (strip_tags($_POST["user"], ENT_QUOTES)));
    $role = $permission->get_rol($user);
    $created_at = "NOW()";
    $queryRole = mysqli_query($con, "SELECT role FROM `users` WHERE id = " . $user);
    $r = mysqli_fetch_array($queryRole);


    $queryUser = mysqli_query($con, "SELECT * FROM `users` WHERE id = " . $user);
    $u = mysqli_fetch_array($queryUser);

    $ticket = $_POST["ticket"];
    $comment = $_POST["msg"];
    $ccustomer = $_POST["ccustomer"];


    if ($ccustomer == 1 OR $r['role'] == 4 OR $r['role'] == 5) {
        $view = "ext";
        $notifiCustomer = 1;
    } else {
        $view = "int";
        $notifiCustomer = 0;
    }


    $comment = str_replace('  ', ' ', $comment);
    $imagesComment = $_POST["imagesComment"];
    $imagesComment = substr($imagesComment, 0, -1);
    $images = explode("|", $imagesComment);


    foreach ($images as $img) {
        $imagesLink[] = '<a href="public/attach/' . $ticket . '/' . $img . '" data-fancybox="images">' . $img . '</a>';
    }

    foreach ($imagesLink as $link) {
        foreach ($images as $img) {
            $salida = mysqli_real_escape_string($con, (strip_tags(trim($comment), ENT_QUOTES)));
        }
    }

//    $salida = htmlspecialchars($salida);

    $sql = "INSERT INTO comments (`comment`, ticket_id, user_id,view, created_at) VALUES ('$salida','$ticket','$user','$view',NOW())";
//    var_dump($sql);

    $query_new_insert = mysqli_query($con, $sql);
    if ($query_new_insert) {
        $messages[] = "El commentario ha sido ingresado satisfactoriamente.";

        if ($role['id'] == 3 OR $role['id'] == 4 OR $role['id'] == 5) { //Developer - Customers - Super Customer

            //Notify all users of technical support and higher level
            $sql = "SELECT * FROM `users` WHERE `role` IN ('1', '2') AND (deleted_at IS NULL OR deleted_at = '')";
            $query = mysqli_query($con, $sql);


            foreach ($query as $q) {
                //Notifications
                $user_notification = $q['id'];
                $sqlNoti = "INSERT INTO notifications (reference,user_id, type, created_at) VALUE ($ticket,$user_notification,'comment',$created_at)";

                $query_new_insert_noti = mysqli_query($con, $sqlNoti);
            }


        } else {

            $sql = "SELECT user_id, asigned_id FROM tickets WHERE id=$ticket";
            $query = mysqli_query($con, $sql);

            //Aquí tengo una paradoja
            //Notify only person asigned
            foreach ($query as $q) {
                if ($q['asigned_id'] <> 0 OR $notifiCustomer != 0) {
                    $user_notification = $q['asigned_id'];
                } else {
                    $user_notification = $q['user_id'];
                }
            }
            $sqlNoti = "INSERT INTO notifications (reference,user_id, type, created_at) VALUE ($ticket,$user_notification,'comment',$created_at)";
            $query_new_insert_noti = mysqli_query($con, $sqlNoti);
        }

//        print_r($sqlNoti);


        $cause = "comentario";
        $content = $u['name'] . " ha agregado un nuevo comentario a la conversación.";

        //Get Ticket Data and Send Mail
//        var_dump($ticket. " ,". $content." ,".$notifiCustomer);
        $cause = 'Nuevo comentario ';

        $comment= $salida;

        $getDataTicket = $notification->updTicket($ticket, $cause, $content, $notifiCustomer,$comment);


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

    if ($role['id'] == 4 OR $role['id'] == 5) { //Customer
    $complement = " AND c.`view` ='ext'";
    }
    $sql_comments = "SELECT c.`comment`, c.created_at, u.`name`, u.profile_pic, c.user_id, c.view  FROM `comments` c LEFT JOIN `tickets` t ON c.ticket_id = t.id LEFT JOIN `users` u ON c.user_id = u.id WHERE t.id = " . $ticket . " " . $complement . " ORDER BY c . id  DESC ";
//   var_dump($sql_comments);

    $comments = mysqli_query($con, $sql_comments);
    if (empty($comments)) {
    ?>
    <p>...</p>

    <?php
} else {
    $class = 0;
    foreach ($comments as $comment) {
        $class++;

//        var_dump($user_id);
//        var_dump($comment['user_id']);
        ?>

        <div class="<?php if ($user_id == $comment['user_id']) { ?> chat_list <?php } else { ?> chat_list_right <?php } ?>">
            <div id="add-chat" class="chat_people">
                <div class="<?php if ($user_id == $comment['user_id']) { ?> chat_img <?php } else { ?> chat_img_right <?php } ?>">
                    <img src="images/profiles/<?php echo $comment['profile_pic'] ?>"
                         class="<?php if ($user_id == $comment['user_id']) { ?> circular--square <?php } else { ?> circular--square-right <?php } ?>">
                </div>
                <div class="<?php if ($user_id == $comment['user_id']) { ?> chat_ib <?php } else { ?> chat_ib_right <?php } ?>">
                    <?php if ($user_id == $comment['user_id']) { ?>
                        <h5><?php echo $comment['name'] ?>

                            <?php if ($comment['view'] == 'ext') {
                                if ($role['id'] <= '3') {
                                    ?>
                                    <span class="shared" title="Comentario compartido con el Cliente">
                                    <i class="fa fa-share-square-o" aria-hidden="true"></i>
                                    <i class="fa fa-users" aria-hidden="true"></i>
                                </span>
                                    <?php
                                }
                            }
                            ?>
                            <span class="chat_date"><?php echo $comment['created_at'] ?></span>

                        </h5>
                    <?php } else { ?>

                        <h5>
                            <?php if ($comment['view'] == 'ext') {
                                if ($role['id'] <= '3') {
                                    ?>
                                    <span class="shared" title="Comentario compartido con el Cliente">
                                    <i class="fa fa-share-square-o" aria-hidden="true"></i>
                                    <i class="fa fa-users" aria-hidden="true"></i>
                                </span>
                                    <?php
                                }
                            }
                            ?>

                            <span class="chat_date"><?php echo $comment['created_at'] ?></span>
                            <?php echo $comment['name'] ?>
                        </h5>

                    <?php } ?>
                    <p>

                        <?php echo $comment['comment'] ?>
                    </p>
                </div>


            </div>
        </div>
        <?php
    }
}


}

?>