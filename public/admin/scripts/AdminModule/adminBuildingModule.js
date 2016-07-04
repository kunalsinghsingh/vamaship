$(document).ready(function ()
{


    $('.chosen-select').chosen();
//    $("#buildingForm").submit(function (e)
//    {
//        e.preventDefault();
//        $(".btn_submit_loader").html('Please Wait....')
//        $("#btn_submit").hide();
//        var form_data = $(this).serialize();
//
//        $.ajax({
//            type: "POST",
//            url: SITE_URL + "saveBuilding",
//            dataType: "json",
//            data: form_data,
//            success: function (message)
//            {
//                var val = eval(message);
//                if (val.error == 'success')
//                {
//                    $(".btn_submit_loader").html('Redirecting....')
//                    var page = '';
//                    if (val.limit && val.limit != 1)
//                        page = '?page=' + val.limit;
//                    window.location.href = SITE_URL + 'buildings' + page;
//
//                } else {
//                    $(".btn_submit_loader").html('')
//                    $("#btn_submit").show();
//                    $.each(val, function (index, value)
//                    {
//                        var input_name = (index.split("_"));
//                        if (value != "") {
//                            if (value == "validation.unique_with")
//                            {
//                                value = "This Restaurant already exists";
//                            }
//                            $("#" + index).html(value);
//
//                            $("." + input_name[0]).addClass("has-error");
//                            $("#" + index).show();
//                            $("#" + index).html(value);
//                            $("html, body").animate({scrollTop: 0}, 100);
//                        }
//                    });
//                }
//
//            }
//        });
//    });


    //$('#location').locationpicker();

    $("#proposed_payment_slab_form").submit(function () {
        var total = 0;
        $('.completion').each(function () {
            total += parseFloat(this.value)
        });
        var form_data = $(this).serialize()



        if (total < 100) {
            bootbox.alert("100% completion Error");
        } else if (total > 100) {
            bootbox.alert("More than 100% completion");
        } else {
            $.ajax({
                type: "POST",
                url: SITE_URL + "building/save_proposed_payment_slab",
                dataType: "json",
                data: form_data,
                success: function (message)
                {
                    var val = eval(message);
                    if (val.error == 'success')
                        location.reload();
                }
            });
        }

    });

    $('.add_more_flr_pln_btn').click(function () {

        var clone_row = $('.clone_row').clone().appendTo('.clone_data');
        clone_row.css('display', 'block');
        clone_row.removeClass('clone_row');
        buttonCount();
    });
});

function removeRow(This) {

    This.closest('.row').remove();
    buttonCount();
}
function save_flats(id)
{
    var flat_id = $.trim(id.replace('save_', ''));
    $.ajax({
        type: "POST",
        url: SITE_URL + "save_flat",
        dataType: "json",
        data: {'city_id': city_id, 'restaurant_id': restaurant_id},
        success: function (message)
        {
            var val = eval(message);
            var selectList = $("#location_id");
            selectList.empty();
            $.each(val.locations, function (index, value) {
                var option = $('<option>').text(value['name']).val(value['id']);
                selectList.append(option);
            });
            $("#location_id").trigger("chosen:updated");//trigger("liszt:updated");
            $.each(val.resturant_id, function (ind, value1) {
                //$("#location_id").chosen().val(value1['location_id'])
            });
        }
    });
}

function deleteFlat(id, buildingId) {
    if (confirm("Are you sure?")) {
        window.location.href = SITE_URL + 'building/deleteFlat/' + id + '/' + buildingId;
    }

}

function add_slab(building_id) {
    $.ajax({
        type: "POST",
        url: SITE_URL + "building/add_slab",
        dataType: "json",
        data: {'building_id': building_id},
        success: function (message)
        {
            var val = eval(message);
            if (val.error == 'success')
                window.location.reload();
        }
    });
}

function delete_proposed_payment_slab(slab_id) {
    bootbox.confirm("Are you sure, want to delete this?", function (result) {
        if (result) {
            $.ajax({
                type: "POST",
                url: SITE_URL + "building/delete_slab",
                dataType: "json",
                data: {'slab_id': slab_id},
                success: function (message)
                {
                    var val = eval(message);
                    if (val.error == 'success')
                        window.location.reload();
                }
            });
        }
    });

}

$("#slotForm").submit(function () {
    $.ajax({
        type: "POST",
        url: SITE_URL + "saveFlat",
        dataType: "json",
        data: $(this).serialize(),
        success: function (message)
        {
            var val = eval(message);
            if (val.error == 'success')
                location.reload();

        }
    });
});

function buttonCount() {
    var count = 0;
    $('.remove_row').each(function (index, value) {
        count++;

    });

    if ((count - 1) == 1) {
        $('.remove_row').css('display', 'none');
    } else {
        $('.remove_row').css('display', 'block');
    }

}