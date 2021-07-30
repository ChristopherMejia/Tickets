<?php
/**
 * Created by PhpStorm.
 * User: intes_000
 * Date: 16/07/2019
 * Time: 04:13 PM
 */
include "../config/config.php";//DB
$user_id = $_POST['user_id'];


//$view = $permission->permissions_per_page_view($user_id, $page);
//$edit = $permission->permissions_per_page_edit($user_id, $page);
//$delete = $permission->permissions_per_page_delete($user_id, $page);
//$role = $permission->get_rol($user_id);
//
//if (empty($delete['action'])) {
//    echo '<script>$(".action-delete").css("display", "none");</script>';
//}


if (isset($_GET['id'])) {
    $id_del = intval($_GET['id']);
    $query = mysqli_query($con, "SELECT * FROM upload_data WHERE id='" . $id_del . "'");
    $count = mysqli_num_rows($query);

    if ($delete1 = mysqli_query($con, "DELETE FROM upload_data WHERE id='" . $id_del . "'")) {
        ?>
        <div class="alert alert-success alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            <strong>Aviso!</strong> Datos eliminados exitosamente.
        </div>
        <?php
    } else {
        ?>
        <div class="alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            <strong>Error!</strong> Lo siento algo ha salido mal intenta nuevamente.
        </div>
        <?php
    } //end else

    die();
} //end if


$id = $_POST['id_ticket'];
$uploads = mysqli_query($con, "SELECT * FROM upload_data WHERE ticket_id = " . $id . " LIMIT 5");
$uploadsAll = mysqli_query($con, "SELECT * FROM upload_data WHERE ticket_id = " . $id);

if (empty($uploads)) {
    ?>
    <p>Sin archivos...</p>

    <?php
} else {
    $class = 0;

    ?>

    <div class="row imglist">
        <!--    <span class="row">-->
        <!--        <a href="#" data-toggle="modal" data-target="#md-uploads">-->
        <!--            <i class="fa fa-files-o" aria-hidden="true"></i> Administrar archivos-->
        <!--        </a>-->
        <!--    </span>-->
        <?php
        foreach ($uploadsAll as $upload) {

            $typeFile = end(explode(".", $upload['filename']));

            if ($global->esImagen($typeFile) == 'image') {
                ?>
                <div class="col-md-8">
                    <a href="public/attach/<?php echo $id ?>/<?php echo $upload['filename']; ?>"
                       data-fancybox="images"
                       data-caption="<?php echo $upload['created_at']; ?>">
                        <img src="public/attach/<?php echo $id; ?>/<?php echo $upload['filename']; ?>"
                             alt="<?php echo $upload['filename'] ?>" class="img-responsive img-data"/>
                    </a>
                </div>
                <?php

            } elseif ($global->esImagen($typeFile) == 'pdf') {
                ?>
                <div class="col-md-8">
                    <a href="public/attach/<?php echo $id ?>/<?php echo $upload['filename']; ?>" target="_blank">
                        <img src="images/icono-pdf.png" class="img-responsive"
                             alt="<?php echo $upload['filename'] ?>"/>
                    </a>
                </div>

                <?php
            } elseif ($global->esImagen($typeFile) == 'excel') {
                ?>
                <div class="col-md-8">
                    <a href="public/attach/<?php echo $id ?>/<?php echo $upload['filename']; ?>" target="_blank">
                        <img src="images/icono-excel.png" class="img-responsive"
                             alt="<?php echo $upload['filename'] ?>"/>
                    </a>
                </div>

                <?php
            } elseif ($global->esImagen($typeFile) == 'csv') {
                ?>
                <div class="col-md-8">
                    <a href="public/attach/<?php echo $id ?>/<?php echo $upload['filename']; ?>" target="_blank">
                        <img src="images/export-csv.png" class="img-responsive"
                             alt="<?php echo $upload['filename'] ?>"/>
                    </a>
                </div>

                <?php
            } elseif ($global->esImagen($typeFile) == 'doc') {
                ?>
                <div class="col-md-8">
                    <a href="public/attach/<?php echo $id ?>/<?php echo $upload['filename']; ?>" target="_blank">
                        <img src="images/icono-word.png" class="img-responsive"
                             alt="<?php echo $upload['filename'] ?>"/>
                    </a>
                </div>

                <?php
            } elseif ($global->esImagen($typeFile) == 'xml') {
                ?>
                <div class="col-md-8">
                    <a href="public/attach/<?php echo $id ?>/<?php echo $upload['filename']; ?>" target="_blank">
                        <img src="images/xml-icon.png" class="img-responsive"
                             alt="<?php echo $upload['filename'] ?>"/>
                    </a>
                </div>
                <?php
            }elseif ($global->esImagen($typeFile) == 'log') {
                ?>
                <div class="col-md-8">
                    <a href="public/attach/<?php echo $id ?>/<?php echo $upload['filename']; ?>" target="_blank">
                        <img src="images/icono-log.png" class="img-responsive"
                             alt="<?php echo $upload['filename'] ?>"/>
                    </a>
                </div>

                <?php
            }else {
                ?>
                <div class="col-md-8">
                    <a href="public/attach/<?php echo $id ?>/<?php echo $upload['filename']; ?>" target="_blank">
                        <img src="images/attach.jpg" class="img-responsive"
                             alt="<?php echo $upload['filename'] ?>"/>
                    </a>
                </div>

                <?php
            }

            ?>

            <div class="col-md-4 functionsUpload">
                <span onclick="comentar_img('<?php echo $upload['filename']; ?>','<?php echo $id; ?>')"><i
                            class="fa fa-commenting fa-2x" data-toggle="tooltip"
                            title="Agregar Comentario"></i></span><br>
                <?php
                if ($user_id == $upload['user_id']) {
                    $permit = true;
                } else {
                    $permit = false;
                }
                ?>
                <span class="action-delete <?php if (!$permit) { ?>disabled <?php } ?>"
                      onclick="eliminar_img(<?php echo $upload['id']; ?>,<?php echo $permit; ?>)">

                    <i class="fa fa-trash fa-2x" data-toggle="tooltip" title="Eliminar"></i>
                </span>

            </div>
            <?php
        }
        ?>

    </div>


    <?php


}