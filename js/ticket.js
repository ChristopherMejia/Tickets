// $.getScript('/js/notes.js');

$(document).ready(function () {
    load(1);
    load_pending(1);
    load_all(1);
    load_testing(1);
});

function load(page) {
    // var search_word = $("#search_word").val();
    var search_for = $("#search_for").val();
    var search_who = $("#search_who").val();
    var user_id = $("#user_id").val();
    var entries = $("#entries").val();
    var module = $("#module").val();

    $("#loader").fadeIn('slow');
    $.ajax({
        url: './ajax/tickets.php?action=ajax&page=' + page + '&search_for=' + search_for + '&search_who=' + search_who + '&module=' + module + '&user_id=' + user_id + '&entries=' + entries,
        beforeSend: function () {
            $('#loader').html('<img src="./images/ajax-loader.gif"> Cargando...');
        },
        success: function (data) {
            $(".outer_div").html(data).fadeIn('slow');
            $('#loader').html('');
        }
    });
}

function load_pending(page) {
    var search_word = $("#search_word_pending").val();
    var search_for = $("#search_for_pending").val();
    var search_who = $("#search_who_pending").val();
    var user_id = $("#user_id").val();
    var entries = $("#entries_pending").val();
    var module = $("#module_pending").val();

    $("#loader").fadeIn('slow');
    $.ajax({
        url: './ajax/tickets-pending.php?action=ajax&page=' + page + '&search_word=' + search_word + '&search_who=' + search_who + '&module=' + module + '&search_for=' + search_for + '&user_id=' + user_id + '&entries=' + entries,
        beforeSend: function () {
            $('#loader').html('<img src="./images/ajax-loader.gif"> Cargando...');
        },
        success: function (data) {
            $(".outer_div_m").html(data).fadeIn('slow');
            $('#loader').html('');
        }
    });
}

function load_all(page) {
    var search_word = $("#search_word_all").val();
    var search_for = $("#search_for_all").val();
    var search_who = $("#search_who_all").val();
    var user_id = $("#user_id").val();
    var entries = $("#entries_all").val();
    var module = $("#module_all").val();

    $("#loader").fadeIn('slow');
    $.ajax({
        url: './ajax/tickets-all.php?action=ajax&page=' + page + '&search_word=' + search_word + '&search_who=' + search_who + '&search_for=' + search_for + '&module=' + module + '&user_id=' + user_id + '&entries=' + entries,
        beforeSend: function () {
            $('#loader').html('<img src="./images/ajax-loader.gif"> Cargando...');
        },
        success: function (data) {
            $(".outer_div_a").html(data).fadeIn('slow');
            $('#loader').html('');
        }
    });
}

function load_testing(page) {
    var search_word = $("#search_word_testing").val();
    var search_for = $("#search_for_testing").val();
    var search_who = $("#search_who_testing").val();
    var user_id = $("#user_id").val();
    var entries = $("#entries_testing").val();
    var module = $("#module_testing").val();

    $("#loader").fadeIn('slow');

    $.ajax({
        url: './ajax/tickets-testing.php?action=ajax&page=' + page + '&search_word=' + search_word + '&search_who=' + search_who + '&module=' + module + '&search_for=' + search_for + '&user_id=' + user_id + '&entries=' + entries,
        beforeSend: function () {
            $('#loader').html('<img src="./images/ajax-loader.gif"> Cargando...');
        },
        success: function (data) {
            $(".outer_div_t").html(data).fadeIn('slow');
            $('#loader').html('');
        }
    });
}

