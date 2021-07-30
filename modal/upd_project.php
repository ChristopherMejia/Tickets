<!-- Modal -->
<div class="modal fade bs-example-modal-lg-udp" id="upd-project" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form class="form-horizontal form-label-left input_mask" method="post" id="upd" name="upd">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel"> Editar Proyecto</h4>
                </div>
                <div class="modal-body">

                    <input type="hidden" id="mod_id" name="mod_id">
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Nombre<span
                                    class="required">*</span></label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                            <input type="text" id="mod_name" required name="mod_name" class="form-control"
                                   placeholder="Nombre">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Descripción <span
                                    class="required">*</span>
                        </label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                            <textarea name="mod_description" id="mod_description"
                                      class="date-picker form-control col-md-7 col-xs-12" required
                                      placeholder="Descripción"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div id="result2"></div>
                    <button id="upd_data" type="submit" class="btn btn-success">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div> <!-- /Modal -->