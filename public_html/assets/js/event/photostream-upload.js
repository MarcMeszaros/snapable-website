$(document).ready(function(){
    /*** PHOTO UPLOADER ****/
    var errors="";

    /*
    $('#uploadArea').mfupload({
        type        : '',   //all types
        maxsize     : 10, // max size in MB
        post_upload : "/upload",
        folder      : "./here",
        ini_text    : "<div class='uploadText'>Drag photos (or click) into this area to upload</div>",
        over_text   : "<div class='uploadText'>Drop file here</div>",
        over_col    : 'white',
        over_bkcol  : '#006699',
        
        init        : function(){       
            $("#uploadedArea").empty();
        },
        
        start       : function(result){ 
            $("#uploadedArea").css({"display":"block"}).append("<div id='FILE"+result.filename+"' class='files'>Uploading Photo <div class='filesSmText'>This will just take a moment</div><div id='PRO"+result.filename+"' class='bar'><span></span></div></div>");    
        },

        loaded      : function(result){
            $("#PRO"+result.filename).remove();
            var resultText = "Upload complete.";
            
            if( result.status != 200 )
            {
                resulttext = "Your photo didn't completely upload.";
            } else {
                jQuery.facebox({ ajax: '/upload/crop/' + result.image + '/' + result.width + '/' +result.height });
            }           
        },

        progress    : function(result){
            $("#PRO"+result.filename).css("width", result.perc+"%");
        },

        error       : function(error){
            errors += error.filename+": "+error.err_des+"\n";
        },

        completed   : function(){
            if (errors != "") {
                alert(errors);
                errors = "";
            }
        }
    });
    */

    // setup the ajax form
    $('#uploadArea form').ajaxForm({
        beforeSubmit: function(arr, $form, options) {
            $('#photo-upload-btn').hide();
            $('#photo-upload-spinner').removeClass('hide');
        },
        success: function(responseText, statusText, xhr, $form) {
            // parse the JSON text
            var result = $.parseJSON(responseText);
            // show the facebox
            jQuery.facebox({ ajax: '/upload/crop/' + result.image + '/' + result.width + '/' +result.height });

            // reset the form
            $('#uploadArea form').resetForm();

            // show stuff
            $('#photo-upload-btn').show();
            $('#photo-upload-spinner').addClass('hide');
        },
        error: function(){ 
            // show a notification
            $.pnotify({
                type: 'error',
                title: 'Image Not Uploaded',
                text: 'An error occurred while trying to upload your photo. Make sure the selected image is smaller than 10MB and is a JPEG.'
            });

            // show stuff
            $('#photo-upload-btn').show();
            $('#photo-upload-spinner').addClass('hide');
        }
    });

});