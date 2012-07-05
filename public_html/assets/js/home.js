$(document).ready(function() 
{  
	
	var windowH = $(window).height();
	var windowW = $(window).width();
	// $(window).resize(function() { });
	$(window).bind("load resize", function(){
		
		// get windowWidth
		// if width has max-width: 960px set as X, if min-width: 961px set as y and if min-width: 1280px set as z
		// set height variable based on width
		
		var theWidth = 1073;
		var theHeight = 504;
		var theMaxHeight = 845;
		
		if ( windowW <= 640 )
		{
			// MOBILE
			theMaxHeight = 345;
		}
		else if ( windowW > 640 && windowW <= 980 )
		{
			// MOBILE
			theMaxHeight = 515;
		} 
		else if ( windowW > 980 && windowW <= 1024 )
		{
			// small/medium resolution laptop
			theMaxHeight = 495;
		}
		else if ( windowW >=1024 && windowW <= 1280 )
		{
			// small/medium resolution laptop
			theMaxHeight = 625;
		}
		else if ( windowW >=1280 && windowW <= 1400 )
		{
			// small/medium resolution laptop
			theMaxHeight = 685;
		}
		
		$('body').bgStretcher({
			images: ['assets/img/home.jpg'],
			imageWidth: theWidth, 
			imageHeight: theHeight, 
			maxHeight: theMaxHeight,
			resizeProportionally: true,
			slideShow: false,
			anchoring: 'left top',
			anchoringImg: 'left top'
		});
		
		if ( windowW <= 640 )
		{
			var theTop = theMaxHeight - 38;
		} else {
			var theTop = theMaxHeight - 108;
		}
		$("#restOfPageWrap").css({ "margin-top":theTop + "px" });
  	});
	
	var elemTop = $('#why-use').offset().top;
    var elemPos = elemTop  - $(window).scrollTop();
    
    if ( (windowW > 1280 && elemPos <= -812) || (windowW >=1024 && windowW <= 1280 && elemPos <= -640) || (windowW > 640 && windowW <= 980 && elemPos <= -420) ) //elemPos <= -812 || 
   {
       $("#headNav, #headTagline").css({"display":"inline"})
   }
   else if (windowW > 980 && windowW <= 1024 && elemPos <= -460)
   {
	   $("#headNav").css({"display":"inline"})
   }
    
    $(window).scroll(function() {
       var elemPos = elemTop  - $(window).scrollTop();
       
       if ( (windowW > 1280 && elemPos <= -812) || (windowW >=1024 && windowW <= 1280 && elemPos <= -640) || (windowW > 980 && windowW <= 1024 && elemPos <= -460) || (windowW > 640 && windowW <= 980 && elemPos <= -420) ) //elemPos <= -812 || 
       {
       		if ( windowW > 980 && windowW <= 1024 && elemPos <= -460 || (windowW > 640 && windowW <= 980 && elemPos <= -420) )
       		{
	       		$("#headNav").fadeIn("normal").css({ "display":"inline" });
       		} else {
	       		$("#headNav, #headTagline").fadeIn("normal").css({ "display":"inline" });		
       		}
	       $("#homeHeadWrap").css({ "-webkit-box-shadow": "0px 2px 5px rgba(102, 102, 102, 0.5)", "-moz-box-shadow":"0px 2px 5px rgba(102, 102, 102, 0.5)", "box-shadow":"0px 2px 5px rgba(102, 102, 102, 0.5)" });
       } else {
	       $("#headNav, #headTagline").fadeOut("normal");
	       $("#homeHeadWrap").css({ "-webkit-box-shadow": "none", "-moz-box-shadow":"none", "box-shadow":"none" });
       }
    });
	
});