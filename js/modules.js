$(document).ready(function () {
    load(1);
});


function load(page) {
    var q = $("#q").val();
    var module = $("#module").val();


    $("#loader").fadeIn('slow');
    $.ajax({
        url: './ajax/fades.php?action=ajax&page=' + page + '&q=' + q + '&module=' + module,
        beforeSend: function (objeto) {
            $('#loader').html('<img src="./images/ajax-loader.gif"> Cargando...');
        },
        success: function (data) {
            $(".outer_div").html(data).fadeIn('slow');
            $('#loader').html('');

        }
    });
}

$("#add_module").submit(function (event) {
    $('#save_data').attr("disabled", true);

    var parametros = $(this).serialize();
    $.ajax({
        type: "POST",
        url: "action/add_module.php",
        data: parametros,
        beforeSend: function () {
            $("#result_module").html("<img src='images/loading_1.gif' height='68px'>");
        },
        success: function (datos) {

            $("#result_module").fadeIn();
            $("#result_module").html(datos);

            var error = datos.indexOf("alert-danger") > -1;
            // console.log(error);
            if (!error) {
                setTimeout(function () {
                    $("#result_module").fadeOut("slow");
                    $("#add_module").trigger('reset');
                    $('#save_data').attr("disabled", true);
                    $(".bs-example-modal-lg-add").modal('hide');

                }, 3000);
                setTimeout(function () {
                    $('#save_data').attr("disabled", false);
                    location.reload();
                }, 3100);
            } else {
                $('#save_data').attr("disabled", false);
                setTimeout(function () {
                    $("#result_module").fadeOut("slow");
                    load(1);
                }, 3000);
            }
        }
    });
    event.preventDefault();
});





