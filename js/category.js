$(document).ready(function () {
    load(1);
});

function load(page) {
    var q = $("#q").val();
    $("#loader").fadeIn('slow');
    $.ajax({
        url: './ajax/categories.php?action=ajax&page=' + page + '&q=' + q,
        beforeSend: function () {
            $('#loader').html('<img src="./images/ajax-loader.gif"> Cargando...');
        },
        success: function (data) {
            $(".outer_div").html(data).fadeIn('slow');
            $('#loader').html('');

        }
    })
}


function eliminar(id) {
    var q = $("#q").val();


    Swal.fire({
        title: '¿Estás seguro de deseas eliminar la categoría?',
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
                url: "./ajax/categories.php",
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

function obtener_datos(id) {

    $('#upd_data').attr("disabled", false);
    var name = $("#name" + id).val();
    $("#mod_id").val(id);
    $("#mod_name").val(name);
}


$("#add").submit(function (event) {
    $('#save_data').attr("disabled", true);

    var parametros = $(this).serialize();
    $.ajax({
        type: "POST",
        url: "action/addcategory.php",
        data: parametros,
        beforeSend: function () {
            $("#result_category").html("<img src='images/loading_1.gif' height='68px'>");
        },
        success: function (datos) {

            $("#result_category").html(datos);


            var error = datos.indexOf("alert-danger") > -1;
            // console.log(error);
            if (!error) {
                setTimeout(function () {
                    $("#result_category").fadeOut("slow");
                    $("#add").trigger('reset');
                    $('#save_data').attr("disabled", true);
                    $(".bs-example-modal-lg-new").modal('hide');
                }, 5000);
            } else {
                $('#save_data').attr("disabled", false);
                setTimeout(function () {
                    $("#result_category").fadeOut("slow");
                    load(1);
                }, 5000);
            }


        }
    });
    event.preventDefault();
});


$("#upd").submit(function (event) {
    $('#upd_data').attr("disabled", true);

    var parametros = $(this).serialize();
    $.ajax({
        type: "POST",
        url: "action/updcategory.php",
        data: parametros,
        beforeSend: function () {
            $("#upd_result_category").html("<img src='images/loading_1.gif' height='68px'>");
        },
        success: function (datos) {

            $("#upd_result_category").html(datos);

            var error = datos.indexOf("alert-danger") > -1;
            // console.log(error);
            if (!error) {
                load(1);
                setTimeout(function () {
                    $("#upd_result_category").fadeOut("slow");
                    $("#upd").trigger('reset');
                    $('#upd_data').attr("disabled", true);
                    $(".bs-example-modal-lg-udp").modal('hide');

                }, 5000);
            } else {
                $('#upd_data').attr("disabled", false);
                setTimeout(function () {
                    $("#upd_result_category").fadeOut("slow");
                    load(1);
                }, 5000);
            }


        }
    });
    event.preventDefault();
});

