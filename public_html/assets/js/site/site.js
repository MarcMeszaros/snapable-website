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
            $.pnotify({
                type: 'success',
                title: 'Message Sent',
                text: 'Your message has been successfully sent.'
            });

            // reset the form
            $('#questionForm').resetForm();
        },
        error: function(){ 
            // show a notification
            $.pnotify({
                type: 'error',
                title: 'Message Not Sent',
                text: 'An error occurred while trying to send your message. Please email us directly at <a href="mailto:team@snapable.com">team@snapable.com</a>'
            });
        }
    });
});