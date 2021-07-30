<?php
/**
 * Created by PhpStorm.
 * User: Freddy Arvizu
 * Date: 25/07/2019
 * Time: 06:44 PM
 */
?>
    <div id="md-uploads" class="modal fade drag" role="dialog">
        <div class="modal-dialog modal-lg">

            <!-- Modal content-->
            <div class="modal-content b-gray">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Archivos Adjuntos</h4>
                </div>
                <div class="modal-body">

                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Fecha</th>
                            <th>Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach ($uploads as $upload) {
                            ?>
                            <tr>
                                <td>
                                    <a href="public/attach/<?php echo $idTicket ?>/<?php echo $upload['filename']; ?>"
                                       data-fancybox="images"
                                       data-caption="<?php echo $upload['created_at']; ?>">
                                        <img src="public/attach/<?php echo $idTicket; ?>/<?php echo $upload['filename']; ?>"
                                             alt="<?php echo $upload['filename'] ?>" class="img-responsive img-data"/>
                                    </a>
                                </td>
                                <td><?php echo $upload['filename']; ?></td>
                                <td><?php echo $upload['created_at']; ?></td>
                                <td>
                                    <a href="#" class="btn btn-default action-delete" alt="Eliminar"
                                       onclick="eliminar_img('<?php echo $id; ?>')">
                                        <i class="fa fa-trash fa-2x" aria-hidden="true"></i>
                                    </a>

                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                        </tbody>
                    </table>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
