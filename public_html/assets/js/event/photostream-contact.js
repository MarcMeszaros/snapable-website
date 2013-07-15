$(document).ready(function(){
    // make the textarea placeholder
    if (Modernizr.input.placeholder) {
        $('form#questionForm .message').attr('placeholder', $('form#questionForm .message').html());
        $('form#questionForm .message').html('');
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
                text: 'An error occurred while trying to send your message. Please email us directly at <a href="mailto:support@snapable.com">support@snapable.com</a>'
            });
        }
    });

});