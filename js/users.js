$(document).ready(function () {
    load(1);
});


function obtener_datos(id) {
    var name = $("#name" + id).val();
    var email = $("#email" + id).val();
    var rfc = $("#rfc" + id).val();
    var rol = $("#rol" + id).val();
    var dpto = $("#dpto" + id).val();

    $("#mod_id").val(id);
    $("#mod_name").val(name);
    $("#mod_email").val(email);
    $("#mod_rfc").val(rfc);
    $("#mod_rol").val(rol);
    $("#mod_dpto").val(dpto);


}

function load(page) {
    var q = $("#q").val();
    $("#loader").fadeIn('slow');
    $.ajax({
        url: './ajax/users.php?action=ajax&page=' + page + '&q=' + q,
        beforeSend: function (objeto) {
            $('#loader').html('<img src="./images/ajax-loader.gif"> Cargando...');
        },
        success: function (data) {
            $(".outer_div").html(data).fadeIn('slow');
            $('#loader').html('');

        }
    });
}

function eliminar(id) {
    var q = $("#q").val();

    Swal.fire({
        title: '¿Estás seguro de deseas eliminar el usuario?',
        text: "¡No podrás revertir esto!",
        type: 'question',
        showCancelButton: true,
        confirmButtonColor: '#d6972e',
        cancelButtonColor: '#c1c1c1',
        confirmButtonText: '¡Sí, continuar!'
    }).then((result) => {
        if (result.value) {

            $.ajax({
                type: "GET",
                url: "./ajax/users.php",
                data: "id=" + id, "q": q,
                beforeSend: function (objeto) {
                    $("#resultados").html("<img src='images/loading_1.gif' height='68px'>");
                },
                success: function (datos) {
                    $("#resultados").html(datos);
                    load(1);

                    setTimeout(function () {
                        $("#resultados").fadeOut("slow");
                    }, 4000);


                }
            });

        }
    });

}

$("#add_user").submit(function (event) {
    $('#save_data').attr("disabled", true);

    var parametros = $(this).serialize();
    $.ajax({
        type: "POST",
        url: "action/add_user.php",
        data: parametros,
        beforeSend: function () {
            $("#result_user").html("<img src='images/loading_1.gif' height='68px'>");
        },
        success: function (datos) {

            $("#result_user").fadeIn();
            $("#result_user").html(datos);

            var error = datos.indexOf("alert-danger") > -1;
            // console.log(error);
            if (!error) {
                setTimeout(function () {
                    $("#result_user").fadeOut("slow");
                    $("#add_user").trigger('reset');
                    $('#save_data').attr("disabled", true);
                    $(".bs-example-modal-lg-add").modal('hide');
                    load(1);
                }, 3000);
                setTimeout(function () {
                    $('#save_data').attr("disabled", false);
                }, 3100);
            } else {
                $('#save_data').attr("disabled", false);
                setTimeout(function () {
                    $("#result_user").fadeOut("slow");
                    // load(1);
                }, 3000);
            }
        }
    });
    event.preventDefault();
});

$("#upd_user").submit(function (event) {
    $('#upd_data').attr("disabled", true);

    var parametros = $(this).serialize();
    $.ajax({
        type: "POST",
        url: "action/upd_user.php",
        data: parametros,
        beforeSend: function (objeto) {
            $("#upd_result_user").html("<img src='images/loading_1.gif' height='68px'>");
        },
        success: function (datos) {
            $("#upd_result_user").html(datos);


            var error = datos.indexOf("alert-danger") > -1;
            // console.log(error);
            if (!error) {
                load(1);
                setTimeout(function () {
                    $("#upd_result_user").fadeOut("slow");
                    $("#upd_user").trigger('reset');
                    $('#upd_data').attr("disabled", true);
                    $(".bs-example-modal-lg-upd").modal('hide');
                }, 3000);
                setTimeout(function () {
                    $('#upd_data').attr("disabled", false);
                }, 3100);
            } else {
                $('#upd_data').attr("disabled", false);
                setTimeout(function () {
                    $("#upd_result_user").fadeOut("slow");
                    load(1);
                }, 3000);
            }
        }
    });
    event.preventDefault();
});


		

