$(document).ready(function(){
    // setup the ajax form
    $('#uploadArea form').ajaxForm({
        beforeSubmit: function(arr, $form, options) {
            $('#photo-upload-btn').hide();
            $('#photo-upload-spinner').removeClass('hide');
        },
        success: function(responseText, statusText, xhr, $form) {
            // parse the JSON text
            var result = $.parseJSON(responseText);
            // show the facebox
            jQuery.facebox({ ajax: '/upload/crop/' + result.image + '/' });

            // reset the form
            $('#uploadArea form').resetForm();

            // show stuff
            $('#photo-upload-btn').show();
            $('#photo-upload-spinner').addClass('hide');
        },
        error: function(){ 
            // show a notification
            $.pnotify({
                type: 'error',
                title: 'Image Not Uploaded',
                text: 'An error occurred while trying to upload your photo. Make sure the selected image is smaller than 10MB and is a JPEG.'
            });

            // show stuff
            $('#photo-upload-btn').show();
            $('#photo-upload-spinner').addClass('hide');
        }
    });

});