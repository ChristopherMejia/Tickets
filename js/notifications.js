$(document).ready(function () {

    var user = $("#user_id").val();
    var page = $("#page").val();


    function load_users_presence() {
        $.ajax({
            url: "lib/Notifi/users_presence.php",
            method: "POST",
            data: {user: user, page: page},
            dataType: "json",
            success: function (data) {
                $('#users-presence-div').html(data.usersp);
            }
        });
    }

    load_users_presence();


    /* PUSH */
    function showNotification() {
        if (!Notification) {
            $('body').append('<h4 style="color:red">*Browser does not support Web Notification</h4>');
            return;
        }
        if (Notification.permission !== "granted") {
            Notification.requestPermission();
        } else {
            $.ajax({
                url: "lib/Notifi/push.php",
                type: "POST",
                data: {user: user},
                success: function (data, textStatus, jqXHR) {

                    // console.log(data);
                    if (data != "") {
                        var data = jQuery.parseJSON(data);
                        console.log(data.notif);

                        if (data.result == true && data.count >= 1) {
                            var data_notif = data.notif;
                            console.log(data_notif.length);

                            for (var i = data_notif.length - 1; i >= 0; i--) {

                                var theurl = data_notif[i]['url'];
                                var notifikasi = new Notification(data_notif[i]['name'], {
                                    title: data_notif[i]['title'],
                                    icon: data_notif[i]['icon'],
                                    body: data_notif[i]['msg'],
                                });
                                notifikasi.onclick = function () {
                                    window.open(theurl);
                                    notifikasi.close();
                                };
                                setTimeout(function () {
                                    notifikasi.close();
                                }, 5000);
                            }
                        }
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                }
            });
        }
    }
    showNotification();
    /* //PUSH */

    /* BELL */
    function load_unseen_notification(view = '') {

        $.ajax({
            url: "lib/Notifi/bell.php",
            method: "POST",
            data: {view: view, user: user},
            dataType: "json",
            success: function (data) {
                // console.log(data);
                $('#noti').html(data.notification);
                if (data.unseen_notification >= 0) {
                    $('.count-notifi').html(data.unseen_notification);
                }
            }
        });
    }

    load_unseen_notification();
    /* //BELL */

    $(document).on('click', '#noti', function () {

        $('.count').html('');
        load_unseen_notification('yes');
    });

    /* ////////////////////////////////////////////////////////////// */

    /* ///////////////MESSAGES///////////////// */

    function load_unseen_notification_msg(view = '') {
        $.ajax({
            url: "lib/Notifi/bell_comments.php",
            method: "POST",
            data: {view: view, user: user},
            dataType: "json",
            success: function (data) {
                // console.log(data);
                $('#noti-msg').html(data.notification);
                if (data.unseen_notification_msg >= 0) {
                    $('.count-notifi-msg').html(data.unseen_notification_msg);
                }
            }
        });
    }

    load_unseen_notification_msg();


    setInterval(function () {
        showNotification();
        load_unseen_notification();
        load_unseen_notification_msg();
        load_users_presence();
    }, 100000);

});