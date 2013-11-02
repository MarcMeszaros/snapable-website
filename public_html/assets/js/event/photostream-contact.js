function contactSuccess() {
    $.pnotify({
        type: 'success',
        title: 'Message Sent',
        text: 'Your message has been successfully sent.'
    });
    $('#questionForm').trigger('reset');
}

function contactError() {
    $.pnotify({
        type: 'error',
        title: 'Message Not Sent',
        text: 'An error occurred while trying to send your message. Please email us directly at <a href="mailto:support@snapable.com">support@snapable.com</a>'
    });
}

$(document).ready(function(){
    // CONTACT MENU
    $('#event-nav-contact').click(function() {
        $('.slidContent[id!="contact"]').slideUp();
        if ($("#contact").hasClass('hide')) {
            $("#contact").removeClass("hide").hide().slideDown();
        } else {
            $("#contact").slideToggle();
        }
        // make the textarea placeholder
        if (!Modernizr.input.placeholder) {
            $('form#questionForm .message').html($('form#questionForm .message').attr('placeholder'));
        }
    });
});