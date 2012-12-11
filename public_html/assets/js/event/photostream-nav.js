$(document).ready(function(){
    // UPLOAD MENU
    $("#uploadBTN").click( function()
    {
        $("#uploadArea").slideToggle("fast");
    });

    // SLIDESHOW MENU
    $("#slideshowBTN").click( function(e)
    {
        e.preventDefault();
        $("#slideshow").slideToggle();
    });
    
    // GUEST MENU
    $("#guestBTN").click( function(e)
    {
        e.preventDefault();
        
        $.Mustache.load('/assets/js/templates.html').done(function () 
        {
            $('#guest').mustache('invite-guests',"",{method: "html"});
            $("#guest").slideToggle();
            // get notification template and drop in place
            $.get('/event/guests/notify', function(data) 
            {
                $("#notify-message").html(data);
                // check if event has guests, hide 'send email' button if it doesn't
                
                $.get('/event/guests/count', { resource_uri:eventID }, function(count)
                {
                    if ( count == 0 )
                    {
                        $("#do-notify-wrap").html("No guests have been invited yet.");
                    }
                });
            });
        });
        //$("#guest").slideToggle();
    });

    // PRIVACY MENU
    $("#event-nav-privacy").click(function(e) 
    {          
        e.preventDefault();
        $("#event-nav-menu-privacy").toggle();
        $("#event-nav-privacy").toggleClass("menu-open");
    });
    
    $("#event-nav-menu-privacy").mouseup(function() 
    {
        return false
    });

    $(document).on("click", "#event-nav-menu-privacy input[type='button']", function(e)
    {
        var privacy_selected = $("input[name=privacy-setting]:checked").val();
        $("#privacySaveWrap").html("<img src='/assets/img/spinner_blue_sm.gif' />");
        $.post("/event/privacy", { event:eventID, selected:privacy_selected }, function(data)
        {
            var json = jQuery.parseJSON(data);
            if ( json.status = 202 )
            {
                sendNotification("positive", "Your privacy settings have been updated.");
                $("#privacySaveWrap").html("<input type='button' value='Save' />");
                if (privacy_selected == 0) {
                    $('#event-pin').fadeIn();
                } else {
                    $('#event-pin').fadeOut();
                }
            } else {
                alert("This is embarassing, something went wrong on our end and we weren't able to change your privacy setting—never fear, we're on it!");
            }
            $('#event-nav-menu-privacy').hide();
        });
    });

    $(document).mouseup(function(e) {
        if($(e.target).parent("a#event-nav-privacy").length==0) {
            $("#event-nav-privacy").removeClass("menu-open");
            $("#event-nav-menu-privacy").hide();
        }
    });
});