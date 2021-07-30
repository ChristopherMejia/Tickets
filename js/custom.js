$("#frm_custom").submit(function (event) {

    $('#save_data').attr("disabled", true);

    var parametros = $(this).serialize();
    $.ajax({
        type: "POST",
        url: "action/save_custom.php",
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


