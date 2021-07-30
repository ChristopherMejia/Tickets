<?php
/**
 * Created by PhpStorm.
 * User: Freddy Arvizu
 * Date: 28/01/2020
 * Time: 04:46 PM
 */

$sql = mysqli_query($con, $query = "SELECT * FROM notices;");
$c = mysqli_fetch_array($sql);


if ($c['active'] == 1) {
?>
<div class="cookiesms" id="cookie1">
    <?php echo $c['short_description'] ?>
    <a href="notices">Léer Más</a>
    <!--    <button onclick="controlcookies()">Aceptar</button>-->
    <div class="cookies2">¡AVISO IMPORTANTE!</div>
</div>

<?php } ?>