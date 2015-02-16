function settingsBeforeSubmit() {
    $('#settings-save-spinner').removeClass('hide');
    $('#event-settings-streamable').val($('#event-settings-streamable-toggle').bootstrapSwitch('state'));
    $('#event-settings-public').val($('#event-settings-public-toggle').bootstrapSwitch('state'));
}

function settingsSuccess() {
    $('#event-settings').hide();
    var json = $.parseJSON(this.response);
    // update field values
    $('#event-title').html(json.title);
    $('#event-address').html(json.addresses[0].address);
    if ($('#event-settings-public-toggle').bootstrapSwitch('state')) {
        $('#event-pin').fadeOut();
    } else {
        $('#event-pin').fadeIn();      
    }

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
    $('#settings-save-spinner').addClass('hide');
}

function settingsError() {
    $.pnotify({
        type: 'error',
        title: 'Settings',
        text: "This is embarassing, something went wrong and we weren't able to change your event settings. If the problem persists, please contact us!"
    });
    $('#settings-save-spinner').addClass('hide');
}

$(document).ready(function(){
    $('#event-settings-streamable-toggle').bootstrapSwitch();
    $('#event-settings-public-toggle').bootstrapSwitch();
    $(document).mouseup(function(e) {
        if ($(e.target).attr('id') != 'event-settings' && $(e.target).parents('#event-settings,.datepicker,.time-picker').length == 0) {
            $('#event-settings').slideUp();
        }
    });

    $('#event-settings-url').keyup(function(){
        var url = sanitizeUrl($(this).val());
        $(this).val(url);
        if(url != $(this).data('orig')) {
            checkUrl(url);
        } else {
            $("#event-settings-url").removeClass("bad").removeClass("spinner-16px").addClass("good");
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
        $("#event-settings-address").removeClass("good").removeClass("bad").addClass("spinner-16px");
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
                $("#event-settings-address").removeClass("spinner-16px").addClass("good");
            } else {
                $("#event-settings-address").removeClass("spinner-16px").addClass("bad");
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
    //if (!Modernizr.inputtypes.date) {
        $("#event-start-date").datepicker({format: 'M d, yyyy', autoclose: true});
    //}
    $("#event-start-time").timePicker({
        startTime: "06.00", // Using string. Can take string or Date object.
        show24Hours: false,
        separator: ':',
        step: 30
    });
});