function getDataTicket(id) {

    var status_ticket = $('#status_ticket').val();
    if (status_ticket == 3 || status_ticket == 4) {

        Swal.fire({
            type: 'error',
            title: 'Ticket cerrado o cancelado',
            showConfirmButton: false,
            timer: 1500
        });
        return false;
    } else {


        // $('#upd-ticket').modal('show');
        $('#upd-ticket').modal();
        // $(".assigned").prop('disabled', true);


        var kind_id = $("#mod_kind_id-s").val();
        var title = $("#mod_title-s").val();
        var description = $("#mod_description-s").val();
        var project_id = $("#mod_project_id-s").val();
        var category_id = $("#mod_category_id-s").val();
        var priority_id = $("#mod_priority_id-s").val();
        var status_id = $("#mod_status_id-s").val();
        var dpto_id = $("#mod_dpto_id-s").val();
        var assigned_id = $("#mod_assigned_id-s").val();
        var company = $("#mod_company-s").val();
        var role_id = $("#mod_role_id").val();

        if (kind_id == 2) {
            $(".kinds").prop('disabled', false);
            $("#fade").css("display", "block");
            var module_f = $('#module_f').val();
            var name_f = $('#name_f').val();
            var order_number_f = $('#order_number_f').val();

            // alert(module_f);
            // $('#mod_name_f').val(name_f);
            // $('#mod_module_f').val(module_f);
            // $('#mod_module_f').val(module_f).change();
        }


        //Estatus 5 = Pruebas && Role 3 Developer
        if (status_id == 5 && role_id == 3) {

            $("#mod_status_id").prop('disabled', true);
            $("#btn_show_detail").prop('disabled', true);
        }


        $("#mod_company_id").val(company);
        $("#mod_id").val(id);
        $("#mod_title").val(title);

        // $("#mod_description").val(description);
        // tinyMCE.activeEditor.setContent(description);
        $("#mod_description").val(description);

        $("#mod_kind_id").val(kind_id);
        $("#mod_project_id").val(project_id);
        $("#mod_category_id").val(category_id);
        $("#mod_priority_id").val(priority_id);
        $("#mod_status_id").val(status_id);
        $("#mod_dpto_id").val(dpto_id);
        $("#mod_asigned_id").val(assigned_id);


        filterUsers(dpto_id);

        setTimeout(function () {

            $('#mod_asigned_id').val(assigned_id).change();

            if (assigned_id != '') {
                $(".assigned").prop('disabled', false);
            }
        }, 1000);

    }
}

function reactivateTicket() {


    var ticket = $('#ticket_id').val();
    var user_id = $('#user_id').val();
    var parametros = new FormData();

    parametros.append("ticket", ticket);
    parametros.append("user_id", user_id);
    parametros.append("reactivate", "1");

    Swal.fire({
        title: '¿Estás seguro de reactivar el ticket?',
        text: "¡Los usuarios involucrados podran acceder a el!",
        type: 'question',
        showCancelButton: true,
        confirmButtonColor: '#d6972e',
        cancelButtonColor: '#c1c1c1',
        confirmButtonText: '¡Sí, continuar!'
    }).then((result) => {
        if (result.value) {

            $.ajax({
                type: "POST",
                url: "./action/updticket.php",
                data: parametros,
                contentType: false,
                cache: false,
                processData: false,
                success: function (datos) {

                    Swal.fire({
                        type: 'success',
                        title: 'Ticket Reactivado',
                        showConfirmButton: false,
                        timer: 2000
                    });

                    location.reload();


                }
            });

        }
    });
}

function filterUsers(id_dpto) {

    $("#loader").fadeIn('slow');
    $.ajax({
        type: "POST",
        url: "ajax/departments.php",
        data: "id_dpto=" + id_dpto,
        success: function (datos) {
            // alert(datos);
            $(".assigned").html(datos);
            $(".assigned").prop('disabled', false);
        }
    });
}

function filterKinds(id_kind) {

    if (id_kind == 2) {
        $(".kinds").prop('disabled', false);
        $("#fade").css("display", "block");
    } else {
        $(".kinds").prop('disabled', true);
        $("#fade").css("display", "none");
    }


}

function validateKinds(kind_id) {
    if (kind_id == 2) {
        Swal.fire({
            type: 'info',
            title: 'Recuerda que no puedes regresar un Fade a Ticket',
            showConfirmButton: false,
            timer: 2500
        });
        $("#mod_kind_id option:contains('Ticket')").attr("disabled", "disabled");
    }
}

function filter(page) {
    var q = $("#status_ticket").val();
    var user_id = $("#user_id").val();

    $("#loader").fadeIn('slow');
    $.ajax({
        url: './ajax/tickets.php?action=ajax&filter=1&page=' + page + '&q=' + q + '&user_id=' + user_id,
        beforeSend: function () {
            $('#loader').html('<img src="./images/ajax-loader.gif"> Cargando...');
        },
        success: function (data) {
            $(".outer_div").html(data).fadeIn('slow');
            $('#loader').html('');
        }
    });
}

function filterm(page) {
    var q = $("#status_ticketm").val();
    var user_id = $("#user_id").val();

    $("#loader").fadeIn('slow');
    $.ajax({
        url: './ajax/tickets-pending.php?action=ajax&filter=1&page=' + page + '&q=' + q + '&user_id=' + user_id,
        beforeSend: function () {
            $('#loader').html('<img src="./images/ajax-loader.gif"> Cargando...');
        },
        success: function (data) {
            $(".outer_div_m").html(data).fadeIn('slow');
            $('#loader').html('');
        }
    });
}

