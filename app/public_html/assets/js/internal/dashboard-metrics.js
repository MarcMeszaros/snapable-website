// document load
$(document).ready(function(){
    update_metrics();

    $('#metrics-refresh').click(function(){
        update_metrics();
    });
    $('#metrics-range form input[type="radio"]').change(function(){
        update_metrics();
    });
});

// simple wrapper to call all the metric update functions
function update_metrics() {
    metric_signups();
    past_events();
    photos_count();
    upcoming_events();
    past_events_photos();
    avg_event_photos();
    revenue();
}


// functions
function metric_signups() {
    var start = get_unix_start();
    $('#metric-signups .status').removeClass('hide');
    $('#metric-signups .value').hide();
    $.getJSON('/ajax_internal/total_signups/'+start, function(json){
        $('#metric-signups .value').html(json.meta.total_count);
        $('#metric-signups .status').addClass('hide');
        $('#metric-signups .value').fadeIn();
    });
}

function past_events() {
    var start = get_unix_start();
    $('#metric-past-events-count .status').removeClass('hide');
    $('#metric-past-events-count .value').hide();
    $.getJSON('/ajax_internal/past_events/'+start, function(json){
        $('#metric-past-events-count .value').html(json.meta.total_count);
        $('#metric-past-events-count .status').addClass('hide');
        $('#metric-past-events-count .value').fadeIn();
    });
}

function photos_count() {
    var start = get_unix_start();
    $('#metric-photos-count .status').removeClass('hide');
    $('#metric-photos-count .value').hide();
    $.getJSON('/ajax_internal/photos_count/'+start, function(json){
        $('#metric-photos-count .value').html(json.meta.total_count);
        $('#metric-photos-count .status').addClass('hide');
        $('#metric-photos-count .value').fadeIn();
    });
}

function upcoming_events() {
    var start = get_unix_start();
    $('#metric-upcoming-events .status').removeClass('hide');
    $('#metric-upcoming-events .value').hide();
    $.getJSON('/ajax_internal/upcoming_events/'+start, function(json){
        $('#metric-upcoming-events .value').html(json.meta.total_count);
        $('#metric-upcoming-events .status').addClass('hide');
        $('#metric-upcoming-events .value').fadeIn();
    });
}

function past_events_photos() {
    var start = get_unix_start();
    $('#metric-past-events-photos .status').removeClass('hide');
    $('#metric-past-events-photos .value').hide();
    $.getJSON('/ajax_internal/events_with_photo_count/1/'+start, function(json){
        $('#metric-past-events-photos .value').html(json.meta.total_count);
        $('#metric-past-events-photos .status').addClass('hide');
        $('#metric-past-events-photos .value').fadeIn();
    });
}

function avg_event_photos() {
    var start = get_unix_start();
    $('#metric-avg-event-photos .status').removeClass('hide');
    $('#metric-avg-event-photos .value').hide();
    $.getJSON('/ajax_internal/avg_event_photos/'+start, function(json){
        $('#metric-avg-event-photos .value').html(Math.round(json.metrics.avg*100)/100);
        $('#metric-avg-event-photos .status').addClass('hide');
        $('#metric-avg-event-photos .value').fadeIn();
    });
}

function revenue() {
    var start = get_unix_start();
    $('#metric-revenue .status').removeClass('hide');
    $('#metric-revenue .value').hide();
    $.getJSON('/ajax_internal/revenue/'+start, function(json){
        $('#metric-revenue .value').html('$'+Math.round(json.metrics.gross_revenue/100)+' | $'+Math.round(json.metrics.net_revenue/100));
        $('#metric-revenue .status').addClass('hide');
        $('#metric-revenue .value').fadeIn();
    });
}

// time helping function
function get_unix_days(days) {
    var current_unix = Math.round(new Date().getTime() / 1000);
    var days_unix = (current_unix + (days * 86400));
    return days_unix;
}

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