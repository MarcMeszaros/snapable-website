$(document).ready(function(){
    // show the contact label
    if (Modernizr.input.placeholder) {
        $('#contact-email-label').hide();
        $('#questionForm .message').attr('placeholder', $('#questionForm .message').html());
        $('#questionForm .message').html('');
    }

    // setup the ajax form
    $('#questionForm').ajaxForm({
        success: function() {
            // show a notification
            noty({
                layout: 'center',
                type: 'success',
                timeout: 5000,
                text: 'Thanks! Your message has been sent.'
            });

            // reset the form
            $('#questionForm').resetForm();
        },
        error: function(){ 
            // show a notification
            noty({
                layout: 'center',
                type: 'error',
                timeout: 10000,
                text: 'An error occurred while trying to send your message. Please email us directly at team@snapable.com'
            });
        }
    });
});