function filtera(page) {
    var q = $("#status_ticketa").val();
    var user_id = $("#user_id").val();


    $("#loader").fadeIn('slow');
    $.ajax({
        url: './ajax/tickets-all.php?action=ajax&filter=1&page=' + page + '&q=' + q + '&user_id=' + user_id,
        beforeSend: function () {
            $('#loader').html('<img src="./images/ajax-loader.gif"> Cargando...');
        },
        success: function (data) {
            $(".outer_div_a").html(data).fadeIn('slow');
            $('#loader').html('');
        }
    });
}

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

function eliminar_img(id, permit) {

    // alert(permit +" "+ id);

    if (permit) {
        Swal.fire({
            title: '¿Estás seguro de eliminar el archivo?',
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
                    url: "./ajax/uploads.php",
                    data: "id=" + id,
                    beforeSend: function (objeto) {
                        $("#result_files").html("<img src='images/loading_1.gif' height='68px'>");
                    },
                    success: function (datos) {
                        $("#result_files").html(datos);
                        showFiles();
                    },
                    complete: function () {

                        setTimeout(function () {
                            $("#result_files").fadeOut("slow");
                        }, 4000);

                    }
                });

            }
        });
    } else {


        Swal.fire({

            type: 'error',
            title: 'No eres propietario de este adjunto',
            showConfirmButton: false,
            timer: 1500
        });

    }

}

function comentar_img(filename, ticket_id) {

    $('#write_msg').val(' [[' + filename + ']]');
    // $('#write_msg').html(txt + ' <a href="public/attach/' + name + '" data-fancybox="images">[[ ' + name + ' ]] </a> ');
    // $('#write_msg_send').html(' <a href="public/attach/' + ticket_id + '/' + filename + '" data-fancybox="images">[[ ' + filename + ' ]] </a> ');

}

function showFiles() {

    var id_ticket = $('#comment_ticket').val();
    var user_id = $('#user_id').val();

    $.ajax({
        type: "POST",
        url: "ajax/uploads.php",
        data: "id_ticket=" + id_ticket + "&user_id=" + user_id,
        beforeSend: function (objeto) {
            $("#result-data").html("<img src='images/loading_1.gif' height='68px'>");
        },
        success: function (datos) {
            $("#result_files").html(datos);
            // load(1);
        }

    });
}

function showComment() {

    var id_ticket = $('#comment_ticket').val();
    var user_id = $('#user_id').val();

    $.ajax({
        type: "POST",
        url: "./ajax/comments.php",
        data: "id_ticket=" + id_ticket + "&user_id=" + user_id,
        success: function (datos) {
            $("#msg_history").html(datos);
            load(1);
        }
    });
}

function showNotes() {
    var id_ticket = $('#comment_ticket').val();
    var user_id = $('#user_id').val();
    $.ajax({
        type: "POST",
        url: "./ajax/notes.php",
        data: "id_ticket=" + id_ticket + "&user_id=" + user_id,
        success: function (datos) {
            $("#notes").html(datos);
            load(1);
        }
    });
}

function showInfoTicket() {
    var id_ticket = $('#comment_ticket').val();
    var user_id = $("#user_id").val();
    $.ajax({
        type: "POST",
        url: "./ajax/ticket.php",
        data: "id_ticket=" + id_ticket + "&user_id=" + user_id,
        success: function (datos) {
            $("#result_ticket").html(datos);
            load(1);
        }
    });
}

