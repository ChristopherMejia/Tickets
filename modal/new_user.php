<?php
$roles = $global->getRoles();
$dptos = $global->getDptos()
?>
<div> <!-- Agregar Usuario -->
    <a data-toggle="modal" data-target=".bs-example-modal-lg-add" class="btn-floating"
       id="a-add-ticket" title="Agregar usuario">
        <i class="fa fa-plus" aria-hidden="true"></i>
    </a>
</div>
<div class="modal fade bs-example-modal-lg-add" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form class="form-horizontal form-label-left input_mask" id="add_user">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
                    <h4 class="modal-title" id="myModalLabel">Agregar Usuario</h4>
                </div>
                <div class="modal-body">

                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback">
                            <input name="name" required type="text" class="form-control" placeholder="Nombre">
                            <span class="fa fa-user form-control-feedback right" aria-hidden="true"></span>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback">
                            <input name="lastname" type="text" class="form-control" placeholder="Apellidos" required>
                            <span class="fa fa-user form-control-feedback right" aria-hidden="true"></span>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback">
                            <input name="email" type="text" class="form-control"
                                   placeholder="Correo Electronico" required>
                            <span class="fa fa-envelope form-control-feedback right" aria-hidden="true"></span>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback">
                            <select class="form-control" required name="status">
                                <option value="" disabled>-- Selecciona estado --</option>
                                <option value="1" selected>Activo</option>
                                <option value="0">Inactivo</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback">
                            <label for="rfc">RFC</label>
                            <input name="rfc" type="text" class="form-control has-feedback-left rfc"
                                   placeholder="AAA10101AAA" maxlength="13" required>
                            <span class="fa fa-text-width form-control-feedback right" aria-hidden="true"></span>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback">
                            <label for="rol">Rol</label>
                            <select class="form-control" required name="rol">
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
                            <select class="form-control" required name="dpto">
                                <option value="" selected disabled>-- Selecciona su rol --</option>
                                <?php foreach ($dptos as $d) { ?>
                                    <option value="<?php echo $d['id'] ?>"><?php echo $d['name'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <hr>
                        <div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback">
                            <label for="rol">Accesos</label>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback">
                            <input id="username" name="username" type="text" class="form-control" placeholder="Usuario">
                            <span class="fa fa-user-circle form-control-feedback right" aria-hidden="true"></span>
                        </div>

                        <div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback">
                            <input id="password" name="password" required type="password" class="form-control"
                                   placeholder="Contraseña">
                            <span class="fa fa-key form-control-feedback right" aria-hidden="true"></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div id="result_user"></div>
                    <button id="save_data" type="submit" class="btn btn-success">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div> <!-- /Modal -->