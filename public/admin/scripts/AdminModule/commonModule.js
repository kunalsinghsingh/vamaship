jQuery(document).ready(function(jQuery) {
    jQuery.ajaxSetup({
        beforeSend: function(xhr) {
            jQuery("#loaderDiv").css({
                "display": "block"
            });
        },
        error: function(x, status, error) {
            if (x.status == 401) {
                alert("Sorry, your session has expired. Please login again to continue");
                window.location.href = "/auth/login";
            }
            
            if (x.status == 403) {
                alert("Sorry, your session has expired. Please login again to continue");
                window.location.href = "/auth/login";
            } else {
                alert("An error occurred: " + x.status + "nError: " + error);
            }
            jQuery("#loaderDiv").css({
                "display": "none"
            });
        },
        complete: function(xhr) {
            jQuery("#loaderDiv").css({
                "display": "none"
            });
        }
    });
    
});