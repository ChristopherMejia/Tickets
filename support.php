<?php
/**
 * Created by PhpStorm.
 * User: Freddy Arvizu
 * Date: 08/08/2019
 * Time: 04:49 PM
 */

$page = "support";
$title = "Asistencia Remota - ";
include "includes/messages.php";
include "includes/head.php";
include "config/presence.php";
include "includes/sidebar.php";

?>
<div class="right_col"> <!-- page content -->
    <img src="images/6633.jpg" class="img-responsive">
    <div class="x_panel">
        <div class="card-header-tab card-header">
            <h1>Asistencia Remota</h1>
        </div>
        <div class="x_title">
            <p>Para que otra persona tenga acceso a esta computadora y pueda controlarla, haz clic en el botón de descarga.</p>
           <a href="https://remotedesktop.google.com/support/" target="_blank">
             <i class="fa fa-download fa-5x" aria-hidden="true"></i>
           </a>
        </div>
    </div>

</div><!-- /page content -->

<?php include "includes/footer.php" ?>
