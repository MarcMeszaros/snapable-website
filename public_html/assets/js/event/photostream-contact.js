$(document).ready(function(){
    // make the textarea placeholder
    if (Modernizr.input.placeholder) {
        $('form#questionForm .message').attr('placeholder', $('form#questionForm .message').html());
        $('form#questionForm .message').html('');
    }

    $(document).on("submit", "form#questionForm", function(e) 
    {
        $('input[name=submit]').attr("disabled", "disabled");
        var message = $("textarea[name=message]").val();
        if ( message == "" || message == "Enter a question, comment or message...")
        {
            $.pnotify({
                type: 'error',
                title: 'Message Not Sent',
                text: 'Forget to include your message?'
            });
            e.preventDefault();
            return false;
        } else {
            $.post("/ajax/send_email", {subject:$("input[name=subject]").val(),message:message,from:$("input[name=from]").val()}, function(data){
                if ( data == "success" )
                {
                    $.pnotify({
                        type: 'success',
                        title: 'Message Sent',
                        text: 'Thanks! Your message has been sent.'
                    });
                } else {
                    $.pnotify({
                        type: 'error',
                        title: 'Message Not Sent',
                        text: 'An error occurred while trying to send your message. Please email us directy at <a href="mailto:team@snapable.com">team@snapable.com</a>'
                    });
                    $('input[name=submit]').attr("disabled", "enabled");
                }
            })  
            e.preventDefault();
            return false;
        }
    });

});