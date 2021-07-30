<?php
/**
 * Created by PhpStorm.
 * User: intes_000
 * Date: 26/07/2019
 * Time: 05:04 PM
 */

?>

<div> <!-- Agregar Ticket -->
    <a data-toggle="modal" data-target=".bs-example-modal-lg-add" class="btn-floating"
       id="a-add-note" title="Agregar Nota">
        <i class="fa fa-plus" aria-hidden="true"></i>
    </a>
</div>
<div class="modal fade bs-example-modal-lg-add" id="add-ticket" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Agregar Nota</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal form-label-left input_mask" method="post" id="frm_note" name="frm_note">
                    <div class="form-group">
                        <label class="control-label">Titulo<span
                                    class="required">*</span></label>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <input type="text" name="title" class="form-control" placeholder="Titulo">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label">
                            Descripción
                            <span class="required">*</span>
                        </label>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <textarea name="description" class="text-custom form-control col-md-7 col-xs-12"
                                      placeholder="Añadir una descripción más detallada..."></textarea>
                        </div>
                    </div>

                    <div class="ln_solid"></div>
                    <div class="form-group">
                        <div class="col-md-12">
                            <input type="hidden" id="note_user" name="user"
                                   value="<?php echo $user_id ?>"/>
                            <input type="hidden" id="note_ticket"
                                   value="<?php echo $idTicket ?>" name="ticket"/>
                            <button id="save_note" type="submit" class="btn btn-lg btn-block btn-success">Guardar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <div id="result_note" class="response_result"></div>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div> <!-- /Modal -->
