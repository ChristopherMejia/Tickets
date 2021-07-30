<?php
/**
 * Created by PhpStorm.
 * User: Freddy Arvizu
 * Date: 23/08/2019
 * Time: 01:46 PM
 */
include "../config/config.php";//DB


if (isset($_POST['id_dpto'])) {
    $id_dpto = intval($_POST['id_dpto']);


    if ($query = mysqli_query($con, $q = "SELECT
            u.id, u.name
        FROM
            users u
        LEFT JOIN users_has_departments uh ON uh.user_id = u.id
        LEFT JOIN departments d ON d.id = uh.department_id
        WHERE
            uh.department_id =" . $id_dpto." AND u.is_active = 1 AND (deleted_at IS NULL OR deleted_at = '')")) {

//        var_dump($q);
        ?>


        <select class="form-control" name="asigned_id" id="asigned_id" >
            <option selected disabled value="">-- Selecciona --</option>
            <?php
            foreach ($query as $q) {
                ?>
                <option value="<?php echo $q['id'] ?>"><?php echo $q['name'] ?></option>
                <?php
            }
            ?>
        </select>
        <?php
    } else {

    }
} //end if

