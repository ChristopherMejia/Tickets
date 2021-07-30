<div class="clearfix heigth_f"></div>
</div>
</div>
<footer>
    <div class="pull-right">
        Copyright © 2020 <a target="_blank" style="color: #2771ca" href="#">Intesystem. </a>
        <input type="hidden" id="page" value="<?php echo $page ?>">
    </div>
    <div class="clearfix"></div>
</footer>


<!-- jQuery -->
<!--<script src="js/jquery/dist/jquery.min.js"></script>-->
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="js/jquery-ui-1.9.2.custom.min.js"></script>
<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>-->
<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>-->

<!-- Bootstrap -->
<!--<script src="css/bootstrap/dist/js/bootstrap.min.js"></script>-->
<!--<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>-->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>

<!-- Autocomplete -->
<!--<script src="https://cdn.jsdelivr.net/gh/xcash/bootstrap-autocomplete@v2.3.0/dist/latest/bootstrap-autocomplete.min.js"></script>-->


<!-- FastClick -->
<script src="js/fastclick/lib/fastclick.js"></script>
<!-- NProgress -->
<script src="css/nprogress/nprogress.js"></script>
<!-- iCheck -->
<script src="css/iCheck/icheck.min.js"></script>
<!-- jQuery custom content scroller -->
<script src="css/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js"></script>

<!-- Drag and Drop Files -->
<!--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>-->
<script type="text/javascript" src="js/dropzone/dropzone.js"></script>
<script type="text/javascript" src="js/dropzone/main.js"></script>


<!-- Datatables -->
<script src="js/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="css/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script src="js/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
<script src="css/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"></script>
<script src="js/datatables.net-buttons/js/buttons.flash.min.js"></script>
<script src="js/datatables.net-buttons/js/buttons.html5.min.js"></script>
<script src="js/datatables.net-buttons/js/buttons.print.min.js"></script>
<script src="js/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js"></script>
<script src="js/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
<script src="js/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="css/datatables.net-responsive-bs/js/responsive.bootstrap.js"></script>
<script src="js/datatables.net-scroller/js/dataTables.scroller.min.js"></script>
<script src="js/jszip/dist/jszip.min.js"></script>
<script src="js/pdfmake/build/pdfmake.min.js"></script>
<script src="js/pdfmake/build/vfs_fonts.js"></script>


<script src="js/fancybox/jquery.fancybox.min.js"></script>

<!-- Custom Theme Scripts -->
<script src="js/custom.min.js"></script>

<!-- bootstrap-daterangepicker -->
<script src="js/moment/min/moment.min.js"></script>
<script src="css/bootstrap-daterangepicker/daterangepicker.js"></script>

<!-- Sweetalert -->
<script type="application/javascript" src="js/sweetalert2/sweetalert2.all.min.js"></script>


<script src="js/typehead/typehead-bootstrap3.js"></script>
<script>
    $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip();

        $.get("resources/json/menu.json", function (data) {
            $(".typehead_menu").typeahead({
                source: data
            });
        }, 'json');

        $.get("resources/json/programs.json", function (data) {
            $(".typehead_program").typeahead({
                source: data
            });
        }, 'json');

    });
</script>

<script type="text/javascript" src="js/main.js"></script>
<script type="text/javascript" src="js/jquery-waypoints.js"></script>

<script src="js/highcharts/highcharts.js"></script>
<script src="js/highcharts/modules/exporting.js"></script>
<script src="js/highcharts/modules/export-data.js"></script>

<script>
    $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>

<!-- NOTIFICATIONS / PUSH -->
<script src="js/push/push.min.js"></script>
<script src="js/notifications.js"></script>
<!--<script src="js/expire.session.js"></script>-->
<!--<script type="text/javascript" src="js/snow-falling.js"></script>-->
<?php
/*  *******************************PERMITIONS */
include "config/permissions.php";
/*  ***************************END PERMITIONS */
?>
