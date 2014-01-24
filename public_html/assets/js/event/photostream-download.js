$(document).ready(function(){
    $('#downloadBTN').click(function() {
        $.ajax({
            url: '/ajax/post_event_zip/' + $('#event-top').data('event-id'),
            type: 'POST',
        }).done(function(data) {
            var json = $.parseJSON(data);
            $.pnotify({
                type: 'success',
                title: 'Download Request Received',
                text: 'Our team of robots are collecting all your photos and will email you once they are done.'
            });
        }).fail(function() {
            $.pnotify({
                type: 'error',
                title: 'Unable to Process Request',
                text: 'Well this is embarassing. If the problem persists, please contact us.'
            });
        });
    });
});