$(document).ready(function(){
    /**** Event Settings ****/
    $('#event-title').click(function(){
        $('#event-settings-save-wrap img').remove();
        $('#event-settings').show();
    });
    $('#event-settings input[type=button].cancel').click(function(){
        $('#event-settings').hide();
    });
    $('#event-settings input[type=button].save').click(function(){
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

        $('<img src="/assets/img/spinner_blue_sm.gif" />').insertAfter('#event-settings input[type=button].save');
        $.post('/ajax/put_event/'+$('#event-top').data('event-id'), data, function(data)
        {
            $('#event-settings').hide();
            var json = jQuery.parseJSON(data);
            if ( json.status = 202 ) {
                // update field values
                $('#event-title').html(json.title);
                $('#event-address').html(json.addresses[0].address);

                // we shanged the url, redirect
                if ($('#event-settings-url').val() != $('#event-settings-url').data('orig')) {
                    $.pnotify({
                        type: 'success',
                        title: 'Settings',
                        text: "Your event settings have been updated.<br>We will redirect you to the new event url...",
                        delay: 3000,
                        after_close: function(pnotify) {
                            window.location = '/event/'+$('#event-settings-url').val();
                        }
                    });
                } else {
                    $.pnotify({
                        type: 'success',
                        title: 'Settings',
                        text: 'Your event settings have been updated.'
                    });
                }
            } else {
                $.pnotify({
                    type: 'error',
                    title: 'Settings',
                    text: "This is embarassing, something went wrong and we weren't able to change your event settings. If the problem persists, please contact us!"
                });
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

    // show the google map when the address setting is changed
    $('#event-settings-address').on('keypress', null, null, $.debounce(2000, false, function(event){
        if (event.type == 'keypress' && event.which != 13) {
            updateMap(this);
        }
    }));

    // handle the enter key
    $('#event-settings-address').on('keypress', null, null, function(event){
        if (event.type == 'keypress' && event.which == 13) {
            updateMap(this);
        }
    });

    // update map
    function updateMap(context) {
        $("#event-settings-address-status").removeClass("good").removeClass("bad").addClass("spinner-16px");
        $.getJSON("https://maps.googleapis.com/maps/api/geocode/json?address=" + encodeURIComponent($(context).val()) + "&sensor=false", function(data)
        {
            // get the lat/lng of the address
            if ( data['results'][0]['geometry']['location'] )
            {
                var lat = data['results'][0]['geometry']['location']['lat'];
                var lng = data['results'][0]['geometry']['location']['lng'];
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
    }

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
    $("#event-start-date").datepicker({format: 'M d, yyyy', autoclose: true});
    $("#event-start-time").timePicker({
        startTime: "06.00", // Using string. Can take string or Date object.
        show24Hours: false,
        separator: ':',
        step: 30
    });
});