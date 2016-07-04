$(document).ready(function ()
{
    $("#virtualNumberSearch").keyup(function () {
        searchVirtualNumbers();
    });
   
    $('.unlinkVnButton').on('click', function () {
        $(this).hide();
        $(this).parents('td').find('.unlinkRemoveButton').show();
//        $(this).parents('td').html('<button class="btn  btn-sm removeData"  vn_id="' + $(this).attr('v_id') + '" v_id="' + $(this).attr('vn_id') + '">Remove</button>')
    });

    $('.chosen-select').chosen();
    var startDate = new Date('01/01/2012');
    var FromEndDate = new Date();
    var ToEndDate = new Date();
    var currentDate = new Date();
    ToEndDate.setDate(ToEndDate.getDate() + 365);
    $('#from').datetimepicker({
        dayOfWeekStart: 1,
        format: 'd/m/Y',
        lang: 'en',
        disabledDates: ['1986/01/08', '1986/01/09', '1986/01/10'],
        startDate: currentDate,
        timepicker: false,
    });
    $('#to').datetimepicker({
        dayOfWeekStart: 1,
        format: 'd/m/Y',
        lang: 'en',
        disabledDates: ['1986/01/08', '1986/01/09', '1986/01/10'],
        startDate: currentDate,
        timepicker: false,
    });
    $("#addCampaignForm").submit(function (e)
    {
        e.preventDefault();

        var form_data = $(this).serialize()

        $.ajax({
            type: "POST",
            url: SITE_URL + "saveCampaign",
            dataType: "json",
            data: form_data,
            success: function (message)
            {
                var val = eval(message);

                if (val.error == 'success')
                {
                    $(".btn_submit_loader").html('Redirecting....');
                    var page = '';
                    if (val.limit && val.limit != 1)
                        page = '?page=' + val.limit;
                    window.location.href = SITE_URL + 'campaign';

                } else {
                    $(".btn_submit_loader").html('')
                    $("#btn_submit").show();
                    $.each(val, function (index, value)
                    {
                        var input_name = (index.split("_"));
                        if (value != "") {

                            $("#" + index).html(value);
                            $("." + input_name[0]).addClass("has-error");
                            $("#" + index).show();
                            $("#" + index).html(value);
                            $("html, body").animate({scrollTop: 0}, 100);
                        }
                    });
                }

            }
        });
    });


    $("#inquiryForm").submit(function () {
        if ($('#starttimer').val() == 1) {
            if (!$('.timer').val()) {
                alert('Please stop Timer');
                return false;
            }
        }
//        $(".btn_submit_loader").html("Please Wait");
//        $("#btn-sbmt").hide();
        $.ajax({
            type: "POST",
            url: SITE_URL + "inquirySave",
            dataType: "json",
            data: $(this).serialize(),
            success: function (message)
            {
                $("#btn-sbmt").show();
//                $(".btn_submit_loader").html("");
                var val = eval(message);
                if (val.error == 'success') {
                    if (val.user_type == 'sales_manager') {
                        window.location.href = SITE_URL + 'followup';
                    } else {
                        window.location.href = SITE_URL + 'inquiry';
                    }

                }
                else {
                    //$(".btn_submit_loader").html('')
                    //$("#btn_submit").show();
                    $.each(val, function (index, value)
                    {
                        var input_name = (index.split("_"));
                        if (value != "") {
//                            if (value == "validation.unique_with")
//                            {
//                                value = "This Restaurant already exists";
//                            }
                            $("#" + index).html(value);

                            $("." + input_name[0]).addClass("has-error");
                            $("#" + index).show();
                            $("#" + index).html(value);
                            $("html, body").animate({scrollTop: 0}, 100);
                        }
                    });
                }


            }
        });
    })

});


