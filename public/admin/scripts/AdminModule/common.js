$(document).ready(function() {
$("#fixedHeader").prop('checked', false);
$(".chosen-select").chosen();
});
function close_modal(){
     $('.enquiry_modal').hide();
//     location.reload();
}
function get_new_enquiry() {
    $.ajax({
        type: "POST",
        url: SITE_URL + "enquiry/get_enquiry_popup",
        dataType: "html",
        data: {},
        success: function(message)
        {
           
            if(message!=''){
              
            $(".all_enquiry_content").html(message);
            $('.enquiry_modal').modal('show');
            }
           
        }
    });
}


 function set_enquiry_read(enquiry_id,read_flag){
      $.ajax({
        type: "POST",
        url: SITE_URL + "enquiry/set_enquiry_read",
        dataType: "html",
        data: {enquiry_id:enquiry_id,read_flag:read_flag},
        success: function(message)
        {
            
         
           
        }
    });
 }