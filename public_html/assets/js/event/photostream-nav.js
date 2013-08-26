$(document).ready(function(){
    // expand based on anchor
    if (location.hash == "#upload-photo") {
        $("#uploadArea").slideToggle("fast");
    }

    // UPLOAD MENU
    $("#uploadBTN").click(function() {
    	$(".slidContent").fadeOut("normal");
        $.getJSON('/ajax/is_logged_in/', function(json_user) {
            // if the owner isn't logged in
            if (json_user == false) {
                $.getJSON('/ajax/is_guest_logged_in/', function(json_guest){
                    // of no guest is logged in
                    if (json_guest == false) {
                        if (window.location.pathname.charAt(window.location.pathname.length-1) == '/') {
                            window.location.assign(window.location.pathname + 'guest_signin?upload-photo=1')
                        } else {
                            window.location.assign(window.location.pathname + '/guest_signin?upload-photo=1')
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

    // PRIVACY MENU
    $("#event-nav-privacy").click(function(e) {          
        e.preventDefault();
        $("#event-nav-menu-privacy").toggle();
        $("#event-nav-privacy").toggleClass("menu-open");
    });

    $(document).on("click", "#event-nav-menu-privacy input[type='button']", function(e) {
        var privacy_selected = $("input[name=privacy-setting]:checked").val();
        $("#privacySaveWrap").html("<img src='/assets/img/spinner_blue_sm.gif' />");
        $.post("/event/privacy", { event:eventID, selected:privacy_selected }, function(data) {
            var json = jQuery.parseJSON(data);
            if ( json.status = 202 ) {
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

});