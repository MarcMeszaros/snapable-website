$(document).ready(function() 
{  
	$("ul#dash-tabs a").each(function(index) {
		if ( $(this).hasClass("active") )
		{
			var url = $(this).attr("href").substr(1);
			$("#tab-" + url).css({ "display":"block" });
		}
	});
	
	$('a[rel*=facebox]').facebox();
	
	$("input#event-url").click(function() { 
		$(this).select();
	});
	
	// TABS
	$(document).on("click", "ul#dash-tabs a", function() {
		var url = $(this).attr("href").substr(1);
		
		$("ul#dash-tabs a").removeClass("active");
		$(this).addClass("active");
		
		$(".dash-tabs-content").fadeOut("fast", function() 
		{
			$("#tab-" + url).fadeIn("fast");
		});
		return false;
	});
	
	$(".show-extended-info").click(function() {
		$(this).parents("h5").next(".dash-extended-info").slideToggle();
		return false;
	});

	//$("#guest-link-upload").on("click", function(event) {
	$(document).on("click", "#guest-link-upload", function(){ 
		$("#guests-choices").hide();
		$("#guests-upload").fadeIn("normal");
		return false;
	});

	$(document).on("click", "#guest-link-manual", function(){ 
		$("#guests-choices").hide();
		$("#guests-manual").fadeIn("normal");
		return false;
	});
	
	$(document).on("click", "#guests-file-how-to-csv-link", function(){ 
		$("#guests-file-how-to-csv").fadeIn("normal");
		return false;
	});
	
	$(document).on("click", ".guests-back-to-choices", function(){ 
		$("#guests-manual, #guests-upload").hide();
		$("#guests-choices").fadeIn();
		return false;
	});
	
	$(document).on("click", "#guests-upload-csv", function(){
		$("#guests-upload").html("<strong>Your guests have been uploaded.</strong><br />Would you like to compose an email to let them know to download the Snapable app? <a id='notify-guests-yes' href='#'>Yes</a> / <a id='notify-guests-no' href='#'>No</a>");
		return false;
	});
	
	$(document).on("click", "#notify-guests-yes", function(){
		$("#overlay-tabs-add").removeClass("active");
		$("#overlay-tabs-notify").addClass("active");
		$("#add-guests-wrap").fadeOut("fast", function()
		{
			$("#notify-guests").fadeIn("fast");
		});
		return false;
	});

});