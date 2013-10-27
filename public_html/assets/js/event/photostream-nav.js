$(document).ready(function(){

    // PRIVACY MENU
    $("#event-nav-privacy").click(function() {
        $("#event-nav-menu-privacy").toggle();
        $("#event-nav-privacy").toggleClass("menu-open");
        return false;
    });

    $("#event-nav-menu-privacy").mouseup(function() {
        return false
    });

    $(document).mouseup(function(e) {
        if ($(e.target).parent("a#event-nav-privacy").length == 0) {
            $("#event-nav-privacy").removeClass("menu-open");
            $("#event-nav-menu-privacy").hide();
        }
    });

    // setup the ajax form
    $('#event-nav-menu-privacy form').ajaxForm({
        beforeSubmit: function(arr, $form, options) {
            $("#privacySaveWrap").html("<img src='/assets/img/spinner_blue_sm.gif' />");
        },
        success: function(responseText, statusText, xhr, $form) {
            var privacy_selected = $("#event-nav-menu-privacy input[name=privacy-setting]:checked").val();
            $.pnotify({
                type: 'success',
                title: 'Settings',
                text: 'Your privacy settings have been updated.'
            });
            $("#privacySaveWrap").html('<input type="submit" class="btn btn-primary" value="Save" />');
            if (privacy_selected == 0) {
                $('#event-pin').fadeIn();
            } else {
                $('#event-pin').fadeOut();
            }
            $('#event-nav-menu-privacy').hide();
        },
        error: function(){ 
            $.pnotify({
                type: 'error',
                title: 'Settings',
                text: "This is embarassing, something went wrong and we weren't able to change your privacy setting. If the problem persists, please contact us!"
            });
        }
    });

});