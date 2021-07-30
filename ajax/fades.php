<?php
/**
 * Created by PhpStorm.
 * User: Freddy Arvizu
 * Date: 12/12/2019
 * Time: 06:55 PM
 */


include "../config/config.php";//DB


// escaping, additionally removing everything that could be (html/javascript-) code
$q = mysqli_real_escape_string($con, (strip_tags($_REQUEST['q'], ENT_QUOTES)));
$aColumns = array('f.name');//Columnas de busqueda
$sTable = "`fades` f";
$sWhere = "LEFT JOIN modules m ON f.module_id = m.id  WHERE
	m.`name` ='" . $_GET['module'] . "'";

if ($_GET['q'] != "") {
    $sWhere = "LEFT JOIN modules m ON f.module_id = m.id  WHERE
	m.`name` ='" . $_GET['module'] . "'";
    $sWhere .= "AND (";
    for ($i = 0; $i < count($aColumns); $i++) {
        $sWhere .= $aColumns[$i] . " LIKE '%" . $q . "%' OR ";
    }
    $sWhere = substr_replace($sWhere, "", -3);
    $sWhere .= ')';
}
$sWhere .= " ORDER BY f.name ASC";
include 'pagination.php'; //include pagination file
//pagination variables
$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page'])) ? $_REQUEST['page'] : 1;
$per_page = 20; //how much records you want to show
$adjacents = 4; //gap between pages after number of adjacents
$offset = ($page - 1) * $per_page;
//Count the total number of row in your table*/
$count_query = mysqli_query($con, "SELECT count(*) AS numrows FROM $sTable  $sWhere");
$row = mysqli_fetch_array($count_query);
$numrows = $row['numrows'];
$total_pages = ceil($numrows / $per_page);
$reload = './users.php';
//main query to fetch the data
$sql = "SELECT f.* FROM  $sTable $sWhere LIMIT $offset,$per_page";

//var_dump($sql);
$query = mysqli_query($con, $sql);
//loop through fetched data
if ($numrows > 0) {

    ?>
    <table class="table table-striped jambo_table bulk_action">
        <thead>
        <tr class="headings">
            <th class="column-title">Nombre</th>
            <th class="column-title no-link last"><span class="nobr"></span></th>
        </tr>
        </thead>
        <tbody>
        <?php
        while ($r = mysqli_fetch_array($query)) {
            $name = $r['name'];
            $id = $r['id'];
            $created_at = date('d/m/Y', strtotime($r['created_at']));
            ?>
            <input type="hidden" value="<?php echo $name; ?>" id="name<?php echo $id; ?>">
            <input type="hidden" value="<?php echo $id; ?>" id="id<?php echo $id; ?>">

            <tr class="even pointer">

                <td>
                    <a href="fade-detail?file=<?php echo $name ?>">
                        <i class="fa fa-file-code-o fa-2x" aria-hidden="true"></i> &nbsp;
                        <?php echo $name; ?>
                    </a>
                </td>
                <td>

                    <a href="fade-detail?file=<?php echo $name ?>">
                        <i class="fa fa-external-link fa-2x" title="Ver FADE"></i>
                    </a>

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

