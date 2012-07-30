$(document).ready(function() 
{  

	/*** PHOTO UPLOADER ****/
	var errors="";
	
	$('#uploadArea').mfupload({
		
		type		: '',	//all types
		maxsize		: 2,
		post_upload	: "/upload",
		folder		: "./here",
		ini_text	: "<div class='uploadText'>Drag files (or click) into this area to upload</div>",
		over_text	: "<div class='uploadText'>Drop file here</div>",
		over_col	: 'white',
		over_bkcol	: '#006699',
        
		init		: function(){		
			$("#uploadedArea").empty();
		},
		
		start		: function(result){		
			$("#uploadedArea").append("<div id='FILE"+result.fileno+"' class='files'>Uploading Photo <div class='filesSmText'>This will just take a moment</div><div id='PRO"+result.fileno+"' class='bar'><span></span></div></div>");	
		},

		loaded		: function(result){
			$("#PRO"+result.fileno).remove();
			var resultText = "Upload complete.";
			if( result.status != 200 )
			{
				resulttext = "Your photo didn't completely upload.";
			}
			$("#FILE"+result.fileno).html(resultText).delay("1500").fadeOut("normal", function()
			{
				$("#photoArea").prepend("<div class='photo photoHidden'>" +
					"<div class='photo-overlay'>" +
						"<ul class='photo-share'>" +
						"<li><a class='photo-share-twitter' href='#'>Tweet</a></li>" +
						"<li><a class='photo-share-facebook' href='#'>Share</a></li>" +
						"<li><a class='photo-share-email' href='#'>Email</a></li>" +
					"</ul>" +
					"<div class='photo-buttons'>" +
						"<a class='button addto-prints' href='#'>Add to Prints</a>" +
					"</div>" +
					"<a class='photo-enlarge' href='/p/123' title='Enlarge'>Enlarge</a>" +
				"</div>" +
				"<img src='/assets/img/FPO/event-photo-1.jpg' />" +
				"<img class='photo-comment' title='Uncle Bob dancing up a storm on the dance floor.' src='/assets/img/icons/comment.png' /> Andrew D." +	
				"</div>");
				$("#photoArea .photo:first-child").fadeIn("fast");
			});			
		},

		progress	: function(result){
			$("#PRO"+result.fileno).css("width", result.perc+"%");
		},

		error		: function(error){
			errors += error.filename+": "+error.err_des+"\n";
		},

		completed	: function(){
			if (errors != "") {
				alert(errors);
				errors = "";
			}
		}
	});
	
	/**** OTHER ****/
	
	$(".photo-comment").tipsy({fade: true, live: true});
	
	$(".photo").hover(
	  function () {
	    $(".photo-overlay", this).fadeIn("fast");
	  },
	  function () {
	    $(".photo-overlay", this).fadeOut("fast");
	  }
	); 
	
	$("#uploadBTN").click( function()
	{
		$("#uploadArea").slideToggle("fast");
	});
	
	$(document).on("click", ".addto-album", function(){ 
		alert("Show album menu")
	});
	
	$(document).on("click", ".addto-prints", function(){ 
		var count = parseFloat($("#in-cart-number").html()) + 1;
		$("#in-cart-number").html(count);
		// store reference of photos id somewhere
	});
	
	// PRIVACY MENU
	$("#event-nav-privacy").click(function(e) {          
		e.preventDefault();
        $("#event-nav-menu-privacy").toggle();
		$("#event-nav-privacy").toggleClass("menu-open");
    });
	
	$("#event-nav-menu-privacy").mouseup(function() {
		return false
	});
	$(document).mouseup(function(e) {
		if($(e.target).parent("a#event-nav-privacy").length==0) {
			$("#event-nav-privacy").removeClass("menu-open");
			$("#event-nav-menu-privacy").hide();
		}
	});
	
	// SHARE MENU
	$("#event-nav-share").click(function(e) {          
		e.preventDefault();
        $("#event-nav-menu-share").toggle();
		$("#event-nav-share").toggleClass("menu-open");
    });
	
	$("#event-nav-menu-share").mouseup(function() {
		return false
	});
	$(document).mouseup(function(e) {
		if($(e.target).parent("a#event-nav-share").length==0) {
			$("#event-nav-share").removeClass("menu-open");
			$("#event-nav-menu-share").hide();
		}
	});
	
});