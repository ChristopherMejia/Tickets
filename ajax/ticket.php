<?php
/**
 * Created by PhpStorm.
 * User: Freddy Arvizu
 * Date: 01/08/2019
 * Time: 05:28 PM
 */


include "../config/config.php";//DB

$id = $_POST['id_ticket'];
$user_id = $_POST['user_id'];

$infoTicket = $global->getInfoTicket($id);
$infoFade = $global->getInfoFade($id);



$ticket = mysqli_query($con, $queryTicket = "SELECT
                                    t.title,
                                    t.description,
                                    t.kind_id,
                                    t.asigned_id,
                                    t.project_id,
                                    t.category_id,
                                    t.priority_id,
                                    t.status_id,
                                    d.id AS department_id
                                FROM
                                    tickets t
                                LEFT JOIN users u ON t.asigned_id = u.id
                                LEFT JOIN users_has_departments uh ON uh.user_id = u.id
                                LEFT JOIN departments d ON uh.department_id = d.id
                                WHERE
                                    t.id ='$id'");

//var_dump($queryTicket);


foreach ($ticket AS $t) {

    $title = $t['title'];
    $description = $t['description'];
    $kind_id = $t['kind_id'];
    $asigned = $t['asigned_id'];
    $project_id = $t['project_id'];
    $category_id = $t['category_id'];
    $priority_id = $t['priority_id'];
    $status_id = $t['status_id'];
    $department_id = $t['department_id'];

}
$uploads = mysqli_query($con, "SELECT * FROM upload_data WHERE ticket_id = " . $id . " LIMIT 5");

$projects = mysqli_query($con, "SELECT * FROM projects");
$priorities = mysqli_query($con, "SELECT * FROM priorities");
$statuses = mysqli_query($con, "SELECT * FROM status");
$kinds = mysqli_query($con, "SELECT * FROM kinds");
$categories = mysqli_query($con, "SELECT * FROM categories");
$dptos = mysqli_query($con, "SELECT * FROM departments WHERE id != 4");
$users = mysqli_query($con, "SELECT * from users");

$modules = mysqli_query($con, "SELECT * FROM modules ORDER BY name ASC");

//var_dump($kind_id);
if ($kind_id == 2) {
    $displayT = 'none';
    $displayF = 'block';
} else {
    $displayT = 'block';
    $displayF = 'none';
}

?>


<div id="result_ticket">
    <div class="card-hover-shadow-2x mb-3 card">
        <div class="card-header-tab card-header">
            INFORMACIÓN GENERAL:
        </div>
        <div class="card-body">

            <li class="list-group-item" style="display: none">
                <h5 class="card-title ttl">Empresa</h5>
                <input type="text" class="form-control" value="<?php echo $infoTicket['company'] ?>"
                       id="mod_company-s">
            </li>

            <li class="list-group-item action-hidden">
                <h5 class="card-title ttl">Tipo</h5>
                <select class="form-control" name="kind_id" id="mod_kind_id-s" disabled>
                    <?php foreach ($kinds as $k): ?>
                        <option value="<?php echo $k['id'] ?>" <?php if ($k['id'] == $kind_id) {
                            echo 'selected';
                        } else echo '' ?> ><?php echo $k['name']; ?></option>
                    <?php endforeach; ?>

                </select>
            </li>
            <li class="list-group-item">
                <h5 class="card-title ttl">Titulo</h5>
                <input type="hidden" class="form-control" id="mod_title-s" value="<?php echo $title ?>"
                       disabled>

                <h4><?php echo $title ?></h4>
            </li>
            <li class="list-group-item">

                <h5 class="card-title ttl">Descripción</h5>
                <?php echo html_entity_decode($description); ?>
                <textarea class="form-control" id="mod_description-s"
                          style="display: none"><?php echo $description ?></textarea>
            </li>

            <div class="row action-hidden" id="infoT" style="display: <?php echo $displayT ?>;">
                <div class="col-md-6">
                    <li class="list-g">
                        <h5 class="card-title ttl">Proyecto</h5>
                        <select class="form-control" name="project_id" id="mod_project_id-s" disabled>
                            <option value="">-- Selecciona --</option>
                            <?php foreach ($projects as $p): ?>
                                <option value="<?php echo $p['id'] ?>" <?php if ($p['id'] == $project_id) {
                                    echo 'selected';
                                } else echo '' ?> ><?php echo $p['name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </li>
                </div>
                <div class="col-md-6">
                    <li class="list-g">
                        <h5 class="card-title ttl">Catégoria</h5>
                        <select class="form-control" name="category_id" id="mod_category_id-s" required
                                disabled>
                            <option selected="" value="">-- Selecciona --</option>
                            <?php foreach ($categories as $c): ?>

                                <option value="<?php echo $c['id'] ?>" <?php if ($c['id'] == $category_id) {
                                    echo 'selected';
                                } else echo '' ?> ><?php echo $c['name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </li>
                </div>
            </div>
            <div class="row action-hidden" id="infoF" style="display: <?php echo $displayF ?>;">
                <div class="col-md-6">
                    <li class="list-g">
                        <h5 class="card-title ttl">Modulo</h5>
                        <select class="form-control" name="module_id" id="module_id_r" disabled>
                            <option value="" selected disabled>-[Seleccione una opción]-</option>
                            <?php foreach ($modules as $m) { ?>
                                <option value="<?php echo $m['id']; ?>"
                                    <?php if ($m['id'] == $infoFade['module_id']) {
                                        echo 'selected';
                                    } else echo '' ?>
                                ><?php echo $m['name']; ?></option>
                            <?php } ?>
                        </select>
                    </li>
                </div>
                <div class="col-md-6">
                    <li class="list-g">
                        <h5 class="card-title ttl">Nombre [Fade]</h5>
                        <input class="form-control typeahead" placeholder="Escribe para buscar..." type="text"
                               autocomplete="off" name="program_name" id="program_name_r"
                               value="<?php echo $infoFade['name'] ?>" readonly>
                    </li>
                </div>
            </div>
            <li class="list-group-item action-hidden">
                <h5 class="card-title ttl">Prioridad</h5>
                <?php
                foreach ($priorities as $p):

                    if ($p['id'] == $priority_id) {
                        ?>
                        <span class="label label-<?php echo $p['badge']; ?>"><?php echo $p['name']; ?></span>
                        <?php
                    }
                endforeach;
                ?>
                <select class="form-control" name="priority_id" id="mod_priority_id-s" style="display: none;">
                    <option selected="" value="">-- Selecciona --</option>

                    <?php foreach ($priorities as $p): ?>
                        <option value="<?php echo $p['id'] ?>"
                                value="<?php echo $p['id'] ?>" <?php if ($p['id'] == $priority_id) {
                            echo 'selected';
                        } else echo '' ?> ><?php echo $p['name']; ?></option>
                    <?php endforeach; ?>
                </select>

                </select>
            </li>
            <div class="row action-hidden">
                <div class="col-md-6">
                    <li class="list-g">
                        <h5 class="card-title ttl">Departamento</h5>
                        <select class="form-control" name="status_id" id="mod_dpto_id-s" required
                                disabled>
                            <option selected="" value="">-- Selecciona --</option>
                            <?php foreach ($dptos as $d): ?>
                                <option value="<?php echo $d['id'] ?>" <?php if ($d['id'] == $department_id) {
                                    echo 'selected';
                                } else echo '' ?> ><?php echo $d['name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </li>
                </div>
                <div class="col-md-6 ">
                    <li class="list-g">
                        <h5 class="card-title ttl">Asignado a:</h5>
                        <select class="form-control" name="status_id" id="mod_assigned_id-s" required
                                disabled>
                            <option selected="" value="">-- Selecciona --</option>
                            <?php foreach ($users as $u): ?>
                                <option value="<?php echo $u['id'] ?>" <?php if ($u['id'] == $asigned) {
                                    echo 'selected';
                                } else echo '' ?> ><?php echo $u['name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </li>
                </div>
            </div>

            <li class="list-group-item">
                <h5 class="card-title ttl">Estatus</h5>
                <select class="form-control" name="status_id" id="mod_status_id-s" required
                        disabled style="display: none">
                    <option selected="" value="">-- Selecciona --</option>
                    <?php foreach ($statuses as $s) { ?>
                        <option value="<?php echo $s['id'] ?>" <?php if ($s['id'] == $status_id) {
                            echo 'selected';
                        } else echo '' ?> ><?php echo $s['name']; ?></option>
                    <?php } ?>
                </select>
                <?php foreach ($statuses as $s) {
                    if ($status_id == $s['id']) {
                        ?>
                        <span class="badge badgecu label-<?php echo $s['badge'] ?>"><?php echo $s['name'] ?></span>
                    <?php }
                } ?>
                <br>
            </li>
            <li class="list-group-item" style="display: none">
                <h5 class="card-title ttl">FADE</h5>
                <input type="hidden" id="module_f" value="<?php echo $infoFade['module_id'] ?>">
                <input type="hidden" id="name_f" value="<?php echo $infoFade['name'] ?>">

            </li>

            </ul>


        </div>
    </div>
</div>


<?php

$role = $permission->get_rol($user_id);
if ($role['id'] == 4 OR $role['id'] == 5) {
    echo '<script>$(".action-hidden").css("display", "none");</script>';
}

if ($role['id'] == 5) {
    echo '<script>$(".super").css("display", "block");</script>';
}
?>
