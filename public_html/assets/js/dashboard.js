$(document).ready(function() 
{  
	$('a[rel*=facebox]').facebox();
	
	$("input#event-url").click(function() { 
		$(this).select();
	});

});