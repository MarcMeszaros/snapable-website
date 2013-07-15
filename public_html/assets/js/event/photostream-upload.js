$(document).ready(function(){
    // setup the ajax form
    $('#uploadArea form').ajaxForm({
        beforeSubmit: function(arr, $form, options) {
            $('#photo-upload-btn').hide();
            $('#photo-upload-spinner').removeClass('hide');
        },
        success: function(responseText, statusText, xhr, $form) {
            // reset the form
            $('#uploadArea form').resetForm();

            // show stuff
            $('#photo-upload-btn').show();
            $('#photo-upload-spinner').addClass('hide');

            $.pnotify({
                type: 'success',
                title: 'Photo Uploaded',
                text: 'The photo was successfully uploaded.\nRefresh the page to see your photo in the stream.'
            });
        },
        error: function(){ 
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
    });

});