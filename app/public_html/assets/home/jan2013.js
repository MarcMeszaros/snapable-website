$(document).ready(function() 
{  
	$(".overlay").colorbox();

    // add the old price if strike through is possible
    if(Modernizr.csstransforms) {
        $('.package-big-bottom .old-price > .strike').css('transform', 'rotate(-15deg)');
        $('.package-big-bottom .old-price').removeClass('hide');
    }
	
});