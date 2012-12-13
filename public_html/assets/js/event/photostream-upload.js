$(document).ready(function(){
    /*** PHOTO UPLOADER ****/
    var errors="";
    
    $('#uploadArea').mfupload({
        type        : '',   //all types
        maxsize     : 2,
        post_upload : "/upload",
        folder      : "./here",
        ini_text    : "<div class='uploadText'>Drag files (or click) into this area to upload</div>",
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
});