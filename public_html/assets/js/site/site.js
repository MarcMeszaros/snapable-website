$(document).ready(function(){
    // show the contact label
    if (Modernizr.input.placeholder) {
        $('#contact-email-label').hide();
        $('#questionForm .message').attr('placeholder', $('#questionForm .message').html());
        $('#questionForm .message').html('');
    }
    // if we can do the email form
    if (Modernizr.inputtypes.email) {
        var oldInput = $('#contact-email');
        var newInput = $('#contact-email').clone();
        newInput.attr('type', 'email');
        newInput.insertBefore(oldInput);
        oldInput.remove();
    }

    // setup the ajax form
    $('#questionForm').ajaxForm();
});