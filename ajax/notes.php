<?php
/**
 * Created by PhpStorm.
 * User: Freddy Arvizu
 * Date: 30/07/2019
 * Time: 05:19 PM
 */

include "../config/config.php";//DB


if (isset($_GET['id'])) {
    $id_del = intval($_GET['id']);
    $query = mysqli_query($con, "SELECT * FROM notes WHERE id='" . $id_del . "'");
    $count = mysqli_num_rows($query);

    if ($delete1 = mysqli_query($con, "DELETE FROM notes WHERE id='" . $id_del . "'")) {
        ?>
        <div class="alert alert-success alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            <strong>Aviso!</strong> Datos eliminados exitosamente.
        </div>
        <?php
        die();
    } else {
        ?>
        <div class="alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            <strong>Error!</strong> Lo siento algo ha salido mal intenta nuevamente.
        </div>
        <?php
        die();
    } //end else
} //end if


$id = $_POST['id_ticket'];
$user_id = $_POST['user_id'];
$query_notes = "SELECT n.id, n.`title`, n.note  FROM `notes` n LEFT JOIN `tickets` t ON n.ticket_id = t.id LEFT JOIN `users` u ON n.user_id = u.id WHERE t.id = " . $id . " AND n.user_id=" . $user_id . " ORDER BY n.id  DESC ";
$notes = mysqli_query($con, $query_notes);

?>
    <div id="notes">
    <div id="resultados_notas"></div>
    <ul>
<?php

if (empty($notes)) {
    ?>
    <ul>
        <li>...</li>
    </ul>

    <?php
} else {

    foreach ($notes as $note) {

        ?>
        <li id="note-<?php echo $note['id'] ?>">
            <a href="#">
                <span class="close-note"
                      onclick="eliminar_nota('<?php echo $note['id'] ?>','<?php echo $note['title'] ?>')"><i
                            class="fa fa-times" aria-hidden="true"></i></span>
                <h2><?php echo $note['title'] ?></h2>
                <?php echo $note['note'] ?>
            </a>
        </li>

        <?php
    }
    ?>
    </ul>
    </div>
    <?php
}