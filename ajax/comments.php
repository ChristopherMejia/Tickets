<?php
/**
 * Created by PhpStorm.
 * User: Freddy Arvizu
 * Date: 16/07/2019
 * Time: 04:13 PM
 */
include "../config/config.php";//DB

$id = $_POST['id_ticket'];
$user_id = $_POST['user_id'];
$role = $global->getRol($user_id);


if ($role['id'] == 4 OR $role['id'] == 5) { //Customer
    $complement = " AND c.`view` ='ext'";
}
$sql_comments = "SELECT c.`comment`, c.created_at, u.`name`, u.profile_pic, c.user_id, c.view  FROM `comments` c LEFT JOIN `tickets` t ON c.ticket_id = t.id LEFT JOIN `users` u ON c.user_id = u.id WHERE t.id = " . $id . " " . $complement . " ORDER BY c . id  DESC ";
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