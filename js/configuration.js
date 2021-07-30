$(document).ready(function () {
    load(1);
});

function load(page) {
    var q = $("#q").val();
    $("#loader").fadeIn('slow');
    $.ajax({
        url: './ajax/logs.php?action=ajax&page=' + page + '&q=' + q,
        beforeSend: function (objeto) {
            $('#loader').html('<img src="./images/ajax-loader.gif"> Cargando...');
        },
        success: function (data) {
            $(".outer_div").html(data).fadeIn('slow');
            $('#loader').html('');

        }
    });
}


$("#frm_grls").submit(function (event) {

    $('#save_data').attr("disabled", true);

    // var parametros = $(this).serialize();
    var parametros = $("#frm_grls").serialize();
    $.ajax({
        type: "POST",
        url: "action/save_configuration.php",
        data: parametros,
        beforeSend: function () {
            $("#result").html("<img src='images/loading_1.gif' height='68px'>");
        },
        success: function (datos) {

            $("#result").fadeIn();
            $("#result").html(datos);

            var error = datos.indexOf("alert-danger") > -1;
            if (!error) {
                setTimeout(function () {
                    $("#result").fadeOut("slow");
                    $('#save_data').attr("disabled", true);
                }, 3000);
                setTimeout(function () {
                    $('#save_data').attr("disabled", false);
                }, 3100);
            } else {
                $('#save_data').attr("disabled", false);
            }
        }
    });
    event.preventDefault();
});


$("#frm_info_notices").submit(function (event) {

    // $('#save_info_notices').attr("disabled", true);

    var parametros = $("#frm_info_notices").serialize() + '&notices=' + 1;


    // alert(parametros);
    $.ajax({
        type: "POST",
        url: "action/save_configuration.php",
        data: parametros,
        beforeSend: function () {
            $("#result_info_notices").html("<img src='images/loading_1.gif' height='68px'>");
        },
        success: function (datos) {

            $("#result_info_notices").fadeIn();
            $("#result_info_notices").html(datos);

            var error = datos.indexOf("alert-danger") > -1;
            if (!error) {
                setTimeout(function () {
                    $("#result_info_notices").fadeOut("slow");
                    $('#save_info_notices').attr("disabled", true);
                }, 3000);
                setTimeout(function () {
                    $('#save_info_notices').attr("disabled", false);
                }, 3100);
            } else {
                $('#save_info_notices').attr("disabled", false);
            }
        }
    });
    event.preventDefault();
});


$('input[type=radio][name=groupNotices]').on('change', function () {

    var value = $(this).val();


    if (value == 1) {
        var status = 'publicar';
        var msg = 'Será visible para todo el público';
    } else {
        var status = 'Desactivar';
        var msg = 'Se ocultara para todo el público';
    }

    Swal.fire({
        title: '¿Está seguro que desea ' + status + ' el aviso?',
        html: '<p>' + msg + '</p>' +
            '<div id="display"></div>',
        type: 'question',
        showCancelButton: true,
        confirmButtonColor: '#d6972e',
        cancelButtonColor: '#c1c1c1',
        confirmButtonText: 'Sí, continuar'
    }).then((result) => {
        if (result.value) {
            $.ajax({
                type: "POST",
                url: "action/save_configuration.php",
                data: "active=" + value,
                beforeSend: function () {
                    $("#result").html("<img src='images/loading_1.gif' height='68px'>");
                },
                success: function (datos) {

                    var error = datos.indexOf("alert-danger") > -1;
                    if (!error) {


                        Swal.fire({
                            title: '¿Desea notificar a sus usuarios?',
                            text: "Se mandará un email con la información escrita.",
                            type: 'question',
                            showCancelButton: true,
                            confirmButtonColor: '#d6972e',
                            cancelButtonColor: '#c1c1c1',
                            confirmButtonText: '¡Sí, deseo notificar!',
                            allowOutsideClick: false
                        }).then((result) => {
                            if (result.value) {
                                $.ajax({
                                    type: "GET",
                                    url: "./ajax/notifyCustomers.php",
                                    data: "notify=yes",
                                    beforeSend: function (objeto) {

                                    },
                                    success: function (datos) {

                                        Swal.fire({
                                            type: 'success',
                                            title: "Enviando correos, espere un momento..",
                                            showConfirmButton: false,
                                            timer: 5000
                                        });
                                        setTimeout(function () {
                                            location = window.location;
                                        }, 5050);
                                    }
                                });
                            }

                        });


                    } else {

                        Swal.fire({
                            type: 'error',
                            title: "Ocurrio un error, vuelva a intentarlo",
                            showConfirmButton: false,
                            timer: 1500
                        });

                    }
                }
            });

        }


    });
});



