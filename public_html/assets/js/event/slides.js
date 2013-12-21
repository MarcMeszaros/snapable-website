// setup the code to do ajax calls and update the dom
function removeOldestSlide() {
    $('#slides .slides-container li').first().remove()
    $('#slides').superslides('update')
}

function addNewSlide(photoId, photoCaption) {
    var photoSrc = sprintf('/p/get/%1$s/orig', photoId);
    // get slide count
    var slideCount = $('#slides .slides-container li').length;

    // remove a slide if we have more than 50
    if (slideCount > 50) {
        removeOldestSlide();
    }

    //$('#slides .slides-container li').first().remove()
    $('#slides').superslides('update')
}

function updateSlides() {
    console.log('update slides call')
    // get slide count
    var slideCount = $('#slides .slides-container li').length;
}

function nextPhotoIfNotLast() {
    var slideCount = $('#slides .slides-container li').length;
    if ($('#slides').superslides('current') < (slideCount-1)) {
        $('#slides').superslides('animate', 'next')
    }
}

$(document).ready(function(){
    // start the slides
    $('#slides').superslides({
        //pagination: false
        //inherit_width_from: '#slides',
        //inherit_height_from: '#slides'
    });

    // try to automatically advance to the next slide
    setInterval(nextPhotoIfNotLast, 10000); // 10 sec

    // setup the code to do ajax calls and update the dom
    setInterval(updateSlides, 30000); // 30 sec
});