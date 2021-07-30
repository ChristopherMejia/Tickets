<div class="modal fade bs-example-modal-lg-upd" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form class="form-horizontal form-label-left input_mask" id="upd_user" name="upd_user">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel">Editar Usuario</h4>
                </div>
                <div class="modal-body">

                    <input type="hidden" id="mod_id" name="mod_id">


                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback">
                            <input name="name" id="mod_name" required type="text" class="form-control"
                                   placeholder="Nombre">
                            <span class="fa fa-user form-control-feedback right" aria-hidden="true"></span>
                        </div>

                        <div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback">
                            <input name="email" id="mod_email" type="text" class="form-control"
                                   placeholder="Correo Electronico" required>
                            <span class="fa fa-envelope form-control-feedback right" aria-hidden="true"></span>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback">
                            <select class="form-control" required name="status" id="mod_status">
                                <option value="" disabled>-- Selecciona estado --</option>
                                <option value="1" selected>Activo</option>
                                <option value="0">Inactivo</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback">
                            <label for="rfc">RFC</label>
                            <input name="rfc" id="mod_rfc" type="text" class="form-control has-feedback-left rfc"
                                   placeholder="AAA10101AAA" maxlength="13" required>
                            <span class="fa fa-text-width form-control-feedback right" aria-hidden="true"></span>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback">
                            <label for="rol">Rol</label>
                            <select class="form-control" required name="rol" id="mod_rol">
                                <option value="" selected disabled>-- Selecciona su rol --</option>
                                <?php foreach ($roles as $r) { ?>
                                    <option value="<?php echo $r['id'] ?>"><?php echo $r['name'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback">
                            <label for="rol">Departamento</label>
                            <select class="form-control" name="dpto" id="mod_dpto" required>
                                <option value="" selected disabled>-- Selecciona su rol --</option>
                                <?php foreach ($dptos as $d) { ?>
                                    <option value="<?php echo $d['id'] ?>"><?php echo $d['name'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>


                    <div class="row">
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="password">Contraseña<span
                                        class="required">*</span>
                            </label>
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <input type="password" id="mod_password" name="password"
                                       class="form-control col-md-7 col-xs-12">
                                <p class="text-muted">La contraseña solo se modificara si escribes algo, en caso
                                    contrario no se modifica.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div id="upd_result_user"></div>
                    <button id="upd_data" type="submit" class="btn btn-success">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div> <!-- /Modal -->