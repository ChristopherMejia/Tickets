$(document).ready(function () {

    // load(1);
    load_detail(1);
});


function load_detail(page) {

    var file = $("#file").val();
    var search_word = $("#search_word_detail").val();
    var search_for = $("#search_for_detail").val();
    var user_id = $("#user_id").val();
    var entries = $("#entries_detail").val();

    $("#loader").fadeIn('slow');
    $.ajax({
        url: './ajax/fade-detail.php?action=ajax&page=' + page + '&search_word=' + search_word + '&search_for=' + search_for + '&user_id=' + user_id + '&entries=' + entries + '&file=' + file,
        beforeSend: function () {
            $('#loader').html('<img src="./images/ajax-loader.gif"> Cargando...');
        },
        success: function (data) {
            $(".outer_div_detail").html(data).fadeIn('slow');
            $('#loader').html('');
        }
    });


}

$("#add_fade").submit(function (event) {
    $('#save_data').attr("disabled", true);
    var page = $("#page").val();

    var parametros = $(this).serialize();
    $.ajax({
        type: "POST",
        url: "action/add_fade.php",
        data: parametros,
        beforeSend: function () {
            $("#result_fade").html("<img src='images/loading_1.gif' height='68px'>");
        },
        success: function (datos) {

            $("#result_fade").fadeIn();
            $("#result_fade").html(datos);

            var error = datos.indexOf("alert-danger") > -1;
            // console.log(error);
            if (!error) {
                setTimeout(function () {
                    $("#result_fade").fadeOut("slow");
                    $("#add_fade").trigger('reset');
                    $('#save_data').attr("disabled", true);
                    $("#new_fade_modal").modal('hide');

                }, 3000);

                if (page == 'tickets') {
                    var value = $("#module_id_f").val();
                    filterFades(value);

                } else if (page == 'modules') {

                    setTimeout(function () {
                        $('#save_data').attr("disabled", false);
                        load(1);
                    }, 3100);

                } else {

                    var value = $("#mod_module_f").val();
                    filterFades(value);
                }


            } else {
                $('#save_data').attr("disabled", false);
                setTimeout(function () {
                    $("#result_fade").fadeOut("slow");
                    load(1);
                }, 3000);
            }
        }
    });
    event.preventDefault();
});



