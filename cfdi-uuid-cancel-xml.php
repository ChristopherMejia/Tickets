<?php
$title = "CFDI UUID Cancelar | ";
include "includes/head.php";
include "includes/sidebar.php";

$dir = $path . "/public/xml";
$total_files = count(glob($dir . '/{*.xml}', GLOB_BRACE));
?>

<div class="right_col" role="main"> <!-- page content -->
    <div class="">
        <div class="page-title">
            <br>
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <h1>CFDI UUID CANCELAR</h1>
                    <code> Patch: <?php echo $dir; ?></code>
                    <p>Cancelación masiva mediante XML, <b>DETECNO</b></p>

                    <br><br>
                    <h2>Paso 1</h2>
                    <p>Cargar los <b>.xml</b></p>
                    <div class="panel panel-info">
                        <div class="panel-heading">Cargar XML(s)</div>
                        <div class="panel-body">
                            <!--                            <div class="dropzone"></div>-->

                            <div class="dropzone2"></div>
                        </div>
                    </div>

                    <div class="form-group">
                        <h2>Paso 2
                            <!--                        <small>(-->
                            <?php //echo $total_files ?><!-- archivos <b>.xml</b> en esta carpeta)</small>-->
                        </h2>
                        <p>Procesar los <b>.xml</b> para extraer su información <b>(UUID)</b></p>
                        <button type="button" id="process_xml" class="btn btn-primary btn-width">
                            <i class="fa fa-file-text-o" aria-hidden="true"></i>&nbsp;
                            Procesar Carpeta
                        </button>
                        <div id="resultados" class="response_result"></div>
                    </div>

                    <div class="form-group">
                        <h2>Paso 3</h2>
                        <p>Mandar la petición a DETECNO masivamente para cancelar mediante <b>UUID</b></p>
                        <button type="button" id="cancel_uuid" class="btn btn-danger btn-width">
                            <i class="fa fa-ban" aria-hidden="true"></i>
                            Cancelar
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div><!-- /page content -->


<?php include "includes/footer.php" ?>
<script type="text/javascript" src="js/utilities/cfdi-uuid-cancel.js"></script>
<script>
    $(document).on('click', '#process_xml', function (event) {

        event.preventDefault();


        $.ajax({
            type: "post",
            url: 'action/utilities/process-xml.php',
            processData: false,
            contentType: false,
            dataType: "json",
            beforeSend: function () {

                $('#process_xml').html('<i class="fa fa-spinner fa-spin  fa-fw"></i> Espere un momento...');

            },
            success: postCorrecto,
            error: postError,
            complete: postCompleto
        });

        function postCorrecto(datos) {

            // Swal.fire({
            //     type: 'success',
            //     title: 'Los anticipos han quedado Activos y se mostraran en el corte',
            //     showConfirmButton: false,
            //     timer: 3500
            // });

        }

        function postError(request, status, error) {
            console.log(request.responseText);
        }

        function postCompleto(datos) {

            $('#process_xml').html('<i class="fa fa-file-text-o" aria-hidden="true"></i>&nbsp; Procesar Carpeta');
            $("#resultados").html(datos.responseText);
            setTimeout(function () {
                $('#resultados').css("display", "none");

                $('.extra-progress-wrapper').hide();

            }, 4000);

        }


    });

</script>