function verifyStatus(status_id, ticket_id, user_id) {


    if (status_id == 3) {
        Swal.fire({
            title: "¿Desea notificar el cierre del ticket al cliente?",
            text: "Agregue una nota si lo desea",
            type: 'question',
            input: 'textarea',
            showCancelButton: true,
            confirmButtonColor: '#d6972e',
            cancelButtonColor: '#c1c1c1',
            confirmButtonText: '¡Sí, enviar!',
            closeOnConfirm: false,
            allowOutsideClick: false,
            // showLoaderOnConfirm: true,
            // animation: "slide-from-top",
            inputPlaceholder: "Escribe algo..."
        }).then((result) => {

            if (result.value) {
                $.ajax({
                    type: "GET",
                    url: "./action/send_mail.php",
                    data: "msg=" + result.value + "&ticket_id=" + ticket_id + "&user_id=" + user_id,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function () {
                        Swal.fire({
                            type: 'success',
                            title: '¡Correo enviado!',
                            showConfirmButton: false,
                            timer: 2500
                        });

                        showComment();
                    }
                });
            } else {
                // load(1);
            }

        });
    } else if (status_id == 4) {
        Swal.fire({
            title: "¿Desea notificar la cancelación del ticket al cliente?",
            text: "Agregue una nota si lo desea",
            type: 'question',
            input: 'textarea',
            showCancelButton: true,
            confirmButtonColor: '#d6972e',
            cancelButtonColor: '#c1c1c1',
            confirmButtonText: '¡Sí, enviar!',
            closeOnConfirm: false,
            allowOutsideClick: false,
            showLoaderOnConfirm: true,
            animation: "slide-from-top",
            inputPlaceholder: "Escribe algo..."
        }).then((result) => {

            if (result.value) {
                $.ajax({
                    type: "GET",
                    url: "./action/send_mail.php",
                    data: "msg=" + result.value + "&ticket_id=" + ticket_id + "&user_id=" + user_id,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function () {
                        Swal.fire({
                            type: 'success',
                            title: '¡Correo enviado!',
                            showConfirmButton: false,
                            timer: 2500
                        });

                        showComment();
                    }
                });
            }
        });
    }

    // scheduleInstallation(status_id, ticket_id, user_id);


}

function filterFades(id_module) {

    $("#loader").fadeIn('slow');
    $.ajax({
        type: "POST",
        url: "ajax/fades_sel.php",
        data: "id_module=" + id_module,
        success: function (datos) {
            // alert(datos);
            $(".fades").html(datos);
            $(".fades").prop('disabled', false);
            $("#add_fade_fast").prop('disabled', false);
        }
    });
}

function scheduleInstallation(status_id, ticket_id, user_id) {


    if (status_id == 3) {
        Swal.fire({
            title: "¿Desea agendar instalación?",
            text: "Los programas afectados pasaran a la lista de pendientes.",
            type: 'question',
            showCancelButton: true,
            confirmButtonColor: '#d6972e',
            cancelButtonColor: '#c1c1c1',
            confirmButtonText: '¡Sí, enviar!',
            closeOnConfirm: false,
            allowOutsideClick: false,
            showLoaderOnConfirm: true,
            animation: "slide-from-top",
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: "GET",
                    url: "./action/addPendingToInstall.php",
                    data: "ticket_id=" + ticket_id + "&user_id=" + user_id,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function () {
                        Swal.fire({
                            type: 'success',
                            title: '¡Agregado correctamente!',
                            showConfirmButton: false,
                            timer: 2500
                        });

                        showComment();
                    }
                });
            }
        });
    }


}


/* --------------------------------------- */
/* -------------------EVENTS-------------- */

/* --------------------------------------- */

function tackeTicket(force = false) {

    var ticket = $('#ticket_id').val();
    var user_id = $('#user_id').val();
    var comment = "Este ticket ya esta siendo evaluado por otro usuario, si decide continuar se le autoasignara";

    var parametros = new FormData();
    parametros.append("ticket", ticket);
    parametros.append("user_id", user_id);
    parametros.append("testing", "1");


    if (force) {

        Swal.fire({
            title: '¿Estás seguro de asignarse el ticket para pruebas?',
            html: "'" + comment + "'",
            type: 'question',
            showCancelButton: true,
            confirmButtonColor: '#d6972e',
            cancelButtonColor: '#c1c1c1',
            confirmButtonText: '¡Sí, continuar!'
        }).then((result) => {
            if (result.value) {

                $.ajax({
                    type: "POST",
                    url: "./action/updticket.php",
                    data: parametros,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (datos) {

                        Swal.fire({
                            type: 'success',
                            title: 'Se ha autoasignado el ticket',
                            showConfirmButton: false,
                            timer: 1500
                        });


                        $('#btn_attendTicket').prop("disabled", true);
                        $('#btn_attendTicket').addClass('btn-default');


                        $("#btn_attendTicket").html('<i class="fa fa-pause" aria-hidden="true"></i> En Pruebas'); //versions newer than 1.6


                    },
                    complete: function () {
                        setTimeout(function () {
                            showInfoTicket();
                        }, 3000);
                    }
                });

            }
        });


    } else {


        $.ajax({
            type: "POST",
            url: "./action/updticket.php",
            data: parametros,
            contentType: false,
            cache: false,
            processData: false,
            success: function (datos) {

                Swal.fire({
                    type: 'success',
                    title: 'Se ha autoasignado el ticket',
                    showConfirmButton: false,
                    timer: 1500
                });


                $('#btn_attendTicket').prop("disabled", true);
                $('#btn_attendTicket').addClass('btn-default');


                $("#btn_attendTicket").html('<i class="fa fa-pause" aria-hidden="true"></i> En Pruebas'); //versions newer than 1.6


            },
            complete: function () {

                setTimeout(function () {

                    // $("#result2").fadeOut("slow");

                }, 3000);


            }
        });
    }

}

