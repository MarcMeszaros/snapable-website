// setup the code to do ajax calls and update the dom
function removeOldestSlide() {
    $('#slides .slides-container li').first().remove()
    $('#slides').superslides('update')
}

function addNewSlide(photoId, caption) {
    var photoSrc = '/p/get/' + photoId + '/orig';
    var captionHTML = '';
    // get slide count
    var slideCount = $('#slides .slides-container li').length;

    // remove a slide if we have more than 50
    if (slideCount > 50) {
        removeOldestSlide();
    }

    if(caption.length > 0) {
        captionHTML = '<div class="container"><div class="contrast"><p>' + caption + '</p></div></div>'
    }
    $('#slides ul.slides-container').append('<li><img src="'+photoSrc+'" alt="'+caption+'" />'+captionHTML+'</li>');

    console.log(photoSrc + ' - ' + caption);
}

function updateSlides() {
    // get slide count
    var lastUpdateISO = new Date(window.lastUpdate).toISOString();
    var slideCount = $('#slides .slides-container li').length;

    $.ajax('/ajax_api/photo', {
        type: 'GET',
        data: {
            'created_at__gte': lastUpdateISO,// lastUpdateISO
            //'order_by': '-created_at',
            'streamable': 'true',
            'event': $('#slides').data('event_id')
        }
    }).done(function(data){
        var resp = $.parseJSON(data);
        for (index = 0; index < resp.objects.length; index++) {
            var photo_id_parts = resp.objects[index].resource_uri.split('/')
            var photo_id = photo_id_parts[photo_id_parts.length - 2];
            addNewSlide(photo_id, resp.objects[index].caption);
        }
        $('#slides').superslides('update');
    }).always(function(data){
        //$("#event_url").removeClass("spinner-16px");
    });

    window.lastUpdate = new Date().getTime();
}

function nextPhotoIfAvailable() {
    var slideCount = $('#slides .slides-container li').length;
    if ($('#slides').superslides('current') < (slideCount-1)) {
        $('#slides').superslides('animate', 'next')
    }
}

var pfx = ["webkit", "moz", "ms", "o", ""];
function RunPrefixMethod(obj, method) {
    
    var p = 0, m, t;
    while (p < pfx.length && !obj[m]) {
        m = method;
        if (pfx[p] == "") {
            m = m.substr(0,1).toLowerCase() + m.substr(1);
        }
        m = pfx[p] + m;
        t = typeof obj[m];
        if (t != "undefined") {
            pfx = [pfx[p]];
            return (t == "function" ? obj[m]() : obj[m]);
        }
        p++;
    }

}

$(document).ready(function(){
    window.lastUpdate = new Date().getTime();
    // start the slides
    $('#slides').superslides({
        pagination: false,
        //inherit_width_from: 'slides',
        //inherit_height_from: 'slides'
    });

    // try to automatically advance to the next slide
    setInterval(nextPhotoIfAvailable, 15000); // 15 sec

    // setup the code to do ajax calls and update the dom
    setInterval(updateSlides, 30000); // 30 sec

    var e = document.getElementById("slides");
    e.onclick = function() {
        if (RunPrefixMethod(document, "FullScreen") || RunPrefixMethod(document, "IsFullScreen")) {
            RunPrefixMethod(document, "CancelFullScreen");
        } else {
            RunPrefixMethod(e, "RequestFullScreen");
        }
    }

});