function click_to_call(customer_no) {
    if (confirm('Are you sure you want to call?')) {
        $.ajax({
            type: "POST",
            url: SITE_URL + "enquiry/click-to-call",
            dataType: "html",
            data: {customer_no: customer_no},
            success: function (message)
            {
                var msg = eval(message);
//                    alert(eval(message));
            }
        });
    }
}
function getAjaxBuildings(id)
{
    var projectId = id;
    $.ajax({
        type: "POST",
        url: SITE_URL + "inquiry/ajaxBuildings",
        dataType: "json",
        data: {projectId: projectId},
        success: function (message)
        {
            //building
            var val = eval(message);
            $('#building').empty();
            $('#building').append('<option value="">Buildings Name</option>');
            $.each(val.buildings, function (index, value) {
//                    /console.log(value.id);
                $('#building').append($('<option/>', {
                    value: value.id,
                    text: value.name
                }));
            });
        }
    });
}
$('.removeData').bind('click', function () {

    removeVirtualNumber($(this).attr('v_id'), $(this));
});
var url = SITE_URL + 'virtualNumberSearch';
//$("#virtualNumberSearch").autocomplete({
//    source: url,
//    minLength: 3,
//    width: 320,
//    max: 10,
//    success: function (data) {
//        alert(data);
//    }
//    ,
//    response: function (request, response) {
//        var data = eval(response);
//        var appendData = '';
//        console.log(data);
//        $.each(data, function (index, value) {
//            console.log(data.id);
//            appendData += '<label><input type="checkbox" class="vn_checked" vn_id="' + value.id + '" vn_number="' + value.vn_numbers + '"> <span>' + value.vn_numbers + '</span></label>'
//        });
//        $('#virtualNumbersDiv').html(appendData)
//
//    }
//});

function searchVirtualNumbers()
{
    $('#virtualNumbersDiv').empty();
    $.ajax({
        type: "POST",
        url: SITE_URL + "virtualNumberSearch",
        dataType: "json",
        data: {term: $("#virtualNumberSearch").val()},
        success: function (message)
        {
            var data = eval(message);
            var appendData = '';
            $.each(data, function (index, value) {
                appendData += '<div class="col-md-3" ><div class="ui-checkbox" ><label><input type="checkbox" class="vn_checked" vn_id="' + value.id + '" vn_number="' + value.vn_numbers + '"> <span>' + value.vn_numbers + '</span></label></div></div>'
            });
            $('#virtualNumbersDiv').html(appendData);
        }
    });
}
function getAjaxFlats(id)
{
    var building_id = id;
    $.ajax({
        type: "POST",
        url: SITE_URL + "inquiry/ajaxFlats",
        dataType: "json",
        data: {building_id: building_id},
        success: function (message)
        {
            var val = eval(message);
            $('#flat').empty();
            $('#flat').append('<option value="">Flat</option>');
            $.each(val.flats, function (index, value) {
                $('#flat').append($('<option/>', {
                    value: value.id,
                    text: value.custom_flat_id
                }));
            });
        }
    });

}
function getAjaxCity(id)
{
    var state_id = id;
    $.ajax({
        type: "POST",
        url: SITE_URL + "inquiry/ajaxCity",
        dataType: "json",
        data: {state_id: state_id},
        success: function (message)
        {
            var val = eval(message);
            $('#city').empty();
            $('#city').append('<option value="">Select</option>');
            $.each(val.city, function (index, value) {
                $('#city').append($('<option/>', {
                    value: value.id,
                    text: value.name
                }));
            });


        }
    });
}

function addVirtualNumbers() {

    var checked_numbers = new Array();
    $('.vn_checked').each(function (index, value) {
        if ($(this).is(':checked')) {
            checked_numbers[index] = new Array();
            checked_numbers[index][0] = $(this).attr('vn_number');
            checked_numbers[index][1] = $(this).attr('vn_id');
        }
    });
    $.ajax({
        type: "POST",
        url: SITE_URL + "addVnCampaign",
        dataType: "json",
        data: {checkedNumbers: checked_numbers,
        },
        success: function (message)
        {
            if (message == 1)
                location.reload();
        }
    });

}
function storeFormInSesseion() {

    var form_data = $('#addCampaignForm').serialize();
    $.ajax({
        type: "POST",
        url: SITE_URL + "addSessionData",
        dataType: "json",
        data: form_data,
        success: function (message)
        {

        }
    }
    );
}
function removeVirtualNumber(id, vnId, This) {

    $.ajax({
        type: "POST",
        url: SITE_URL + "removeVnCampaign",
        dataType: "json",
        data: {id: id,
            vnId: vnId},
        success: function (message)
        {
            var val = eval(message);
            if (val.error == 'success') {
                This.parents('tr').remove();
            }

        }
    });
}

 