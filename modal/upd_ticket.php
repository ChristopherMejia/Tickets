<?php

$role = $global->getRol($user_id);
$projects_upd = mysqli_query($con, "SELECT * FROM projects");
$priorities_upd = mysqli_query($con, "SELECT * FROM priorities");
$statuses_upd = $permission->showStatus($role['id']);
$kinds_upd = mysqli_query($con, "SELECT * FROM kinds");
$categories_upd = mysqli_query($con, "SELECT * FROM categories");
$dptos = mysqli_query($con, "SELECT * FROM departments WHERE id != 4");

$infoDetailTicket = $global->getInfoDetailTicket($idTicket);
$modules = mysqli_query($con, $q = "SELECT * FROM modules ORDER BY name ASC");
$fades = mysqli_query($con, "SELECT * FROM fades ORDER BY name ASC");

?>
<?php include "modal/new_fade.php"; ?>
<!-- Modal -->
<div class="modal fade bs-example-modal-lg-udp" id="upd-ticket" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form class="form-horizontal form-label-left input_mask" method="post" id="upd" name="upd">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel"> Editar Ticket</h4>
                    <input type="hidden" class="form-control" name="ticket_id" id="mod_id" required disabled>
                </div>
                <div class="modal-body">

                    <input type="hidden" name="ticket_id" id="ticket_id" value="<?php echo $idTicket; ?>">
                    <div class="form-group action-hidden">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="empresa">Empresa</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                            <input type="text" class="form-control" placeholder="Empresa" name="company_id"
                                   id="mod_company_id"
                                   required disabled>
                        </div>
                    </div>
                    <div class="form-group action-hidden">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Tipo
                        </label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                            <select class="form-control" name="kind_id" required id="mod_kind_id"
                                    onchange="filterKinds(this.value)" onclick="validateKinds(this.value)">
                                <?php foreach ($kinds_upd as $p): ?>
                                    <option value="<?php echo $p['id']; ?>"><?php echo $p['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <section id="fade" style="display: none;">
                        <div class="form-group action-hidden">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="module">
                                Modulo
                                <span class="required">*</span>
                            </label>
                            <div class="col-md-9 col-sm-9 col-xs-12">

                                <select class="form-control border kinds border-warning" name="module_f"
                                        id="mod_module_f" onchange="filterFades(this.value)"
                                        disabled>
                                    <option value="" selected disabled>-[Seleccione una opción]-</option>
                                    <?php foreach ($modules as $m) { ?>
                                        <option value="<?php echo $m['id']; ?>"
                                            <?php if ($m['id'] == $infoFade['module_id']) {
                                                echo 'selected';
                                            } else echo '' ?>>
                                            <?php echo $m['name']; ?>
                                        </option>
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

                                <select class="form-control border kinds border-warning" name="name_f"
                                        id="mod_name_f"
                                        disabled>
                                    <option value="" selected disabled>-[Seleccione una opción]-</option>
                                    <?php foreach ($fades as $fa) { ?>
                                        <option value="<?php echo $fa['id']; ?>"
                                            <?php if ($fa['id'] == $infoFade['fade_id']) {
                                                echo 'selected';
                                            } else echo '' ?>
                                        ><?php echo $fa['name']; ?></option>
                                    <?php } ?>
                                </select>


                                <button type="button" id="add_fade_fast" class="btn" title="Agregar Fade"
                                        data-toggle="modal"
                                        data-target="#new_fade_modal" title="Agregar Fade"
                                        disabled>
                                    <i class="fa fa-plus-circle" aria-hidden="true"></i>
                                </button>


                            </div>
                        </div>
                    </section>
                    <div class="form-group action-hidden">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Titulo<span
                                    class="required">*</span></label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                            <input type="text" name="title" class="form-control" placeholder="Titulo" name="title"
                                   id="mod_title"
                                   value="<?php echo $title; ?>" disabled required>
                        </div>
                    </div>
                    <div class="form-group action-hidden">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Descripción <span
                                    class="required">*</span>
                        </label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                            <textarea style="resize: none; resize: vertical!important;" name="description"
                                      id="mod_description"
                                      class="text-custom form-control col-md-7 col-xs-12"
                                      placeholder="Añada una descripción detallada..." disabled required>
                                <?php echo $description ?>
                            </textarea>
                        </div>
                    </div>
                    <div class="form-group action-hidden">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Proyecto
                        </label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                            <select class="form-control" name="project_id" id="mod_project_id" required>
                                <option selected="" value="">-- Selecciona --</option>
                                <?php foreach ($projects_upd as $p): ?>
                                    <option value="<?php echo $p['id']; ?>"><?php echo $p['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group action-hidden">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Categoría
                        </label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                            <select class="form-control" name="category_id" id="mod_category_id" required>
                                <option selected="" value="">-- Selecciona --</option>
                                <?php foreach ($categories_upd as $p): ?>
                                    <option value="<?php echo $p['id']; ?>"><?php echo $p['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group action-hidden">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Prioridad
                        </label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                            <select class="form-control" name="priority_id" id="mod_priority_id" required>
                                <option selected="" value="">-- Selecciona --</option>
                                <?php foreach ($priorities_upd as $p): ?>
                                    <option value="<?php echo $p['id']; ?>"><?php echo $p['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Estatus
                        </label>
                        <div class="col-md-9 col-sm-9 col-xs-12">

                            <select class="form-control" name="status_id" id="mod_status_id"
                                    onchange="checkStatus(this.value, <?php echo $role['id'] ?>)" required>
                                <option selected="" value="">-- Selecciona --</option>
                                <?php foreach ($statuses_upd as $p): ?>
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
                            <select class="form-control" id="mod_dpto_id" name="dpto_id"
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
                            Asignar a:*
                        </label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                            <select class="form-control assigned" name="asigned_id" id="mod_asigned_id" disabled>
                                <option selected disabled value="">-- Selecciona --</option>
                            </select>
                        </div>
                    </div>
                    <section id="dTicket">
                        <div class="form-group action-hidden-dev">
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
                                    <!--                                    <span class="required">*</span>-->
                                </label>
                                <div class="col-md-9 col-sm-9 col-xs-12">
                                    <select class="form-control border border-primary" name="module" id="module_id_r"
                                    >
                                        <option value="" selected>-[Seleccione una opción]-</option>
                                        <?php foreach ($modules as $m) { ?>
                                            <option value="<?php echo $m['id']; ?>"
                                                <?php if ($m['id'] == $infoDetailTicket['module_id']) {
                                                    echo 'selected';
                                                } else echo '' ?>
                                            ><?php echo $m['name']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group action-hidden">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="program">
                                    Menu
                                    <!--                                    <span class="required">*</span>-->
                                </label>
                                <div class="col-md-9 col-sm-9 col-xs-12">
                                    <input class="form-control typehead_menu" placeholder="Escribe para buscar..."
                                           type="text"
                                           autocomplete="off" name="program_name" id="program_name_r"
                                           value="<?php echo $infoDetailTicket['program'] ?>">
                                </div>
                            </div>
                            <div class="form-group action-hidden-dev">

                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="module">
                                    Programa(s) Afectados
                                    <!--                                    <span class="required">*</span>-->
                                </label>
                                <!-- Section only Developers -->
                                <div class="col-md-9 col-sm-9 col-xs-12 ">
                                    <div class="input-group">
                                        <input class="form-control border border-primary typehead_program"
                                               placeholder="Escribe para buscar..."
                                               type="text"
                                               autocomplete="off"
                                               name="program"
                                               id="typehead_program">

                                        <span class="input-group-btn">
                                    <button id="add-item-program" class="btn btn-info" type="button"
                                            onclick="newElement()">
                                        <i class="fa fa-plus" aria-hidden="true"></i>
                                    </button>
                                </span>
                                    </div>

                                    <ul class="form-control to-do" id="program-list">
                                        <?php if (!empty($infoDetailTicket['results'])) {

                                            $w = strtok($infoDetailTicket['results'], ",");
                                            while ($w !== false) {
                                                ?>
                                                <li class="li-program"><?php echo $w ?></li>
                                                <?php
                                                $w = strtok(",");
                                            }
                                        } ?>
                                    </ul>
                                    <input type="hidden" class="form-control" name="program-list-array"
                                           id="program-list-array">
                                </div>
                            </div>
                    </section>
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="mod_role_id" value="<?php echo $role['id'] ?>">
                    <div id="result2" class="response_result"></div>
                    <button id="upd_data" type="submit" class="btn btn-lg btn-success">Guardar Cambios</button>


                </div>
            </form>
        </div>

    </div>
</div> <!-- /Modal -->
<script src="js/to-do-list.js"></script>