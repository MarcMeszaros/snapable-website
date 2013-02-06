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
            noty({
                layout: 'center',
                type: 'error',
                timeout: 5000,
                text: 'Forget to include your message?'
            });
            e.preventDefault();
            return false;
        } else {
            $.post("/ajax/send_email", {subject:$("input[name=subject]").val(),message:message,from:$("input[name=from]").val()}, function(data){
                if ( data == "success" )
                {
                    noty({
                        layout: 'center',
                        type: 'success',
                        timeout: 5000,
                        text: 'Thanks! Your message has been sent.'
                    });
                } else {
                    noty({
                        layout: 'center',
                        type: 'error',
                        timeout: 10000,
                        text: 'An error occurred while trying to send your message. Please email us directy at team@snapable.com'
                    });
                    $('input[name=submit]').attr("disabled", "enabled");
                }
            })  
            e.preventDefault();
            return false;
        }
    });

});