function checkUrl(url) {
    $("#event_url").removeClass("good").removeClass("bad").addClass("spinner-16px");
    $.ajax('/internal/ajax_check_url', {
        type: 'GET',
        data: { 'url': url }
    }).done(function(data){
        var resp = $.parseJSON(data);
        if (resp.meta.total_count > 0) {
            $("#event_url").addClass("bad");
        } else {
            $("#event_url").addClass("good");
        }
    }).always(function(data){
        $("#event_url").removeClass("spinner-16px");
    });
}

function geocoder(address) {
    // do geocode to get addresses lat/lng
    // set #lat and #lng
    $.ajax("https://maps.googleapis.com/maps/api/geocode/json", {
        type: 'GET',
        data: {
            'address': encodeURIComponent(address),
            'sensor': false
        }
    }).done(function(data){
        if ( data.status == 'OK' ) {
            var lat = data['results'][0]['geometry']['location']['lat'];
            var lng = data['results'][0]['geometry']['location']['lng'];
            $("#lat").val(lat);
            $("#lng").val(lng);
            var timestamp = Math.round((new Date()).getTime() / 1000);
            updateTimezone(lat, lng, timestamp);
        }
    });

    return true;
}

function sanitizeUrl() {
    // replace spaces with dashes change uppercase to lowercase
    var new_title = $("#event_url").val().replace(/&/g,"and");
    new_title = new_title.replace(/ /g,"-");
    new_title = new_title.replace(/[^a-zA-Z0-9_-]/g,"");
    var title = new_title.toLowerCase();
    // check if already in the database
    $("#event_url").val(title);
    checkUrl($("#event_url").val());
}

function updateTimezone(lat, lng, timestamp) {
    $.ajax('https://maps.googleapis.com/maps/api/timezone/json', {
        type: 'GET',
        data: {
            'location': lat+','+lng,
            'timestamp': timestamp,
            'sensor': false
        }
    }).done(function(data){
        if (data.status == 'OK') {
            $('#timezone').val((data.rawOffset/60));
        }
    });
}

$(document).ready(function() {
    // validate all fields on blur
    $('form input').blur(function() {
        $(this).parsley('validate');
    });
    
    // listener to check if url is available
    $('#event_url').on('keyup change blur', $.debounce(650, function() {
        sanitizeUrl();
    }));
    
    // listener to geocode location
    $('#event_location').on('blur keypress', function(e){
        if (e.type == 'blur' || (e.type == 'keypress' && e.which == 13)) {
            return geocoder($("#event_location").val());
        }
    });
    
    // listener to set url from title
    $("#event_title").blur( function() {
        $("#event_url").val($(this).val());
        sanitizeUrl();
    });

    $("#event-duration-type").change( function() {
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
    

    // show the google map when the address setting is changed
    $('#event_location').on('keypress', null, null, $.debounce(2000, false, function(event){
        if (event.type == 'keypress' && event.which != 13) {
            updateMap(this);
        }
    }));

    // handle the enter key
    $('#event_location').on('keypress', null, null, function(event){
        if (event.type == 'keypress' && event.which == 13) {
            updateMap(this);
            return false;
        }
    });

    // update map
    function updateMap(context) {
        $("#event_location").removeClass("good").removeClass("bad").addClass("spinner-16px");
        $.getJSON("https://maps.googleapis.com/maps/api/geocode/json?address=" + encodeURIComponent($(context).val()) + "&sensor=false", function(data) {
            // get the lat/lng of the address
            if ( data['results'][0]['geometry']['location'] ) {
                var lat = data['results'][0]['geometry']['location']['lat'];
                var lng = data['results'][0]['geometry']['location']['lng'];
                $("#lat").val(lat);
                $("#lng").val(lng);
                var timestamp = Math.round((new Date()).getTime() / 1000);
                updateTimezone(lat, lng, timestamp);
                // set spinner to checkmark
                $("#event_location").removeClass("spinner-16px").addClass("good");
            } else {
                $("#event_location").removeClass("spinner-16px").addClass("bad");
            }

            // display the google map
            var lat = $('#lat').val();
            var lng = $('#lng').val();
            var mapOptions = {
                center: new google.maps.LatLng(lat, lng),
                zoom: 15,
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                streetViewControl: false
            };
            var map = new google.maps.Map($('#map_canvas').get(0), mapOptions);
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
                $("#lat").val(point.lat());
                $("#lng").val(point.lng());
                map.panTo(point);

                var timestamp = Math.round((new Date()).getTime() / 1000);
                updateTimezone(point.lat(), point.lng(), timestamp);
            });

            // display the map on initial load
            $('#map_canvas_container').slideDown();
        });
    }

    // setup the ajax form
    $('#eventForm').ajaxForm({
        success: function() {
            // show a notification
            $.pnotify({
                type: 'success',
                title: 'Event Created',
                text: 'The event has been successfully created.'
            });

            // reset the form
            $('#eventForm').resetForm();
        },
        error: function(){ 
            // show a notification
            $.pnotify({
                type: 'error',
                title: 'Event Not Created',
                text: 'An error occurred while trying to create the event.'
            });
        }
    });
});