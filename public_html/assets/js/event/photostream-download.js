function downloadAlbum() {
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
    }).fail(function(jqXHR, textStatus, errorThrown) {
        if (jqXHR.status == 409) {
            $.pnotify({
                type: 'error',
                title: 'Unable to Process Request',
                text: 'Our team of robots are already processing your request. You can request them to generate the download link again later.'
            });
        } else {
            $.pnotify({
                type: 'error',
                title: 'Unable to Process Request',
                text: 'Well this is embarassing. If the problem persists, please contact us.'
            });
        }
    });
}