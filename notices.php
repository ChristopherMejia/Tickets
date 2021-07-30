<?php
/**
 * Created by PhpStorm.
 * User: Freddy Arvizu
 * Date: 28/01/2020
 * Time: 05:02 PM
 */

$page = "notices";
$title = "Noticias - ";

include "includes/head.php";
include "includes/sidebar.php";


$sql = mysqli_query($con, $query = "SELECT * FROM notices;");
$c = mysqli_fetch_array($sql);


if ($c['active'] == 1) {
    ?>
    <div class="right_col"> <!-- page content -->

        <div class="x_panel">
            <div class="card-header-tab card-header">
                <h1><?php echo $c['title'] ?></h1>
            </div>
            <div class="x_title">

                <?php if(!empty($c['error'])){ ?>
                <h3> Error:</h3>
                <p><?php echo $c['error'] ?></p>
                <?php } ?>

                <?php if(!empty($c['description'])){ ?>
                <h3> Descripción:</h3>
                <p>
                    <?php echo $c['description'] ?>
                </p>
                 <?php } ?>

                <?php if(!empty($c['solution'])){ ?>
                <h3> Solución:</h3>
                <p>
                    <?php echo $c['solution'] ?>
                </p>
                <?php } ?>
            </div>
        </div>
    </div><!-- /page content -->
    <?php
} else {
    echo "<script>setTimeout(function(){ window.history.back();}, 3000);</script>";
}

include "includes/footer.php"
?>



