$(document).ready(function(){
    // TABLE CARDS
    $('#tablecards a.download').click(function(){
        ga('send', 'event', 'Downloads', 'PDF');
        _gaq.push(['_trackEvent', 'Downloads', 'PDF']); // track the download as an analytics event
    });

    $('#tableBTN').click(function() {
        $('.slidContent[id!="tablecards"]').slideUp();
        if ($("#tablecards").hasClass('hide')) {
            $("#tablecards").removeClass("hide").hide().slideDown();
        } else {
            $("#tablecards").slideToggle();
        }
        return false;
    });
});