var IDLE_TIMEOUT = 100 * 60;  // 10 minutes of inactivity
var _idleSecondsCounter = 0;
document.onclick = function () {
    _idleSecondsCounter = 0;
};
document.onmousemove = function () {
    _idleSecondsCounter = 0;
};
document.onkeypress = function () {
    _idleSecondsCounter = 0;
};
window.setInterval(CheckIdleTime, 1000);

function CheckIdleTime() {
    _idleSecondsCounter++;
    var oPanel = document.getElementById("SecondsUntilExpire");
    if (oPanel)
        oPanel.innerHTML = (IDLE_TIMEOUT - _idleSecondsCounter) + "";
    if (_idleSecondsCounter >= IDLE_TIMEOUT) {
        // destroy the session in logout.php

        _idleSecondsCounter = 0;

        Swal.fire({
            title: 'Tu sesión expirará pronto',
            html: '<p>Actualice la sesión para seguir navegando.</p>' +
                '<div id="display"></div>',
            type: 'question',
            showCancelButton: false,
            confirmButtonColor: '#d6972e',
            cancelButtonColor: '#c1c1c1',
            confirmButtonText: '¡Sigo Aquí!'
        }).then((result) => {
            // document.location.href = "action/logout.php";
        });


        CountDown(30, $('#display'));

    }
}

function CountDown(duration, display) {
    if (!isNaN(duration)) {
        var timer = duration, minutes, seconds;

        var interVal = setInterval(function () {
            minutes = parseInt(timer / 60, 10);
            seconds = parseInt(timer % 60, 10);

            minutes = minutes < 10 ? "0" + minutes : minutes;
            seconds = seconds < 10 ? "0" + seconds : seconds;

            $(display).html("<b>" + seconds + "</b>");
            if (--timer < 0) {
                timer = duration;
                document.location.href = "action/logout.php";
                clearInterval(interVal);
            }
        }, 1000);
    }
}