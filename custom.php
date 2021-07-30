<?php
/**
 * Created by PhpStorm.
 * User: Freddy Arvizu
 * Date: 27/11/2019
 * Time: 02:28 PM
 */
$page = 'configuration';
include "includes/messages.php";
include "includes/head.php";
include "config/presence.php";
include "includes/sidebar.php";


?>
<div class="right_col" role="main">
    <!-- page content -->
    <div class="">
        <div class="page-title">

            <!-- content  -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="dashboard">Dashboard</a></li>

                    <li class="breadcrumb-item active" aria-current="page">Configuraciòn</li>
                </ol>
            </nav>
            <div id="exTab1" class="container">
                <ul class="nav nav-pills">
                    <li class="active">
                        <a href="#a1" data-toggle="tab"> Generales</a>
                    </li>

                </ul>
                <div class="tab-content clearfix">
                    <div class="tab-pane active" id="a1">
                        <div class="container">
                            <div id="result"></div>
                            <form class="form-horizontal form-label-left input_mask" method="post" id="frm_custom"
                                  name="frm_grls">

                                <div class="row grls">
                                    <div class="col-md-2">
                                        <p class="grls-text"></p>
                                        Tema
                                    </div>
                                    <div class="col-md-10 ">
                                        <div class="col-lg-4">
                                            <img src="images/dark-theme.jpg" class="img-thumbnail img-min">

                                            <label class="checkbox-inline">
                                                <input type="radio" class="checkbox-inline custom-control-input"
                                                       id="theme"
                                                       name="groupTheme"
                                                       value="dark"
                                                    <?php if ($dataUserCustom['theme'] == 'dark') { ?> checked <?php } ?>
                                                       onclick="document.documentElement.classList.add('dark-theme')">
                                                Dark Theme
                                            </label>

                                        </div>
                                        <div class="col-lg-4">
                                            <img src="images/windows-theme.jpg" class="img-thumbnail img-min">
                                            <label class="checkbox-inline">
                                                <input type="radio" class="checkbox-inline custom-control-input"
                                                       id="theme"
                                                       name="groupTheme"
                                                       value="windows"
                                                    <?php if ($dataUserCustom['theme'] == 'windows') { ?> checked <?php } ?>
                                                       onclick="document.documentElement.classList.remove('dark-theme')"
                                                >
                                                Windows Theme
                                            </label>


                                        </div>

                                    </div>
                                </div>
                                <div class="panel-footer text-right">
                                    <input type="hidden" name="user_id" value="<?php echo $user_id ?>">
                                    <button type="submit" id="save_data" class="btn btn-success pull-right">Guardar
                                    </button>
                                </div>
                            </form>
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
<script type="text/javascript" src="js/custom.js"></script>

</body>
</html>