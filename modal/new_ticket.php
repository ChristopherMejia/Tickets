<?php
$role = $global->getRol($user_id);
$projects = mysqli_query($con, "SELECT * FROM projects");
$priorities = mysqli_query($con, "SELECT * FROM priorities");
//$statuses = mysqli_query($con, "SELECT * FROM status");
$statuses = $permission->showStatus($role['id']);
$kinds = mysqli_query($con, "SELECT * FROM kinds");
$categories = mysqli_query($con, "SELECT * FROM categories");
$dptos = mysqli_query($con, "SELECT * FROM departments WHERE id != 4");
$modules = mysqli_query($con, "SELECT * FROM modules ORDER BY name ASC");
$fades = mysqli_query($con, "SELECT * FROM fades ORDER BY name ASC");

?>
<?php include  "modal/new_fade.php"; ?>
<div id="action-add-ticket"> <!-- Agregar Ticket -->
    <a data-toggle="modal" data-target=".bs-example-modal-lg-add" class="btn-floating"
       id="a-add-ticket" title="Agregar ticket">
        <i class="fa fa-plus" aria-hidden="true"></i>
    </a>
</div>
<div class="modal fade bs-example-modal-lg-add" id="add-ticket" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form class="form-horizontal form-label-left input_mask" method="post" id="frm-add-ticket"
                  name="frm-add-ticket"
                  enctype="multipart/form-data">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel">Agregar Ticket</h4>
                </div>
                <div class="modal-body">

                    <section id="step1" style="display: block;">
                        <div class="form-group action-hidden">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Tipo
                            </label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <select class="form-control disabled" name="kind_id" id="kind_id"
                                        onchange="filterKinds(this.value)">
                                    <?php foreach ($kinds as $p): ?>
                                        <option value="<?php echo $p['id']; ?>"><?php echo $p['name']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div id="fade" style="display: none;">
                            <div class="form-group action-hidden">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="module">
                                    Modulo
                                    <span class="required">*</span>
                                </label>
                                <div class="col-md-9 col-sm-9 col-xs-12">
                                    <select class="form-control border kinds border-warning" name="module_f"
                                            id="module_id_f" onchange="filterFades(this.value)"
                                            disabled required>
                                        <option value="" selected disabled>-[Seleccione una opción]-</option>
                                        <?php foreach ($modules as $m) { ?>
                                            <option value="<?php echo $m['id']; ?>"><?php echo $m['name']; ?></option>
                                        <?php } ?>
                                    </select>


                                </div>
                            </div>
                            <div class="form-group action-hidden">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="module">
                                    Nombre
                                    <span class="required">*</span>
                                </label>
                                <div class="col-md-9 col-sm-9 col-xs-12 wcontrol">
                                    <select class="form-control border kids fades border-warning" name="name_f"
                                            id="name_f" data-show-subtext="true" data-live-search="true"
                                            disabled required >
                                        <option value="" selected disabled>-[Seleccione una opción]-</option>
                                        <?php foreach ($fades as $f) { ?>
                                            <option data-subtext="<?php echo $f['name']; ?>"
                                                    value="<?php echo $f['id']; ?>"><?php echo $f['name']; ?></option>
                                        <?php } ?>
                                    </select>

                                    <button type="button" id="add_fade_fast" class="btn" title="Agregar Fade" data-toggle="modal" data-target="#new_fade_modal" title="Agregar Fade"
                                            disabled>
                                        <i class="fa fa-plus-circle" aria-hidden="true"></i>
                                    </button>


                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Empresa
                            </label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <select class="form-control" name="company_id">
                                    <?php foreach ($allSubCompanies as $c): ?>
                                        <option value="<?php echo $c['id']; ?>"
                                            <?php if ($company_id == $c['id']) { ?> selected <?php } ?>
                                        ><?php echo $c['name']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Titulo<span
                                        class="required">*</span></label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <input type="text" name="title" class="form-control" placeholder="Titulo">
                            </div>
                            <div class="dropzone-previews"></div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">
                                Descripción
                                <span class="required">*</span>
                            </label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                            <textarea style="min-height: 70px;resize: vertical; " name="description"
                                      class="text-custom form-control col-md-7 col-xs-12"
                                      placeholder="Añada una descripción detallada..."></textarea>
                            </div>
                        </div>
                        <div class="form-group action-hidden">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Proyecto
                            </label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <select class="form-control" name="project_id">
                                    <option selected disabled value="">-- Selecciona --</option>
                                    <?php foreach ($projects as $p): ?>
                                        <option value="<?php echo $p['id']; ?>"><?php echo $p['name']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group action-hidden">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Categoria
                            </label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <select class="form-control" name="category_id">
                                    <option selected disabled value="">-- Selecciona --</option>
                                    <?php foreach ($categories as $p): ?>
                                        <option value="<?php echo $p['id']; ?>"><?php echo $p['name']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group action-hidden">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Prioridad
                            </label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <select class="form-control" name="priority_id">
                                    <option selected disabled value="">-- Selecciona --</option>
                                    <?php foreach ($priorities as $p): ?>
                                        <option value="<?php echo $p['id']; ?>"><?php echo $p['name']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group action-hidden">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Estatus
                            </label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <select class="form-control" name="status_id">
                                    <option selected disabled value="">-- Selecciona --</option>
                                    <?php foreach ($statuses as $p): ?>
                                        <option value="<?php echo $p['id']; ?>"><?php echo $p['name']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group action-hidden">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                                Departamento
                            </label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <select class="form-control" name="dpto" id="dpto_id"
                                        onchange="filterUsers(this.value)">
                                    <option selected disabled value="">-- Selecciona --</option>
                                    <?php foreach ($dptos as $d): ?>
                                        <option value="<?php echo $d['id']; ?>"><?php echo $d['name']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group action-hidden">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                                Asignar a:
                            </label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <select class="form-control assigned" name="asigned_id" id="asigned_id" disabled>
                                    <option selected disabled value="">-- Selecciona --</option>

                                    <!-- Ajax Dptos/Users -->
                                </select>
                            </div>
                        </div>
                        <div id="dTicket">
                            <div class="form-group action-hidden">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">

                                </label>
                                <div class="col-md-9 col-sm-9 col-xs-12">
                                    <button type="button" id="btn_show_detail" onclick="showDetails()"
                                            class="btn btn-info btn-block" style="display: block;">
                                        Agregar Detalles
                                    </button>
                                    <button type="button" id="btn_hidden_detail" onclick="hiddenDetails()"
                                            class="btn btn-primary btn-block" style="display: none;">
                                        Ocultar Detalles
                                    </button>
                                </div>
                            </div>

                            <div style="height: 18px"></div>

                            <div class="form-group " id="details-ticket" style="display: none;">
                                <div class="form-group action-hidden">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="module">
                                        Modulo
                                        <span class="required">*</span>
                                    </label>
                                    <div class="col-md-9 col-sm-9 col-xs-12">
                                        <select class="form-control border border-primary" name="module"
                                                id="module_id_r"
                                                disabled>
                                            <option value="" selected disabled>-[Seleccione una opción]-</option>
                                            <?php foreach ($modules as $m) { ?>
                                                <option value="<?php echo $m['id']; ?>"><?php echo $m['name']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group action-hidden">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="module">
                                        Menu
                                        <span class="required">*</span>
                                    </label>
                                    <div class="col-md-9 col-sm-9 col-xs-12">
                                        <input class="form-control border border-primary typehead_menu"
                                               placeholder="Escribe para buscar..."
                                               type="text"
                                               autocomplete="off" name="program" id="program_name_r" disabled>
                                    </div>
                                </div>
                                <div class="form-group action-hidden">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12">
                                        Observaciones
                                    </label>
                                    <div class="col-md-9 col-sm-9 col-xs-12">
                                                <textarea style="min-height: 200px;resize: vertical;"
                                                          name="observations"
                                                          id="observations_r"
                                                          class="text-custom form-control border border-primary col-md-7 col-xs-12"
                                                          placeholder="Añada una observacion detallada..."
                                                          disabled></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <section id="step2" style="display: none">

                        <div class="jumbotron">
                            <h1 class="display-4">¡Sube tus archivos!</h1>
                            <p class="lead">Puedes subir directamente tus screenshot, ve a la pantalla que quieres copiar despues presiona las teclas <b>alt +
                                    impr pant</b>, vuelve a esta pantalla y presiona
                                <b>crtl + v</b></p>
                            <div class="dv-upload" name="fileupload">
                                <p>
                                    <i class="fa fa-plus fa-5x attach-file-new" aria-hidden="true" title="Subir archivos"></i>
                                </p>

                            </div>
                            <hr class="my-4">
                            <div class="preview-img" id="result_files-preview"></div>
                            <p>Archivos Admitidos: 'jpg', 'png', 'jpeg', 'gif', 'pdf', 'xlsx', 'xls','xml', 'csv',
                                'docx','doc','log'</p>


                            <button type="button" data-dismiss="modal" aria-label="Close" class="btn btn-info btn-block" style="height: 50px">
                                FINALIZAR
                            </button>
                        </div>

                    </section>


                </div>
                <div class="modal-footer" id="footer_ticket">
                    <input type="hidden" name="user_id" value="<?php echo $user_id ?>">
                    <!-- Detail -->
                    <div id="result"></div>
                    <button id="save_data" type="submit" class="btn btn-lg  btn-success">
                        Guardar
                    </button>

                </div>
            </form>
            <form action="#" method="post" id="uploadFiles"
                  enctype="multipart/form-data">
                <input type="file" class="form-control-file hide-element fileupload"
                       name="files[]"
                       id="files-new"
                       multiple>
                <input type="hidden" class="form-control" name="user_id"
                       value="<?php echo $user_id ?>">
                <input type="hidden" class="form-control" name="ticket_id"
                       id="ticket_id">
            </form>
        </div>
    </div> <!-- /Modal -->
</div>

<!-- Modal Detail -->
