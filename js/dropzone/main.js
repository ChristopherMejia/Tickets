$(document).ready(function () {

    $(".dropzone2").dropzone({
        url: 'ajax/upload_xml.php',
        margin: 20,
        allowedFileTypes: 'xml',
        params: {
            'action': 'save'
        },
        uploadOnDrop: true,
        addRemoveLinks: true,
        uploadOnPreview: true,
        removeComplete: true,

        success: function (res, index) {
            // console.log(res, index);
        }
    });


    $(".dropzone").dropzone.autoDiscover = false;
    $(".dropzone").dropzone({
        url: 'ajax/upload_attach.php',
        margin: 20,
        allowedFileTypes: 'png,jpg,pdf',
        params: {
            'action': 'save'
        },
        uploadOnDrop: true,
        addRemoveLinks: true,
        uploadOnPreview: true,
        removeComplete: true,

        success: function (res, index) {
            // console.log(res, index);
        }
    });
});