// pnotify defaults
$.pnotify.defaults.history = false;
$.pnotify.defaults.icon = false;
$.pnotify.defaults.animation = "slide";
$.pnotify.defaults.width = "100%";
$.pnotify.defaults.addclass = "stack-content-center";
$.pnotify.defaults.sticker = false;
$.pnotify.defaults.closer = false;
$.pnotify.defaults.stack = {"dir1": "down", "dir2": "right", "push": "bottom", "firstpos1": -1, "firstpos2": -1, "spacing1":0, "spacing2":0};

// spinner
$.fn.spin.presets = {
    tiny: { lines: 9, length: 3, width: 1, radius: 1 }, // 12px = (3*2) + (1*2) + 4
    small: { lines: 12, length: 3, width: 2, radius: 3 }, // 16px = (3*2) + (3*2) + 4
    medium: { lines: 12, length: 5, width: 3, radius: 5 }, // 24px = (5*2) + (5*2) + 4
    large: { lines: 12, length: 7, width: 4, radius: 7 }  // 32px = (7*2) + (7*2) + 4
}
$(document).ready(function(){
    // add the spinner
    $('span.spinner-wrap').each(function(){
        $(this).spin('small');
    });
});
