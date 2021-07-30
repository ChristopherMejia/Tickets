<?php
/**
 * Created by PhpStorm.
 * User: Freddy
 * Date: 09/01/2020
 * Time: 06:36 PM
 */

include "../config/config.php";//DB


if (isset($_POST['id_module'])) {
    $id_module = intval($_POST['id_module']);

    if ($query = mysqli_query($con, $q = "SELECT
            *
        FROM
            fades WHERE module_id=" . $id_module." ORDER BY name ASC  ")) {
        ?>


        <select class="form-control border kinds border-warning" name="name_f" id="name_f">
            <option selected disabled value="">-- Selecciona --</option>
            <?php
            foreach ($query as $q) {
                ?>
                <option data-subtext="<?php echo $q['name']; ?>"
                        value="<?php echo $q['id'] ?>"><?php echo $q['name'] ?></option>
                <?php
            }
            ?>
        </select>
        <?php
    } else {

    }
} //end if

