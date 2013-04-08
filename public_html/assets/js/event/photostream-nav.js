$(document).ready(function(){
    // UPLOAD MENU
    $("#uploadBTN").click( function()
    {
    	$(".slidContent").fadeOut("normal");
    	
        $.getJSON('/ajax/is_logged_in/', function(json_user){
            // if the owner isn't logged in
            if (json_user == false) {
                $.getJSON('/ajax/is_guest_logged_in/', function(json_guest){
                    // of no guest is logged in
                    if (json_guest == false) {
                        if (window.location.pathname.charAt(window.location.pathname.length-1) == '/') {
                            window.location.pathname = window.location.pathname + 'guest_signin'
                        } else {
                            window.location.pathname = window.location.pathname + '/guest_signin'
                        }
                        return false;
                    } else {
                        $("#uploadArea").slideToggle("fast");
                    }
                });
            } else {
                $("#uploadArea").slideToggle("fast");
            }
        });
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
    	$(".slidContent").fadeOut("normal");
        
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
    
    // TABLE CARDS
    $('#tableBTN').click(function()
    {
    	$(".slidContent").fadeOut("normal");
    	
        $.Mustache.load('/assets/js/event/templates-nav.html').done(function () {
            var eventUrl = $('#tablecards').data('url');
            $('#tablecards').mustache('tablecards', {url: eventUrl}).slideToggle();
            $('#tablecards a.download').click(function(){
                _gaq.push(['_trackEvent', 'Downloads', 'PDF']); // track the download as an analytics event
            });
        });
        return false;
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
                $.pnotify({
                    type: 'success',
                    title: 'Settings',
                    text: 'Your privacy settings have been updated.'
                });
                $("#privacySaveWrap").html("<input type='button' value='Save' />");
                if (privacy_selected == 0) {
                    $('#event-pin').fadeIn();
                } else {
                    $('#event-pin').fadeOut();
                }
            } else {
                $.pnotify({
                    type: 'error',
                    title: 'Settings',
                    text: "This is embarassing, something went wrong and we weren't able to change your privacy setting. If the problem persists, please contact us!"
                });
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

    // CONTACT MENU
    $('#event-nav-contact').click(function()
    {
    	$(".slidContent").fadeOut("fast");
    	
        $.Mustache.load('/assets/js/event/templates-nav.html').done(function () {
            var viewData = {
                email: user_email
            };
            $('#contact').mustache('nav-contact', viewData, {method: "html"});
            // modernise some stuff
            if (Modernizr.input.placeholder) {
                $('#contact textarea').each(function(){
                    $(this).attr('placeholder', $(this).html());
                    $(this).html('');
                });
            }
            // setup the form
            $("#contact form").ajaxForm();
            $('#contact').slideToggle();
        });
        return false;
    });
});