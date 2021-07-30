$(document).ready(function () {
    load(1);
});

function load(page) {
    var q = $("#q").val();
    $("#loader").fadeIn('slow');
    $.ajax({
        url: './ajax/projects.php?action=ajax&page=' + page + '&q=' + q,
        beforeSend: function (objeto) {
            $('#loader').html('<img src="./images/ajax-loader.gif"> Cargando...');
        },
        success: function (data) {
            $(".outer_div").html(data).fadeIn('slow');
            $('#loader').html('');
        }
    })
}

$("#add").submit(function (event) {
    $('#save_data').attr("disabled", true);

    var parametros = $(this).serialize();
    $.ajax({
        type: "POST",
        url: "action/addproject.php",
        data: parametros,
        beforeSend: function () {
            $("#result").html("<img src='images/loading_1.gif' height='68px'>");
        },
        success: function (datos) {

            $("#result").fadeIn("slow");
            $("#result").html(datos);

            let error = datos.indexOf("alert-danger") > -1;
            // console.log(error);
            if (!error) {
                setTimeout(function () {
                    $("#add").trigger('reset');
                    $("#add-project").modal('hide');
                }, 5000);

                load(1);

            } else {
                $('#save_data').attr("disabled", false);
            }

            setTimeout(function () {
                $("#result").fadeOut("slow");
            }, 4000);

        }, error: function (jqXHR, exception) {
            var msg = '';
            if (jqXHR.status === 0) {
                msg = 'Not connect.\n Verify Network.';
            } else if (jqXHR.status == 404) {
                msg = 'Requested page not found. [404]';
            } else if (jqXHR.status == 500) {
                msg = 'Internal Server Error [500].';
            } else if (exception === 'parsererror') {
                msg = 'Requested JSON parse failed.';
            } else if (exception === 'timeout') {
                msg = 'Time out error.';
            } else if (exception === 'abort') {
                msg = 'Ajax request aborted.';
            } else {
                msg = 'Uncaught Error.\n' + jqXHR.responseText;
            }
            console.log(msg);
        }
    });
    event.preventDefault();
});

// success

$("#upd").submit(function (event) {
    $('#upd_data').attr("disabled", true);

    var parametros = $(this).serialize();
    $.ajax({
        type: "POST",
        url: "action/updproject.php",
        data: parametros,
        beforeSend: function () {
            $("#result2").html("<img src='images/loading_1.gif' height='68px'>");
        },
        success: function (datos) {

            $("#result2").fadeIn("slow");
            $("#result2").html(datos);

            let error = datos.indexOf("alert-danger") > -1;
            // console.log(error);
            if (!error) {
                setTimeout(function () {
                    $("#upd").trigger('reset');
                    $("#upd-project").modal('hide');
                }, 5000);


            } else {
                $('#upd_data').attr("disabled", false);
            }

            setTimeout(function () {
                $("#result2").fadeOut("slow");
            }, 4000);


        }
    });
    event.preventDefault();
});

function obtener_datos(id) {
    var description = $("#description" + id).val();
    var name = $("#name" + id).val();
    $("#mod_id").val(id);
    $("#mod_description").val(description);
    $("#mod_name").val(name);
}

function eliminar(id) {
    var q = $("#q").val();
    if (confirm("Realmente deseas eliminar el proyecto?")) {
        $.ajax({
            type: "GET",
            url: "./ajax/projects.php",
            data: "id=" + id, "q": q,
            beforeSend: function (objeto) {
                $("#resultados").html("<img src='images/loading_1.gif' height='68px'>");
            },
            success: function (datos) {
                $("#resultados").html(datos);
                load(1);
            }
        });
    }
}