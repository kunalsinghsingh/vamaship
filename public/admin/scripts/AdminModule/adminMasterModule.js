$(document).ready(function ()
{
    
    $('#master_form').submit(function () {
        var form_data = $(this).serialize();

        $.ajax({
            type: "POST",
            url: SITE_URL + "masters/save",
            dataType: "json",
            data: form_data,
            success: function (message)
            {
                var name = $('.heading').val();
                var table = $('.table').val();
                var val = eval(message);
                if (val.error == 'success') {
                    window.location.href = SITE_URL + 'masters/' + name + '/' + table;
                } else {
                    $.each(val, function (index, value)
                    {
                        var input_name = (index.split("_"));
                        $("#" + index).show();
                        $("#" + index).html(value);
                        $(".btn_submit_loader").html('');
                        $(".btn_submit").show();
                    });
                }
            }
        });
    });

    $('#folderCreationForm').submit(function () {
        var form_data = $(this).serialize();

        $.ajax({
            type: "POST",
            url: SITE_URL + "save-folder",
            dataType: "json",
            data: form_data,
            success: function (message)
            {
               
                var val = eval(message);
                if (val.error == 'success') {
                    window.location.reload();
                } else {
                   
                }
            }
        });
    });

    $('#is_primary').change(function(){
        if($(this).val()=='1')
        {
            $("#parent_div").hide();
            $("#cat_div").show();
            $("#parent_div select").attr('required',false);
            $("#cat_div select").attr('required',true);
        }
        else if($(this).val()=='0')
        {
            $("#parent_div").show();
            $("#cat_div").hide();
            $("#parent_div select").attr('required',true);
            $("#cat_div select").attr('required',false);
        }
    });
});

function downloadFile(folder,file){
    $.ajax({
        type: "GET",
        url: SITE_URL + "/downloadFile",
        dataType: "json",
        data: {
            folder: folder,
            file: file
        },
        success: function(message)
        {

        }
    });
}
//$('.delete_master').live('click', function() {
//    $.ajax({
//        type: "POST",
//        url: SITE_URL + "masters/delete_master",
//        dataType: "json",
//        data: {
//            id: $(this).attr('id'),
//            table_name: $(this).attr('table_name'),
//            heading: $(this).attr('heading')
//        },
//        success: function(message)
//        {
//            var val = eval(message);
//            if (val.error == 'success') {
//                window.location.href = SITE_URL + 'masters/' + $(this).attr('heading') + '/' + $(this).attr('table_name');
//            }
//        }
//    });
//});
$(function(){
    $("#is_primary").trigger('change');
});