function edValueKeyPress() {
    // var edValue = document.getElementById("write_msg");
    // var s = edValue.value;
    //
    // var lblValue = document.getElementById("write_msg_send");
    // lblValue.innerText = s;
}

$("#files").change(function () {

    var fileExtension = ['jpg', 'png', 'jpeg', 'gif', 'pdf', 'xlsx', 'xls', 'xml', 'csv', 'docx', 'doc', 'xml', 'log', 'JPG', 'PNG', 'JPEG', 'GIF', 'PDF', 'XLSX', 'XLS', 'CSV', 'DOCX', 'DOC', 'XML', 'LOG'];
    if ($.inArray($(this).val().split('.').pop().toLowerCase(), fileExtension) == -1) {
        // alert("Only '.jpeg','.jpg' formats are allowed.");
        Swal.fire({
            type: 'error',
            title: 'Un archivo tiene un formato de archivo diferente a las especificadas.',
            showConfirmButton: false,
            timer: 3500
        });
        $('.fileupload').val('');
    } else {


        $('#upload-files').submit();


        // $('#write_msg').val(this.files && this.files.length ? this.files[0].name: '');


        var name = $('#files').val().split('\\').pop();
        // name=name.split('.')[0];
        var txt = $('#write_msg').val();
        var ticket_id = $('#comment_ticket').val();


        // $('#write_msg').val(txt + ' [[' + name + ']]');
        // $('#write_msg').html(txt + ' <a href="public/attach/' + name + '" data-fancybox="images">[[ ' + name + ' ]] </a> ');
        // $('#write_msg_send').html(txt + ' <a href="public/attach/' + ticket_id + '/' + name + '" data-fancybox="images">[[ ' + name + ' ]] </a> ');


        var arrayImg = $('#imagesComment').val();
        $('#imagesComment').val(arrayImg + name + '|');

    }
});

$("#upload-files").submit(function (event) {

    $.ajax({
        type: "POST",
        url: "./action/addfiles.php",
        data: new FormData(this),
        contentType: false,
        cache: false,
        processData: false,
        beforeSend: function (objeto) {
            $("#result_files").html("<img src='images/loading_1.gif' height='68px'>");
        },
        success: function (datos) {
            // $("#result_files").html(datos);
            showFiles();
            var response = JSON.parse(datos);

            if (response.success == 0) {
                Swal.fire({
                    type: 'error',
                    html: '<h4>Hubo un problema con la carga</h4><br>' +
                        '<p>Recuerde que la extención del archivo este dentro de las permitidas.</p>',
                    showConfirmButton: false,
                    timer: 4500
                });
            } else {

                var name = response.filename;
                // name=name.split('.')[0];
                var txt = $('#write_msg').val();
                var ticket_id = $('#comment_ticket').val();


                $('#write_msg').val(txt + ' [[' + name + ']]');
                // $('#write_msg').html(txt + ' <a href="public/attach/' + name + '" data-fancybox="images">[[ ' + name + ' ]] </a> ');
                // $('#write_msg_send').html(txt + ' <a href="public/attach/' + ticket_id + '/' + name + '" data-fancybox="images">[[ ' + name + ' ]] </a> ');


                var arrayImg = $('#imagesComment').val();
                $('#imagesComment').val(arrayImg + name + '|');

            }


        }
    });

    event.preventDefault();
});

$('.attach-file').click(function () {

    var status_ticket = $('#status_ticket').val();
    if (status_ticket == 3 || status_ticket == 4) {

        Swal.fire({
            type: 'error',
            title: 'Ticket cerrado o cancelado',
            showConfirmButton: false,
            timer: 1500
        });
        return false;
    } else {

        $('#files').trigger('click');
    }
});

