<?php
/**
 * Created by PhpStorm.
 * User: Freddy Arvizu
 * Date: 17/01/2020
 * Time: 10:48 AM
 */
$page = 'installation';
include "includes/messages.php";
include "includes/head.php";
include "config/presence.php";
include "includes/sidebar.php";


/* Company */
$companies = mysqli_query($con, "SELECT * FROM companies GROUP BY `group`;");

?>
<div class="right_col" role="main">
    <!-- page content -->
    <div class="">
        <div class="page-title">

            <!-- content  -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="dashboard">Dashboard</a></li>

                    <li class="breadcrumb-item active" aria-current="page">Control de Instalación</li>
                </ol>
            </nav>


            <ul class="nav nav-tabs">
                <li class="active">
                    <a href="#1" data-toggle="tab">
                        <i class="fa fa-archive" aria-hidden="true"></i>
                        PENDIENTES
                    </a>
                </li>
                <li>
                    <a href="#2" data-toggle="tab">
                        <i class="fa fa-archive" aria-hidden="true"></i>
                        HISTORICO
                    </a>
                </li>

            </ul>

            <div class="tab-content">
                <div class="tab-pane active" id="1">
                    <div class="container">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2>Pendientes por instalar</h2>
                                <ul class="nav navbar-right panel_toolbox">
                                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                    </li>
                                    <li><a class="close-link"><i class="fa fa-close"></i></a>
                                    </li>
                                </ul>
                                <div class="clearfix"></div>
                            </div>
                            <div class="col-md-12 col-sm-12 col-xs-12 ">
                                <div class="input-group bg-blue full-width">
                                    <h4 class="card-title">EMPRESA</h4>
                                    <select class="form-control disabled company_id" name="company_id" id="company_id">
                                        <option disabled selected>-[ Selecciona una opción ]-</option>
                                        <?php foreach ($companies as $c) { ?>
                                            <option value="<?php echo $c['group']; ?>">
                                                <?php echo $c['name']; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <input type="hidden" id="user_installation" value="<?php echo $_SESSION["user_id"] ?>">

                                <div class="results">
                                    <ul class="form-control to-do" id="program-list">
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="2">
                    <div class="container">

                        <div class="x_panel">
                            <div class="x_title">
                                <h2>Historico de Instalaciones</h2>
                                <ul class="nav navbar-right panel_toolbox">
                                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                    </li>
                                    <li><a class="close-link"><i class="fa fa-close"></i></a>
                                    </li>
                                </ul>
                                <div class="clearfix"></div>
                            </div>

                            <!-- form search -->
                            <form class="form-horizontal" id="frm_histoy">
                                <div class="row">


                                    <div class="col-lg-5 action-hidden">
                                        <div class="input-group">
                                            <span class="input-group-addon">EMPRESA</span>
                                            <select class="form-control" name="company_id"
                                                    id="company_id_hist" onchange="load(1)">>
                                                <option disabled selected>-[ Selecciona una opción ]-</option>
                                                <?php foreach ($companies as $c) { ?>
                                                    <option value="<?php echo $c['group']; ?>">
                                                        <?php echo $c['name']; ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="input-group">
                                            <span class="input-group-addon">INICIO</span>
                                            <input type="date" name="start_at" id="start_at" value=""
                                                   class="form-control" onchange="load(1)">
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="input-group">
                                            <span class="input-group-addon">FIN</span>
                                            <input type="date" name="finish_at" id="finish_at" value=""
                                                   class="form-control" onchange="load(1)">
                                        </div>
                                    </div>

                                    <!-- <span id="loader"></span> -->
                                </div>

                            </form>
                            <!-- end form search -->
                            <div class="x_content">
                                <div class="table-responsive">
                                    <!-- ajax -->
                                    <div id="resultados"></div><!-- Carga los datos ajax -->
                                    <div class='outer_div'></div><!-- Carga los datos ajax -->
                                    <!-- /ajax -->
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /page content -->


<?php
include "includes/footer.php";
?>

<!--<script src="js/control_installations.js"></script>-->
<script>

    $(document).ready(function () {
        load(1);
    });

    function load(page) {

        var company_id =  $("#company_id_hist").val();
        var start_at = $("#start_at").val();
        var finish_at = $("#finish_at").val();

        $("#loader").fadeIn('slow');
        $.ajax({
            url: "./ajax/historical-installation.php?action=ajax&page=" + page + "&company_id=" + company_id + "&start_at=" + start_at + "&finish_at=" + finish_at,
            beforeSend: function () {
                $('#loader').html('<img src="./images/ajax-loader.gif"> Cargando...');
            },
            success: function (data) {
                $(".outer_div").html(data).fadeIn('slow');
                $('#loader').html('');
            }
        });
    }

    $('.company_id').on('change', function () {

        // alert("voy a mandar: " + this.value);
        var group_id = this.value;
        var parametros = new FormData();
        parametros.append("group_id", group_id);
        parametros.append("action", "ajax");

        $.ajax({
            type: "POST",
            url: "./ajax/showPrograms.php",
            data: parametros,
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function () {
                $('#loader').html('<img src="./images/ajax-loader.gif"> Cargando...');
            },
            success: function (data) {

                $(".results").html(data).fadeIn('fast');
            }
        });

    });

</script>
</body>
</html>