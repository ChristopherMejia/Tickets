<?php
/**
 * Created by PhpStorm.
 * User: Freddy Arvizu
 * Date: 10/01/2020
 * Time: 12:59 PM
 */

$modules = mysqli_query($con, "SELECT * FROM modules ORDER BY name ASC");

$modules_sel = mysqli_query($con, "SELECT * FROM modules WHERE name='" . $_GET['n']."' ORDER BY name ASC");
if ($row_module = mysqli_fetch_array($modules_sel)) {

    $id_module_get = $row_module['id'];
}
?>
<div> <!-- Agregar Fade -->
    <a data-toggle="modal" data-target="#new_fade_modal" class="btn-floating"
       id="a-add-ticket" title="Agregar Fade">
        <i class="fa fa-plus" aria-hidden="true"></i>
    </a>
</div>
<div class="modal fade" id="new_fade_modal" tabindex="-1" role="dialog" aria-hidden="true" style="z-index: 9999;">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form class="form-horizontal form-label-left input_mask" id="add_fade">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
                    <h4 class="modal-title" id="myModalLabel">Agregar Fade</h4>
                </div>
                <div class="modal-body">

                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback">
                            <label for="rol">Modulo</label>
                            <select class="form-control border kinds border-warning" name="module_f"
                                    id="mod_module_f">
                                <option value="" selected disabled>-[Seleccione una opción]-</option>
                                <?php foreach ($modules as $m) { ?>
                                    <option value="<?php echo $m['id']; ?>"
                                        <?php if ($m['id'] == $id_module_get) { ?> selected <?php } ?>
                                    ><?php echo $m['name']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback">
                            <label for="rol">Nombre</label>
                            <input id="name" name="name" type="text" class="form-control" placeholder="ejemp: AuditoriaPagos">
                        </div>
                    </div>


                </div>
                <div class="modal-footer">
                    <div id="result_fade"></div>
                    <button id="save_data" type="submit" class="btn btn-success">Guardar</button>
                </div>

            </form>
        </div>
    </div>
</div> <!-- /Modal -->