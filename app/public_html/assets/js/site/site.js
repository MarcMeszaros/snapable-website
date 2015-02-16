function questionSuccess() {
    $.pnotify({
        type: 'success',
        title: 'Message Sent',
        text: 'Your message has been successfully sent.'
    });
    $('#questionForm').trigger('reset');
}

function questionError() {
    $.pnotify({
        type: 'error',
        title: 'Message Not Sent',
        text: 'An error occurred while trying to send your message. Please email us directly at <a href="mailto:support@snapable.com">support@snapable.com</a>'
    });
}

$(document).ready(function(){
    // show the contact label
    if (Modernizr.input.placeholder) {
        $('#contact-email-label').hide();
        $('#contact-message-label').hide();
    }
});