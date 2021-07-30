$(".modal").draggable({
    handle: ".modal-header"
});

(function ($) {
    'use strict';

    var htmlWeb = "";

    // Mobile
    var isMobile = {
        Android: function () {
            return navigator.userAgent.match(/Android/i);
        },
        BlackBerry: function () {
            return navigator.userAgent.match(/BlackBerry/i);
        },
        iOS: function () {
            return navigator.userAgent.match(/iPhone|iPad|iPod/i);
        },
        Opera: function () {
            return navigator.userAgent.match(/Opera Mini/i);
        },
        Windows: function () {
            return navigator.userAgent.match(/IEMobile/i);
        },
        any: function () {
            return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
        }
    };

    // Animation
    var rollAnimation = function () {
        if (isMobile.any() == null) {
            $('.idea-animation').each(function () {
                var el = $(this),
                    rollClass = el.data('animation'),
                    rollDelay = el.data('animation-delay'),
                    rollOffset = el.data('animation-offset');

                el.css({
                    '-webkit-animation-delay': rollDelay,
                    '-moz-animation-delay': rollDelay,
                    'animation-delay': rollDelay
                });

                el.waypoint(function () {
                    el.addClass('animated').addClass(rollClass);
                }, {
                    triggerOnce: true,
                    offset: rollOffset
                });
            });
        }
    };


    // Contact Form
    var ajaxContactForm = function () {
        // http://www.bitrepository.com/a-simple-ajax-contact-form-with-php-validation.html
        $('.contact-form').each(function () {
            var $this = $(this);
            $this.submit(function () {
                var str = $this.serialize();
                $.ajax({
                    type: "POST",
                    url: $this.attr('action'),
                    data: str,
                    success: function (msg) {
                        // Message Sent? Show the 'Thank You' message and hide the form
                        var result;
                        if (msg == 'OK') {
                            result = '<div class="notification_ok">Your message has been sent. Thank you!</div>';
                        } else {
                            result = msg;
                        }
                        result = '<div class="result">' + result + '</div>';
                        $this.find('.note').html(result);
                    }
                });
                return false;
            }); // submit

        }); // each contactform
    };


    // Preloader
    var removePreloader = function () {
        $('.loader').fadeOut('slow', function () {
            $(this).remove();
        });
    };


    // Top Search
    var topSearch = function () {
        $('.top .contact-info li.search a').on('click', function () {
            if (!$('.top-search').hasClass("show-search"))
                $('.top-search').addClass('show-search');
            else
                $('.top-search').removeClass('show-search');
        });
    };

    $("#write_msg").bind('paste', function (event) {

        var user_id = $('#user_id').val();
        var ticket_id = $('#ticket_id').val();

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
                            // $("#result_files").html(datos);
                            showFiles();
                            var response = JSON.parse(datos);

                            console.log(response);

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


                }

            } else {
                // ignore not images
                console.log('Discarding not image paste data');
            }
        }
    });


    // Dom Ready
    $(function () {
        rollAnimation();
        ajaxContactForm();
        topSearch();
        $(window).bind('load', function () {
            removePreloader();
        });
    });
})(jQuery);

