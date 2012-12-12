// document load
$(document).ready(function(){
    metric_signups();



    $('#metrics-range form input[type="radio"]').change(function(){
        metric_signups();
    });
});


// functions
function metric_signups() {
    var start = get_unix_start();
    $.getJSON('/ajax_internal/total_signups/'+start, function(json){
        $('#metric-signups .value').html(json.meta.total_count);
    });
}

// time helping function
function get_unix_start() {
    // get current unix
    var current_unix = Math.round(new Date().getTime() / 1000);

    // get the start
    var days = $('#metrics-range form input[name="metrics-range"]:checked').val();
    var start_unix = (days > 0) ? (current_unix - (days * 86400)) : 0;
    return start_unix;
}

function get_unix_end() {
    return Math.round(new Date().getTime() / 1000);
}