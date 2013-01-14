$(document).ready(function(){
    // SHARE MENU
    $("#event-nav-share").click(function(e) 
    {          
        e.preventDefault();
        $("#event-nav-menu-share").toggle();
        $("#event-nav-share").toggleClass("menu-open");
    });
    
    $("#event-nav-menu-share").mouseup(function() 
    {
        return false
    });
    $(document).mouseup(function(e) 
    {
        if($(e.target).parent("a#event-nav-share").length==0) {
            $("#event-nav-share").removeClass("menu-open");
            $("#event-nav-menu-share").hide();
        }
    });
});