$("#frm-add-ticket").submit(function (event) {

    var kind_id = $("#kind_id").val();
    var dpto_id = $("#dpto_id").val();


    if (kind_id != 2 && dpto_id == 3) {

        Swal.fire({
            type: 'error',
            title: 'Debe Cambiar el tipo a FADE si desea pasar el ticket al departamento de desarrollo.',
            showConfirmButton: false,
            timer: 3500
        });
        $('#mod_kind_id').click();
        return false;
    } else {

        // event.preventDefault();
        $('#save_data').attr("disabled", true);
        var page = $('#page').val();
        // alert(new FormData(this));
        $.ajax({
            type: "POST",
            url: "./action/addticket.php",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function () {
                $("#result").html("<img src='images/loading_1.gif' height='68px'>");
            },
            success: function (datos) {

                // alert(datos);
                // $("#result").fadeIn("slow");
                // $("#result").html(datos);

                // console.log(error);
                if (datos != 0) {
                    // setTimeout(function () {
                    //     $(".kinds").prop('disabled', true);
                    //     $("#fade").css("display", "none");
                    //
                    //     $("#frm-add-ticket").trigger('reset');
                    //     $("#add-ticket").modal('hide');
                    // }, 3000);
                    // setTimeout(function () {
                    //     $('#save_data').attr("disabled", false);
                    // }, 3100);

                    // if (page == 'tickets') {
                    //     load(1);
                    //     $('#save_data').attr("disabled", true);
                    // } else {
                    //     location.reload();
                    // }


                    $("#step1").delay(50).fadeOut();
                    $("#footer_ticket").delay(50).fadeOut();
                    $("#step2").delay(100).fadeIn();
                    load(1);
                    $("#ticket_id").val(datos);

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
                    $('#save_data').attr("disabled", true);

                    setTimeout(function () {
                        $("#add-ticket").modal('hide');
                    }, 1500);

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
    }
    event.preventDefault();

});

$("#upd").submit(function (event) {

    if ($('#mod_kind_id').val() != 2 && $('#mod_dpto_id').val() == 3) {

        Swal.fire({
            type: 'error',
            title: 'Debe Cambiar el tipo a FADE si desea pasar el ticket al departamento de desarrollo.',
            showConfirmButton: false,
            timer: 3500
        });
        $('#mod_kind_id').click();
        return false;
    } else {

        $('#upd_data').attr("disabled", true);

        var programsArray = [];
        $('#program-list').each(function (indice, elemento) {

            var element = $(elemento).text().trim().replace(/ /g, "");

            programsArray.push(element);
            console.log('El elemento con el índice ' + indice + ' contiene ' + $(elemento).text());
        });


        $('#program-list-array').val(programsArray);

        $.ajax({
            type: "POST",
            url: "./action/updticket.php",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
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
                        $("#frm-upd-ticket").trigger('reset');
                        $("#upd-ticket").modal('hide');
                    }, 3000);

                    setTimeout(function () {
                        $("#upd-ticket").modal('hide');
                        showInfoTicket();
                    }, 1000);
                    setTimeout(function () {

                        var status_id = $('#mod_status_id-s').val();
                        var ticket_id = $('#ticket_id').val();
                        var user_id = $('#user_id').val();

                        verifyStatus(status_id, ticket_id, user_id);
                    }, 1200);


                } else {
                    $('#upd_data').attr("disabled", false);
                }

                setTimeout(function () {
                    $("#result2").fadeOut("slow");
                    $('#upd_data').attr("disabled", false);
                }, 4000);
            }, error: function (jqXHR, exception) {
                var msg = '';
                if (jqXHR.status === 0) {
                    msg = 'Not connect.\n Verify Network.';
                } else if (jqXHR.status == 404) {
                    msg = 'Requested page not found. [404]';
                } else if (jqXHR.status == 500) {

                    $('#upd_data').attr("disabled", true);

                    setTimeout(function () {
                        $("#upd-ticket").modal('hide');
                    }, 1500);

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
    }

    event.preventDefault();


});

/* Comments */
$("#add-comment").submit(function (event) {
    event.preventDefault();

    var status = $("#mod_status_id-s").val();
    var role = $("#role_user").val();
    var msg = $('#write_msg').val();


    if (msg != "") {
        if ((status == 3 || status == 4) && role >= 3) {

            Swal.fire({
                type: 'error',
                title: 'Ticket Cerrado.',
                showConfirmButton: false,
                timer: 3500
            });
        } else {


            var user = $('#comment_user').val();
            var ticket = $('#comment_ticket').val();
            var imagesComment = $('#imagesComment').val();
            var ccustomer = $('#ccustomer').val();
            var parametros = new FormData();


            parametros.append("msg", msg);
            parametros.append("imagesComment", imagesComment);
            parametros.append("user", user);
            parametros.append("ticket", ticket);
            parametros.append("ccustomer", ccustomer);

            $.ajax({
                type: "POST",
                url: "./action/addcomment.php",
                data: parametros,
                cache: false,
                processData: false,
                contentType: false,
                dataType: 'html',
                beforeSend: function (objeto) {
                    $("#result").html("<img src='images/loading_1.gif' height='68px'>");
                    $(".msg_send_btn").attr("disabled", true);
                    $("#write_msg").attr("disabled", true);
                },
                success: function (datos) {


                    $("#msg_history").html(datos);
                    $("#write_msg").val('');
                    // $("#write_msg_send").html('');
                    $("#imagesComment").val('');


                    $(".msg_send_btn").attr("disabled", false);
                    $("#write_msg").attr("disabled", false);
                    $("#write_msg").focus();
                    $('#chk_ccustomer').prop('checked', false);

                    load(1);
                }, error: function (jqXHR, exception) {
                    var msg = '';
                    if (jqXHR.status === 0) {
                        msg = 'Not connect.\n Verify Network.';
                    } else if (jqXHR.status == 404) {
                        msg = 'Requested page not found. [404]';
                    } else if (jqXHR.status == 500) {


                        setTimeout(function () {
                            location.reload();
                        }, 1500);

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
                },
                complete: function () {
                    $("#result").html("");
                }
            });

        }
    }
});
/* End comment */

$(document).ready(function () {

    showInfoTicket();
    showFiles();
    showComment();
    showNotes();


});


/* -----------------------------------------------------------------------------*/
/* --------------------------------------NOTES---------------------------------- */

/* ----------------------------------------------------------------------------- */


function eliminar_nota(id, title) {


    Swal.fire({
        title: '¿Estás seguro de eliminar la nota?',
        html: "'" + title + "'",
        type: 'question',
        showCancelButton: true,
        confirmButtonColor: '#d6972e',
        cancelButtonColor: '#c1c1c1',
        confirmButtonText: '¡Sí, continuar!'
    }).then((result) => {
        if (result.value) {


            $.ajax({
                type: "GET",
                url: "./ajax/notes.php",
                data: "id=" + id,
                beforeSend: function (objeto) {
                    $("#resultados_notas").html("<img src='images/loading_1.gif' height='68px'>");
                },
                success: function (datos) {
                    $("#resultados_notas").html(datos);
                    load(1);
                },
                complete: function () {

                    setTimeout(function () {
                        $("#resultados_notas").fadeOut("slow");
                        $('#note-' + id).css("display", "none");
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
        url: "./action/addnote.php",
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
                showNotes();
            }, 4000);

            setTimeout(function () {
                $("#add-ticket").modal('hide');

            }, 5000);

            load(1);
        }
    });

});


function setDataDetails() {

//GET
    var observations = $("#observations_r").val();
    var program = $("#program_name_r").val();
    var module = $("#module_id_r").val();


//SET
    $("#mod_observations").val(observations);
    $("#mod_program").val(program);
    $("#mod_module").val(module);


    // $('#detail-add').modal('hide');
    $("#details-ticket").css("display", "none");
}

function showDetails() {
    $("#details-ticket").css("display", "block");
    $("#btn_show_detail").css("display", "none");
    $("#btn_hidden_detail").css("display", "block");


    $("#module_id_r").prop("disabled", false);
    $("#program_name_r").prop("disabled", false);
    $("#observations_r").prop("disabled", false);


}

function hiddenDetails() {
    $("#details-ticket").css("display", "none");
    $("#btn_show_detail").css("display", "block");
    $("#btn_hidden_detail").css("display", "none");

    $("#module_id_r").prop("disabled", true);
    $("#program_name_r").prop("disabled", true);
    $("#observations_r").prop("disabled", true);
}

function checkStatus(value, role) {

    if (role == 3 && value == 5) {
        $('.dev').css('display', 'block');
        $('#typehead_program').focus();

    }
}


/* --------------------------------------- */
/* ------------Upload files Ticket---------- */
/* --------------------------------------- */

$('.fileupload').on("change", function () {

    var fileExtension = ['jpg', 'png', 'jpeg', 'gif', 'pdf', 'xlsx', 'xls', 'xml', 'csv', 'docx', 'doc', 'xml', 'log', 'JPG', 'PNG', 'JPEG', 'GIF', 'PDF', 'XLSX', 'XLS', 'CSV', 'DOCX', 'DOC', 'XML', 'LOG'];
    if ($.inArray($(this).val().split('.').pop().toLowerCase(), fileExtension) == -1) {
        // alert("Only '.jpeg','.jpg' formats are allowed.");
        Swal.fire({
            type: 'error',
            title: 'Un archivo tiene un formato de archivo diferente a las especificadas.',
            showConfirmButton: false,
            timer: 3500
        });
        $('.fileupload').val('');
    }

});

$('.attach-file-new').click(function () {

    var status_ticket = $('#status_ticket').val();
    if (status_ticket == 3 || status_ticket == 4) {

        Swal.fire({
            type: 'error',
            title: 'Ticket cerrado o cancelado',
            showConfirmButton: false,
            timer: 1500
        });
        return false;
    } else {

        $('#files-new').trigger('click');
    }
});

$("#files-new").change(function () {

    var fileExtension = ['jpg', 'png', 'jpeg', 'gif', 'pdf', 'xlsx', 'xls', 'xml', 'csv', 'docx', 'doc', 'xml', 'log', 'JPG', 'PNG', 'JPEG', 'GIF', 'PDF', 'XLSX', 'XLS', 'CSV', 'DOCX', 'DOC', 'XML', 'LOG'];
    if ($.inArray($(this).val().split('.').pop().toLowerCase(), fileExtension) == -1) {
        // alert("Only '.jpeg','.jpg' formats are allowed.");
        Swal.fire({
            type: 'error',
            title: 'Un archivo tiene un formato de archivo diferente a las especificadas.',
            showConfirmButton: false,
            timer: 3500
        });
        $('.fileupload').val('');
    } else {

        $('#uploadFiles').submit();
        // $('#upload-files').trigger('submit');

    }
});

$("#uploadFiles").submit(function (event) {

    $.ajax({
        type: "POST",
        url: "./action/addfiles.php",
        data: new FormData(this),
        contentType: false,
        cache: false,
        processData: false,
        beforeSend: function (objeto) {
            $("#result_files-preview").html("<img src='images/loading_1.gif' height='68px'>");
        },
        success: function (datos) {
            previewFiles();

        }
    });

    event.preventDefault();
});

function previewFiles() {

    var id_ticket = $('#ticket_id').val();
    var user_id = $('#user_id').val();

    $.ajax({
        type: "POST",
        url: "ajax/preview.php",
        data: "id_ticket=" + id_ticket + "&user_id=" + user_id,
        beforeSend: function (objeto) {
            $("#result-data").html("<img src='images/loading_1.gif' height='68px'>");
        },
        success: function (datos) {
            $("#result_files-preview").html(datos);
            // load(1);
        }

    });
}


$("#step2").bind('paste', function (event) {

    // console.log('paste event');
    var user_id = $('#user_id').val();
    var ticket_id = $('#ticket_id').val();

    // console.log(user_id);
    // console.log(ticket_id);
    var formData = new FormData();
    formData.append('user_id', user_id);
    formData.append('ticket_id', ticket_id);


    var items = (event.clipboardData || event.originalEvent.clipboardData).items;
    var e = event.originalEvent;
    for (var i = 0; i < e.clipboardData.items.length; i++) {
        var item = e.clipboardData.items[i];
        // console.log('Item: ' + item.type);
        if (item.type.indexOf('image') != -1) {
            //item.
            var file = item.getAsFile && item.getAsFile();
            if (file) {

                // Attach file
                formData.append('files[]', file);

                $.ajax({
                    type: "POST",
                    url: "./action/addfiles.php",
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    beforeSend: function (objeto) {
                        $("#result_files").html("<img src='images/loading_1.gif' height='68px'>");
                    },
                    success: function (datos) {
                        previewFiles();
                        var response = JSON.parse(datos);
                    }
                });

            }

        } else {
            // ignore not images
            console.log('Discarding not image paste data');
        }
    }
});

function eliminar_img_preview(id, permit) {


    if (permit) {
        Swal.fire({
            title: '¿Estás seguro de eliminar el archivo?',
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
                    url: "./ajax/uploads.php",
                    data: "id=" + id,
                    beforeSend: function (objeto) {
                        $("#result_files").html("<img src='images/loading_1.gif' height='68px'>");
                    },
                    success: function (datos) {
                        previewFiles();
                    },
                    complete: function () {

                        // setTimeout(function () {
                        //     // $("#result_files").fadeOut("slow");
                        // }, 4000);

                    }
                });

            }
        });
    } else {


        Swal.fire({

            type: 'error',
            title: 'No eres propietario de este adjunto',
            showConfirmButton: false,
            timer: 1500
        });

    }

}
