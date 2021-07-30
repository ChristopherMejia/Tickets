<?php
/**
 * Created by PhpStorm.
 * User: Freddy Arvizu
 * Date: 10/01/2020
 * Time: 11:42 AM
 */

?>
<div> <!-- Agregar Modulo -->
    <a data-toggle="modal" data-target=".bs-example-modal-lg-add" class="btn-floating"
       id="a-add-ticket" title="Agregar modulo">
        <i class="fa fa-plus" aria-hidden="true"></i>
    </a>
</div>
<div class="modal fade bs-example-modal-lg-add" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form class="form-horizontal form-label-left input_mask" id="add_module">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
                    <h4 class="modal-title" id="myModalLabel">Agregar Modulo</h4>
                </div>
                <div class="modal-body">


                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback">
                            <label for="rol">Alias</label>
                            <input name="alias" required type="text" class="form-control" placeholder="ejemp: com">

                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback">
                            <label for="rol">Nombre</label>
                            <input id="name" name="name" type="text" class="form-control" placeholder="ejemp: Compras">

                        </div>
                    </div>


                </div>
                <div class="modal-footer">
                    <div id="result_module"></div>
                    <button id="save_data" type="submit" class="btn btn-success">Guardar</button>
                </div>

            </form>
        </div>
    </div>
</div> <!-- /Modal -->