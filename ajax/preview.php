<?php
/**
 * Created by PhpStorm.
 * User: Freddy Arvizu
 * Date: 17/01/2020
 * Time: 06:39 PM
 */

include "../config/config.php";//DB
$user_id = $_POST['user_id'];


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

    <div class="row preview-list">
        <!--    <span class="row">-->
        <!--        <a href="#" data-toggle="modal" data-target="#md-uploads">-->
        <!--            <i class="fa fa-files-o" aria-hidden="true"></i> Administrar archivos-->
        <!--        </a>-->
        <!--    </span>-->
        <?php
        foreach ($uploads as $upload) {

            $typeFile = end(explode(".", $upload['filename']));

            ?>
            <div class="cont-img-prev">
                <?php
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
                } elseif ($global->esImagen($typeFile) == 'log') {
                    ?>
                    <div class="col-md-8">
                        <a href="public/attach/<?php echo $id ?>/<?php echo $upload['filename']; ?>" target="_blank">
                            <img src="images/icono-log.png" class="img-responsive"
                                 alt="<?php echo $upload['filename'] ?>"/>
                        </a>
                    </div>

                    <?php
                } else {
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

                    <?php
                    if ($user_id == $upload['user_id']) {
                        $permit = true;
                    } else {
                        $permit = false;
                    }
                    ?>
                    <span class="action-delete <?php if (!$permit) { ?>disabled <?php } ?>"
                          onclick="eliminar_img_preview(<?php echo $upload['id']; ?>,<?php echo $permit; ?>)">

                    <i class="fa fa-trash fa-2x" data-toggle="tooltip" title="Eliminar"></i>
                </span>

                </div>
            </div>
            <?php
        }
        ?>

    </div>


    <?php


}