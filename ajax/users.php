<?php

include "../config/config.php";//DB



$action = (isset($_REQUEST['action']) && $_REQUEST['action'] != NULL) ? $_REQUEST['action'] : '';
if (isset($_GET['id'])) {
    $id_expence = intval($_GET['id']);
    $query = mysqli_query($con, "SELECT * FROM users WHERE id='" . $id_expence . "'");
    $count = mysqli_num_rows($query);
    if ($delete1 = mysqli_query($con, "UPDATE  users  SET deleted_at=NOW() WHERE id='" . $id_expence . "'")) {
        ?>
        <div class="alert alert-success alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            <strong>Aviso!</strong> Datos eliminados exitosamente.
        </div>
        <?php
    } else {
        ?>
        <div class="alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            <strong>Error!</strong> Lo siento algo ha salido mal intenta nuevamente.
        </div>
        <?php
    } //end else
} //end if
?>

<?php
if ($action == 'ajax') {
    // escaping, additionally removing everything that could be (html/javascript-) code
    $q = mysqli_real_escape_string($con, (strip_tags($_REQUEST['q'], ENT_QUOTES)));
    $aColumns = array('name', 'email');//Columnas de busqueda
    $sTable = "users";
    $sWhere = " WHERE (deleted_at IS NULL OR deleted_at = '')";

    if ($_GET['q'] != "") {
        $sWhere = "AND (";
        for ($i = 0; $i < count($aColumns); $i++) {
            $sWhere .= $aColumns[$i] . " LIKE '%" . $q . "%' OR ";
        }
        $sWhere = substr_replace($sWhere, "", -3);
        $sWhere .= ')';
    }
    $sWhere .= " order by created_at desc";
    include 'pagination.php'; //include pagination file
    //pagination variables
    $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page'])) ? $_REQUEST['page'] : 1;
    $per_page = 10; //how much records you want to show
    $adjacents = 4; //gap between pages after number of adjacents
    $offset = ($page - 1) * $per_page;
    //Count the total number of row in your table*/
    $count_query = mysqli_query($con, "SELECT count(*) AS numrows FROM $sTable  $sWhere");
    $row = mysqli_fetch_array($count_query);
    $numrows = $row['numrows'];
    $total_pages = ceil($numrows / $per_page);
    $reload = './users.php';
    //main query to fetch the data
    $sql = "SELECT * FROM  $sTable $sWhere LIMIT $offset,$per_page";


    $query = mysqli_query($con, $sql);
    //loop through fetched data
    if ($numrows > 0) {

        ?>
        <table class="table table-striped jambo_table bulk_action">
            <thead>
            <tr class="headings">
                <th class="column-title">Nombre</th>
                <th class="column-title">Correo Electrónico</th>
                <th class="column-title">Estado</th>
                <th class="column-title">Fecha</th>
                <th class="column-title">Role</th>
                <th class="column-title no-link last"><span class="nobr"></span></th>
            </tr>
            </thead>
            <tbody>
            <?php
            while ($r = mysqli_fetch_array($query)) {
                $id = $r['id'];
                $status = $r['is_active'];
                if ($status == 1) {
                    $status_f = "Activo";
                } else {
                    $status_f = "Inactivo";
                }

                $name = $r['name'];
                $email = $r['email'];
                $rol_id = $r['role'];
                $company_id = $r['company_id'];

                $sqlCompany = "SELECT * FROM companies WHERE id = $company_id";
                $queryCompanies = mysqli_query($con, $sqlCompany);
                foreach ($queryCompanies as $qc) {
                    $company = $qc['name'];
                    $rfc = $qc['RFC'];
                }

                $sqlDptos = "SELECT * FROM users_has_departments WHERE user_id = $id";
                $queryDptos = mysqli_query($con, $sqlDptos);
                foreach ($queryDptos as $qr) {
                    $department_id = $qr['department_id'];
                }


                $sqlRoles = "SELECT * FROM roles WHERE id = $rol_id";
                $queryRoles = mysqli_query($con, $sqlRoles);
                foreach ($queryRoles as $r) {
                    $role_f = $r['name'];
                }

//                $sqlDptos = "SELECT
//                                    d.id
//                                FROM
//                                    users u
//                                LEFT JOIN users_has_departments uh ON u.id = uh.user_id
//                                LEFT JOIN departments d ON d.id = uh.department_id WHERE u.id = $id";
//                $queryDptos = mysqli_query($con, $sqlDptos);
//                foreach ($queryDptos as $qd) {
//                    $department_id = $qd['id'];
//                }


                $created_at = date('d/m/Y', strtotime($r['created_at']));
                ?>
                <input type="hidden" value="<?php echo $name; ?>" id="name<?php echo $id; ?>">
                <input type="hidden" value="<?php echo $email; ?>" id="email<?php echo $id; ?>">
                <input type="hidden" value="<?php echo $rfc; ?>" id="rfc<?php echo $id; ?>">
                <input type="hidden" value="<?php echo $rol_id; ?>" id="rol<?php echo $id; ?>">
                <input type="hidden" value="<?php echo $department_id; ?>" id="dpto<?php echo $id; ?>">

                <?php
                //Súperusuario

                if ($rol_id == 1) {
                    $disabled = 'disabled';
                    $editAction = "";
                    $deleteAction = "";
                } else {
                    $disabled = '';
                    $editAction = "onclick=\"obtener_datos('" . $id . "');\" data-toggle=\"modal\" data-target=\".bs-example-modal-lg-upd\"";
                    $deleteAction = "onclick=\"eliminar('". $id."')\"";
                }

                ?>


                <tr class="even pointer">
                    <td><?php echo $name; ?></td>
                    <td><?php echo $email; ?></td>
                    <td><?php echo $status_f; ?></td>
                    <td><?php echo $created_at; ?></td>
                    <td><span class="badge"><?php echo $role_f; ?></span></td>
                    <td>
                        <span class="pull-right">
                        <a href="#" class='btn btn-default' title='Editar producto'
                            <?php echo $editAction ?>  <?php echo $disabled ?>>
                            <i class="glyphicon glyphicon-edit"></i>
                        </a>
                        <a href="#" class='btn btn-default' title='Borrar producto'
                            <?php echo $deleteAction ?> <?php echo $disabled ?>>
                            <i class="glyphicon glyphicon-trash"></i>
                        </a>
                        </span>
                    </td>
                </tr>
                <?php
            } //end while
            ?>
            <tr>
                <td colspan=12>
                        <span class="pull-right">
                        <?php echo paginate($reload, $page, $total_pages, $adjacents,'load'); ?>
                    </span>
                </td>
            </tr>
        </table>
        </div>
        <?php
    } else {
        ?>
        <div class="alert alert-warning alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            <strong>Aviso!</strong> No hay datos para mostrar
        </div>
        <?php
    }
}
?>