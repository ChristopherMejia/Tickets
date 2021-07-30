

function eliminar(id) {
    var q = $("#q").val();

    Swal.fire({
        title: '¿Estás seguro de eliminar el ticket?',
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
                url: "./ajax/tickets.php",
                data: "id=" + id, "q": q,
                beforeSend: function (objeto) {
                    $("#resultados").html("<img src='images/loading_1.gif' height='68px'>");
                },
                success: function (datos) {
                    $("#resultados").html(datos);
                    load(1);
                },
                complete: function () {

                    setTimeout(function () {
                        $("#resultados").fadeOut("slow");
                    }, 4000);

                }
            });

        }
    });

}


/* --------------------------------------- */
/* -------------------EVENTS-------------- */
/* --------------------------------------- */
$("#frm_note").submit(function (event) {
    event.preventDefault();
    $('#save_note').attr("disabled", true);

    var parametros = $(this).serialize();


    $.ajax({
        type: "POST",
        url: "action/addnote.php",
        data: parametros,
        beforeSend: function (objeto) {
            $("#result_note").html("<img src='images/loading_1.gif' height='68px'>");
        },
        success: function (datos) {
            $("#result_note").html(datos);
            $('#save_note').attr("disabled", false);

            setTimeout(function () {
                // $("#add-ticket")[0].reset();
                $("#add").trigger('reset');
                $("#result_note").fadeOut("slow");
            }, 4000);

            setTimeout(function () {
                $("#add-ticket").modal('hide');
            }, 5000);

            load(1);
        }
    });

});

