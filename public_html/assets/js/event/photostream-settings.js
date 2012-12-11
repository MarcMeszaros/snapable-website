$(document).ready(function(){
    /**** Event Settings ****/
    if (owner == true) {
        $('#event-title').click(function(){
            $('#event-settings-save-wrap img').remove();
            $('#event-settings').show();
        });
        $('#event-settings input[type=button].cancel').click(function(){
            $('#event-settings').hide();
        });
        $('#event-settings input[type=button].save').click(function(){
            $('<img src="/assets/img/spinner_blue_sm.gif" />').insertAfter('#event-settings input[type=button].save');
            
            var addressIdParts = $('#event-settings-address').data('resourceUri').split("/");
            var data = {
                title: $('#event-settings-title').val(),
                address_id: addressIdParts[3],
                address: $('#event-settings-address').val(),
                lat: $('#event-settings-lat').val(),
                lng: $('#event-settings-lng').val(),
                tz_offset: $('#event-settings-timezone').val(),
                start_date: $('#event-start-date').val(),
                start_time: $('#event-start-time').val(),
                duration_num: $('#event-duration-num').val(),
                duration_type: $('#event-duration-type').val()
            }
            if ($('#event-settings-url').val() != $('#event-settings-url').data('orig') && $('#event-settings-url').val().length > 0) {
                data.url = $('#event-settings-url').val();
            }

            $.post('/ajax/put_event/'+eid[3], data, function(data)
            {
                $('#event-settings').hide();
                var json = jQuery.parseJSON(data);
                if ( json.status = 202 ) {
                    // update field values
                    $('#event-title').html(json.title);
                    $('#event-address').html(json.addresses[0].address);

                    // we shanged the url, redirect
                    if ($('#event-settings-url').val() != $('#event-settings-url').data('orig')) {
                        sendNotification("positive", "Your event settings have been updated.<br>Redirecting you to the new event url...", 4000);
                        setTimeout(function(){
                            window.location = '/event/'+$('#event-settings-url').val();
                        }, 5000);
                    } else {        
                        sendNotification("positive", "Your event settings have been updated.");
                    }
                } else {
                    sendNotification("caution", "This is embarassing, something went wrong on our end and we weren't able to change your event settings—never fear, we're on it!");
                }
            });
        });
        $('#event-settings-url').keyup(function(){
            var url = $(this).val();
            url = sanitizeUrl(url);
            $(this).val(url);
            if(url != $(this).data('orig')) {
                checkUrl(url);
            } else {
                $("#event-settings-url-status").removeClass("bad").removeClass("spinner-16px").addClass("good");
            }
        });

        // call the blur event on "enter key"
        $('#event-settings-address').keypress(function(e){
            if(e.which == 13) {
                $(this).blur();
            }
        });

        // show the google map when the address setting is changed
        $('#event-settings-address').blur(function(){

            $("#event-settings-address-status").removeClass("good").removeClass("bad").addClass("spinner-16px");
            $.getJSON("http://where.yahooapis.com/geocode?location=" + encodeURIComponent($(this).val()) + "&flags=J&appid=qrVViDXV34GuS1yV7Mi2ya09wffvK6zlXaN1LFLQ3Q7fIXQI2MVhMtLMKQkDWMPP_g--", function(data)
            {
                // get the lat/lng of the address
                if ( data['ResultSet']['Error'] == 0 )
                {
                    var lat = data['ResultSet']['Results'][0]['latitude'];
                    var lng = data['ResultSet']['Results'][0]['longitude'];
                    $("#event-settings-lat").val(lat);
                    $("#event-settings-lng").val(lng);
                    // set spinner to checkmark
                    $("#event-settings-address-status").removeClass("spinner-16px").addClass("good");
                } else {
                    $("#event-settings-address-status").removeClass("spinner-16px").addClass("bad");
                }

                // display the google map
                var lat = $('#event-settings-lat').val();
                var lng = $('#event-settings-lng').val();
                var mapOptions = {
                    center: new google.maps.LatLng(lat, lng),
                    zoom: 15,
                    mapTypeId: google.maps.MapTypeId.ROADMAP,
                    streetViewControl: false
                };
                var map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
                // add the location marker
                var marker = new google.maps.Marker({
                    position: new google.maps.LatLng(lat, lng),
                    map: map,
                    draggable: true,
                    animation: google.maps.Animation.DROP
                });
                // add a listener to update the map/form when the marker is dragged
                google.maps.event.addListener(marker, "dragend", function(event) {
                    var point = marker.getPosition();
                    $("#event-settings-lat").val(point.lat());
                    $("#event-settings-lng").val(point.lng());
                    map.panTo(point);

                    var timestamp = Math.round((new Date()).getTime() / 1000);
                    var tzRequest = '/ajax/timezone?lat='+point.lat()+'&lng='+point.lng()+'&timestamp='+timestamp;
                    $.getJSON(tzRequest, function(data){
                        $('#event-settings-timezone').val((data.rawOffset/60));
                    });
                });

                // display the map on initial load
                $('#map_canvas-wrap').slideDown();
            });
        });
        // event time settings
        $("#event-duration-type").change(function() {
            var option = $(this).val();
            var values = "";
            var selected = "";
            
            if ( option == "days" ) {
                for ( var i=1; i <= 7; i++ ) { 
                    values += "<option value='" + i + "'" + selected + ">" + i + "</option>";
                }
            } else {
                for ( var i=1; i<= 23; i++ ) { 
                    if ( i == 12 ) {
                        selected = " SELECTED";
                    }
                    values += "<option value='" + i + "'" + selected + ">" + i + "</option>";
                }
            }
            $("#event-duration-num").html(values);
            if ( option == "days" ) {
                $("#event-duration-num").val(1);
            } else {
                $("#event-duration-num").val(12);
            }
        });
        // initialize the pickers
        $("#event-start-date").datepicker({dateFormat: 'M d, yy'});
        $("#event-start-time").timePicker({
            startTime: "06.00", // Using string. Can take string or Date object.
            show24Hours: false,
            separator: ':',
            step: 30
        });
    }
});