$("#forgot_pass").submit(function (event) {
    $('#save_data').attr("disabled", true);

    var username = $("#username").val();

    $.ajax({
        type: "POST",
        url: "action/password.php",
        data: "action=forgot&u=" + username,
        beforeSend: function () {
            $("#result_forgot").html("<img src='images/loading_1.gif' height='68px'>");
        },
        success: function (datos) {

            $("#result_forgot").html(datos);

            var error = datos.indexOf("alert-danger") > -1;
            // console.log(error);
            if (!error) {
                setTimeout(function () {
                    $("#result_forgot").fadeOut("slow");
                    $("#forgot_pass").trigger('reset');
                    $('#save_data').attr("disabled", true);

                }, 5000);
            } else {
                $('#save_data').attr("disabled", false);
                $('#username').focus();
                setTimeout(function () {
                    $("#result_forgot").fadeOut("slow");

                }, 5000);
            }


        }
    });
    event.preventDefault();
});

$("#reset_pass").submit(function (event) {
    $('#save_data').attr("disabled", true);

    var pass1 = $("#pass1").val();
    var pass2 = $("#pass2").val();
    var token = $("#token").val();

    $.ajax({
        type: "POST",
        url: "action/password.php",
        data: "action=reset&p1=" + pass1 + "&p2=" + pass2 + "&t=" + token,
        beforeSend: function () {
            $("#result_reset").html("<img src='images/loading_1.gif' height='68px'>");
        },
        success: function (datos) {

            $("#result_reset").html(datos);

            var error = datos.indexOf("alert-danger") > -1;
            // console.log(error);
            if (!error) {
                setTimeout(function () {
                    $("#result_reset").fadeOut("slow");
                    $("#reset_pass").trigger('reset');
                    $('#save_data').attr("disabled", true);
                    window.location.href = "http://soporte.intesystem.net/";



                }, 5000);
            } else {
                $('#save_data').attr("disabled", false);
                $('#username').focus();
                setTimeout(function () {
                    $("#result_reset").fadeOut("slow");

                }, 5000);
            }


        }
    });
    event.preventDefault();
});




