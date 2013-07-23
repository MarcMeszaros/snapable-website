// pnotify defaults
$.pnotify.defaults.history = false;
$.pnotify.defaults.icon = false;
$.pnotify.defaults.animation = "slide";
$.pnotify.defaults.width = "100%";
$.pnotify.defaults.addclass = "stack-content-center";
$.pnotify.defaults.sticker = false;
$.pnotify.defaults.closer = false;
$.pnotify.defaults.stack = {"dir1": "down", "dir2": "right", "push": "bottom", "firstpos1": -1, "firstpos2": -1, "spacing1":0, "spacing2":0};

// init spinner
$(document).ready(function(){
    // defaults
    var spinner_defaults = {
      lines: 11, // The number of lines to draw
      length: 3, // The length of each line
      width: 2, // The line thickness
      radius: 3, // The radius of the inner circle
      corners: 1, // Corner roundness (0..1)
      rotate: 0, // The rotation offset
      direction: 1, // 1: clockwise, -1: counterclockwise
      color: '#000', // #rgb or #rrggbb
      speed: 1, // Rounds per second
      trail: 60, // Afterglow percentage
      shadow: false, // Whether to render a shadow
      hwaccel: false, // Whether to use hardware acceleration
      className: 'spinner', // The CSS class to assign to the spinner
      zIndex: 2e9, // The z-index (defaults to 2000000000)
      top: 'auto', // Top position relative to parent in px
      left: 'auto' // Left position relative to parent in px
    };

    // add the spinner
    $('span.spinner-wrap').each(function(){
        var options = $(this).data(); // get data params
        var settings = $.extend({}, spinner_defaults, options); // override defaults
        // calculate the wrap dimensions
        var box_size = (settings.length*2) + (settings.radius*2) + 4; // add a 4px padding
        $(this).css('width', box_size+'px');
        $(this).css('height', box_size+'px');
        // init the spinner
        new Spinner(settings).spin($(this).get(0));
        // place the spinner in the center of the wrap box
        $(this).children('.spinner').css('top', (box_size/2)+'px');
        $(this).children('.spinner').css('left', (box_size/2)+'px');
    });
});
