
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
    var company_id = this.value;
    var parametros = new FormData();
    parametros.append("company_id", company_id);
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