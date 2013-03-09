$(document).ready(function(){
    // GUESTS
    $(document).on("click", ".tabs a", function()
    {
        var href = $(this).attr("href").substring(1);
        
        if ( $(this).parent().attr("class") != "active" )
        {
            $(".tabs li").removeClass("active");
            $(this).parent().addClass("active");
            
            if ( href == "guestlist" )
            {
                $(".tab-content").hide();
                $("#" + href + "Box").html("<div class='bar'><span></span></div>").show();
                
                // get guest list
                var eid = eventID.split("/");
                $.getJSON('/event/get/guests/' + eid[3], function(json) {
                    if ( json.status == 200 )
                    {
                        $.Mustache.load('/assets/js/templates.html').done(function () 
                        {
                            $("#" + href + "Box").fadeOut("fast", function()
                            {
                                // format results and then fadeIn
                                $("#" + href + "Box").html("<ul></ul>");
                                $.each(json.guests, function(key, val) 
                                {
                                    var viewData = { 
                                        name: val.name,
                                        email: val.email,
                                        type: val.type 
                                    };
                                    $("#" + href + "Box ul").mustache("guest-list", viewData);
                                });
                                $("#" + href + "Box").fadeIn("normal");
                            });
                        });
                    } else {
                        $("#" + href + "Box").html("You haven't added any guests to your event.");
                    }
                });
            } else {
                $(".tab-content").hide();
                $("#" + href + "Box").show();
            }
        }
        return false;
    });
    
    //$("#guest-link-upload").on("click", function(event) {
    $(document).on("click", "#guest-link-upload", function(){ 
        $.Mustache.load('/assets/js/templates.html').done(function () 
        {
            $("#guest-choices").hide().mustache("guest-upload", "", {method: "html"}).addClass("guests-options-wrap").fadeIn("normal");
        });
        return false;
    });

    $(document).on("click", "#guest-link-manual", function(){ 
        $("#guest-choices").hide().mustache("guest-manual", "", {method: "html"}).addClass("guests-options-wrap").fadeIn("normal");
        return false;
    });
    
    
    $(document).on("click", "#guests-file-how-to-csv-link", function(){ 
        $("#guests-file-how-to-csv").fadeIn("normal");
        return false;
    });
    
    $(document).on("click", ".guests-back-to-choices", function(){ 
        //$("#guests-manual, #guests-upload").hide();
        //$("#guests-choices").fadeIn();
        $("#guest-choices").hide().mustache("guest-options", "", {method: "html"}).removeClass("guests-options-wrap").fadeIn("normal");
        return false;
    });
    
    $(document).on("click", "#guests-upload-csv", function(e){
        e.preventDefault();
        
        var ext = $('#guests-csv-input').val().split('.').pop().toLowerCase();
        if($.inArray(ext, ['csv']) == -1) {
            alert("Sorry, this doesn't appear to be a CSV file");
        } else {
            var iframe = $('<iframe name="postCSViframe" id="postCSViframe" style="display: none" />');

            $("body").append(iframe);

            var form = $('#guests-file-uploader');
            form.attr("action", "/upload/csv");
            form.attr("method", "post");
            form.attr("enctype", "multipart/form-data");
            form.attr("encoding", "multipart/form-data");
            form.attr("target", "postCSViframe");
            form.attr("file", $('#guests-csv-input').val());
            form.submit();

            $("#postCSViframe").load(function () {
                iframeContents = $("#postCSViframe")[0].contentWindow.document.body.innerHTML;
                var guestJSON = jQuery.parseJSON(iframeContents);
                
                if ( guestJSON.status == 200 )
                {
                    csvFilename = guestJSON.filename;
                    
                    $.Mustache.load('/assets/js/templates.html').done(function () 
                    {
                        var viewData = {
                        };
                        $("#guest-choices").removeClass("choiceBox").addClass("csvParse").mustache('guest-csv-parse', viewData, {method: "html"});
                        
                        var emailHeader = guestJSON.header.email.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
                        var nameHeader = guestJSON.header.name.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
                        var guestHeader = guestJSON.header.type.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
                        
                        $("#csvHeader" + emailHeader).val("email");
                        $("#csvHeader" + nameHeader).val("name");
                        $("#csvHeader" + guestHeader).val("type");
                        
                        // populated each row with the correct data (use template: guest-csv-row)
                        
                        $.each(guestJSON.rows, function(key, val) 
                        {
                            var emailRow = emailHeader.toLowerCase();
                            var nameRow = nameHeader.toLowerCase();
                            var guestRow = guestHeader.toLowerCase();
                            
                            $.each(val, function(k, v) 
                            {
                                if ( k == emailRow )
                                {
                                    var emailData = { 
                                        text: v 
                                    };
                                    $("#row" + emailHeader + "Contents").mustache('guest-csv-row', emailData);  
                                }
                                else if ( k == nameRow )
                                {
                                    var nameData = { 
                                        text: v 
                                    };
                                    $("#row" + nameHeader + "Contents").mustache('guest-csv-row', nameData);    
                                }
                                else if ( k == guestRow )
                                {
                                    var guestData = { 
                                        text: v 
                                    };
                                    $("#row" + guestHeader + "Contents").mustache('guest-csv-row', guestData);  
                                }   
                            })
                        });
                        
                    });
                } else {
                    alert("Sad Trombone, it seems we couldn't read the file you uploaded.");
                }
                
            });
            
        }
        /*
        $("#guests-upload").html("<strong>Your guests have been uploaded.</strong><br />Would you like to compose an email to let them know to download the Snapable app? <a id='notify-guests-yes' href='#'>Yes</a> / <a id='notify-guests-no' href='#'>No</a>");
        */
        return false;
    });
    
    $(document).on("click", "#csvAllDone", function(e)
    {
        e.preventDefault();
        
        if ( csvFilename != "" )
        {
            $("#allDoneWrap").html("<img src='/assets/img/spinner_32px.gif' />");
            $.post("/parse/csv", { event:eventID, file:csvFilename, col1:$("#csvHeaderOne").val(), col2:$("#csvHeaderTwo").val(), col3:$("#csvHeaderThree").val() }, function(data)
            {
                var json = jQuery.parseJSON(data);
                
                if ( json.status == 200 )
                {
                    // switch tab to notify and show content
                    $("#addTab").removeClass("active");
                    $("#notifyTab").addClass("active");
                    $("#addBox").fadeOut("fast", function()
                    {
                        $.get('/event/guests/count', { resource_uri:eventID }, function(count)
                        {
                            if ( count == 0 )
                            {
                                $("#do-notify-wrap").html("No guests have been invited yet.");
                            } else {
                                $("#do-notify-wrap").html('<a href="#" id="do-notify-guests">Send Email(s)</a>');
                            }
                        });
                        $("#notifyBox").fadeIn("fast");
                    });
                } else {
                    alert("We weren't able to complete the upload of your guest list at this time.");
                    $("#allDoneWrap").html("<a id='csvAllDone' href='#'>All Done </a>");
                }
            });
        } else {
            alert("We weren't able to complete the upload of your guest list at this time.");
        }
    });
    
    
    $(document).on("focus", "#notify-custom-message", function(e)
    {
        if ( $(this).val() == "Enter a message for your guests." )
        {
            $(this).val("").css({"color":"#333333"});
        } 
    });
    $(document).on("blur", "#notify-custom-message", function(e)
    {
        if ( $(this).val() == "" )
        {
            $(this).val("Enter a message for your guests.").css({"color":"#999"});
        } 
    });
    $(document).on("click", "#do-send-to-guests", function(e)
    {
    	$("#notify-group").html("<img style='vertical-align:middle' src='/assets/img/spinner_blue_sm.gif' width='16' height='16' alt='*' /> Sending...");
    	
        // get checkboxes checked and message
        var message = $("#notify-custom-message").val();
        
        if ( message == "" || message == "Enter a message for your guests." )
        {
            alert("You haven't supplied a message for your guests.")
        }
        else {
            $.post("/event/send/invites", { resource_uri:eventID, message:message }, function(data)
            {
                if ( data == "sent" )
                {
                    $.pnotify({
                        type: 'success',
                        title: 'Invitations',
                        text: 'Your invitations were successfully sent.'
                    });
                } else {
                    $.pnotify({
                        type: 'error',
                        title: 'Invitations',
                        text: "We weren't able to email your guests the invitations, contact us and we'll be happy to help."
                    });
                }
                $("#notify-group").html('<a href="#" id="do-send-to-guests">Send Email(s)</a>');
            });
        }
    });
    
    
    $(document).on("click", "#notify-guests-yes", function()
    {
        $("#overlay-tabs-add").removeClass("active");
        $("#overlay-tabs-notify").addClass("active");
        $("#add-guests-wrap").fadeOut("fast", function()
        {
            $("#notify-guests").fadeIn("fast");
        });
        return false;
    });
    
    $(document).on("click", "#guests-manual-done", function()
    {
        // check if there's anything in the textbox
        if ( $("#guests-manual-textarea").val() == "" )
        {
            alert("It doesn't look like you've invited anyone to your event.");
            $("#guests-manual-textarea").focus();
        } else {
            $.post("/parse/text", { eventURI:eventID, message:$("#guests-manual-textarea").val() }, function(data)
            {
                var json = jQuery.parseJSON(data);
                if ( json.status == 200 )
                {
                    // switch tab to notify and show content
                    $("#addTab").removeClass("active");
                    $("#notifyTab").addClass("active");
                    $("#addBox").fadeOut("fast", function()
                    {
                        $.get('/event/guests/count', { resource_uri:eventID }, function(count)
                        {
                            if ( count == 0 )
                            {
                                $("#do-notify-wrap").html("No guests have been invited yet.");
                            } else {
                                $("#do-notify-wrap").html('<a href="#" id="do-notify-guests">Send Email(s)</a>');
                            }
                        });
                        $("#notifyBox").fadeIn("fast");
                    });
                } else {
                    alert("We weren't able to complete the upload of your guest list at this time.");
                }
            });
        }
    });
});