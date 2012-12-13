$(document).ready(function(){
    /// GUEST LOGIN
    $('form').submit(function(e) 
    {
        var email = $("input[name=email]").val();
        var pin = $("input[name=pin]").val();
        
        var emailReg = new RegExp("[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?");
        
        if ( emailReg.test(email) == false )
        {
            //$("#email-error").fadeIn("fast");
            $("label[for=email]").css({ "color": "#cc3300" });
            $("label[for=email] div").fadeIn("fast");
            $("input[name=email]").addClass("inputError");
            e.preventDefault();
            return false;
        }
        else if ( pin == "")
        {
            $("input[name=email]").removeClass("inputError");
            $("label[for=email]").css({ "color": "#999" });
            $("label[for=email] div").fadeOut("fast");
            
            $("label[for=pin]").css({ "color": "#cc3300" });
            $("label[for=pin] div").fadeOut("fast");
            $("input[name=pin]").addClass("inputError");
            e.preventDefault();
            return false;
        } else {
            $("label[for=email], label[for=pin]").css({ "color": "#999" });
            $("input[name=email], input[name=pin]").removeClass("inputError");
            $("label[for=email] div, label[for=pin] div").fadeOut("fast");
            return true;
        }
    });
});