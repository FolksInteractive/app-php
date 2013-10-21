$(document).ready(function(){

    $(".droplink a").click(function () {
        
            var path = $(this).parent();
            path.find(".droptext").toggle();
            
            if ( path.find(".droptext").css("display") != "none" ) {
                path.find(".plus").css("display", "none");
                path.find(".less").css("display", "inline-block");
            } else {
                path.find(".plus").css("display", "inline-block");
                path.find(".less").css("display", "none");
            }	
        }
    );
    
});