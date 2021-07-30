<?php
/**
 * Created by PhpStorm.
 * User: Freddy Arvizu
 * Date: 21/11/2019
 * Time: 06:33 PM
 */


include "../config/config.php";//DB


// escaping, additionally removing everything that could be (html/javascript-) code
$q = mysqli_real_escape_string($con, (strip_tags($_REQUEST['q'], ENT_QUOTES)));
$aColumns = array('name', 'email');//Columnas de busqueda
$sTable = "log";

if ($_GET['q'] != "") {
    $sWhere = "AND (";
    for ($i = 0; $i < count($aColumns); $i++) {
        $sWhere .= $aColumns[$i] . " LIKE '%" . $q . "%' OR ";
    }
    $sWhere = substr_replace($sWhere, "", -3);
    $sWhere .= ')';
}
$sWhere .= " order by logtime desc";
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
            <th class="column-title">Nivel</th>
            <th class="column-title">Ruta</th>
            <th class="column-title">Accion</th>
            <th class="column-title">Prioridad</th>
            <th class="column-title">Parametros</th>
            <th class="column-title">Mensaje</th>
            <th class="column-title">Fecha</th>
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

            $type = $r['type'];
            $program = $r['program'];
            $action = $r['action'];
            $priority = $r['prority'];
            $parameters = $r['parameters'];
            $message = $r['message'];
            $logtime = date('d/m/Y', strtotime($r['logtime']));

            if($type == 'error'){
                $csstype = "color:red; text-align: center;";
                $icontype = "glyphicon glyphicon-exclamation-sign";
            }else{
                $csstype = "color:green; text-align: center;";
                $icontype = "glyphicon glyphicon-ok";
            }

            ?>
            <input type="hidden" value="<?php echo $type; ?>" id="type<?php echo $id; ?>">
            <input type="hidden" value="<?php echo $program; ?>" id="path<?php echo $id; ?>">
            <input type="hidden" value="<?php echo $action; ?>" id="action<?php echo $id; ?>">
            <input type="hidden" value="<?php echo $priority; ?>" id="prority<?php echo $id; ?>">
            <input type="hidden" value="<?php echo $parameters; ?>" id="parameters<?php echo $id; ?>">
            <input type="hidden" value="<?php echo $message; ?>" id="messages<?php echo $id; ?>">
            <input type="hidden" value="<?php echo $logtime; ?>" id="logtime<?php echo $id; ?>">

            <tr class="even pointer">
                <td><span  style="<?php echo $csstype ?>" ><i class="<?php echo $icontype ?>"> <?php echo $type; ?></span></td>
                <td><?php echo $program; ?></td>
                <td><?php echo $action; ?></td>
                <td><span class="badge"><?php echo $priority; ?></span></td>
                <td><?php echo $parameters; ?></td>
                <td><?php echo $message; ?></td>
                <td><?php echo $logtime; ?></td>
                <td>
                        <span class="pull-right">
                        <a href="#" class='btn btn-default' title='Ampliar Log'
                            <?php echo $editAction ?>  <?php echo $disabled ?>>
                            <i class="glyphicon glyphicon-zoom-in"></i>
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
                        <?php echo paginate($reload, $page, $total_pages, $adjacents, 'load'); ?>
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

?>