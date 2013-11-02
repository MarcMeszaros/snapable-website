function uploadBeforeSubmit() {
    $('#photo-upload-btn').hide();
    $('#photo-upload-spinner').removeClass('hide');
}

function uploadSuccess() {
    // reset the form
    $('#uploadArea form').trigger('reset');

    // show stuff
    $('#photo-upload-btn').show();
    $('#photo-upload-spinner').addClass('hide');

    $.pnotify({
        type: 'success',
        title: 'Photo Uploaded',
        text: 'The photo was successfully uploaded.\nRefresh the page to see your photo in the stream.'
    });
}

function uploadError() {
    // show a notification
    $.pnotify({
        type: 'error',
        title: 'Photo Not Uploaded',
        text: 'An error occurred while trying to upload your photo. Make sure the selected image is smaller than 10MB and is a JPEG.'
    });

    // show stuff
    $('#photo-upload-btn').show();
    $('#photo-upload-spinner').addClass('hide');
}

$(document).ready(function(){
    // expand based on anchor
    if (location.hash == "#upload-photo") {
        $("#uploadArea").removeClass("hide").hide().slideDown();
    }

    // UPLOAD MENU
    $("#uploadBTN").click(function() {
        // only dropdown if logged in
        if(!$(this).data('signin')) {
            $('.slidContent[id!="uploadArea"]').slideUp();
            if ($("#uploadArea").hasClass('hide')) {
                $("#uploadArea").removeClass("hide").hide().slideDown();
            } else {
                $("#uploadArea").slideToggle();
            }
            return false;
        }
    });

});