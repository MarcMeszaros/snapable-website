$(document).ready(function() 
{  
	$(".overlay").colorbox();

    // add the old price if strike through is possible
    if(Modernizr.csstransforms) {
        $('.package-big-bottom .old-price > .strike').css('transform', 'rotate(25deg)');
        $('.package-big-bottom .old-price').show();
    }
	
});