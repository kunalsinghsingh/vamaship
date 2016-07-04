$(document).ready(function()
{
    get_total_completion();
    $(".expected_completion_date").datepicker({
        format: 'yyyy-mm-dd'
    });
    $(".actual_completion_date").datepicker({
        format: 'yyyy-mm-dd'
    });
    $("#project_form").submit(function(e)
    {
        e.preventDefault();
        $(".btn_submit_loader").html('Please Wait....')
        $("#btn_submit").hide();
        var form_data = $(this).serialize()

        $.ajax({
            type: "POST",
            url: SITE_URL + "building/save",
            dataType: "json",
            data: form_data,
            success: function(message)
            {
                var val = eval(message);

                if (val.error == 'success')
                {
                    $(".btn_submit_loader").html('Redirecting....')
                    var page = '';
                    if (val.limit && val.limit != 1)
                        page = '?page=' + val.limit;
                    window.location.href = SITE_URL + 'building' + page;

                } else {
                    $(".btn_submit_loader").html('')
                    $("#btn_submit").show();
                    $.each(val, function(index, value)
                    {
                        var input_name = (index.split("_"));
                        if (value != "") {
                            if (value == "validation.unique_with")
                            {
                                value = "This Restaurant already exists";
                            }
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


    //$('#location').locationpicker();

    $(".restaurant_menu").submit(function(e)
    {

        e.preventDefault();

        $(".btn_submit_loader").html('Please Wait....')
        $(".btn_submit").hide();
        var form_data = $(this).serialize()
        $.ajax({
            type: "POST",
            url: SITE_URL + "admin/restaurant/save_menu",
            dataType: "json",
            data: form_data,
            success: function(msg)
            {

                var data = eval(msg);

                if (data.error == 'success')
                {
                    $(".btn_submit_loader").html('Redirecting....');
                    var page = '';
                    if (data.limit && data.limit != 1)
                        page = '?page=' + data.limit;
                    window.location.href = SITE_URL + 'admin/restaurant' + page;
                }
                else
                {
                    $.each(data, function(index, value)
                    {
                        var input_name = (index.split("_"));

                        if (value != "") {
                            $("#" + index).html(value);
                            $("." + input_name[0]).addClass("has-error");
                            $(".btn_submit_loader").html('')
                            $(".btn_submit").show();
                        }
                    });
                }

            }
        });

    });


    $("#slot_form").submit(function() {
        $.ajax({
            type: "POST",
            url: SITE_URL + "building/save_flats",
            dataType: "json",
            data: $(this).serialize(),
            success: function(message)
            {
                var val = eval(message);
                if (val.error == 'success')
                    location.reload();
//                var selectList = $("#location_id");
//                selectList.empty();
//                $.each(val.locations, function (index, value) {
//                    var option = $('<option>').text(value['name']).val(value['id']);
//                    selectList.append(option);
//                });
//                $("#location_id").trigger("chosen:updated");//trigger("liszt:updated");
//                $.each(val.resturant_id, function (ind, value1) {
//                    //$("#location_id").chosen().val(value1['location_id'])
//                });
            }
        });
    });
    $("#proposed_payment_slab_form").submit(function() {
        var total = 0;
        $('.completion').each(function() {
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
                success: function(message)
                {
                    var val = eval(message);
                    if (val.error == 'success')
                        location.reload();
                }
            });
        }

    });

    $('#master_form').submit(function() {
        var form_data = $(this).serialize()

        $.ajax({
            type: "POST",
            url: SITE_URL + "masters/save",
            dataType: "json",
            data: form_data,
            success: function(message)
            {
                var name = $('.heading').val();
                var table = $('.table').val();
                var val = eval(message);
                if (val.error == 'success') {
                    window.location.href = SITE_URL + 'masters/' + name + '/' + table;
                } else {
                    $.each(val, function(index, value)
                    {
                        var input_name = (index.split("_"));
                        $("#" + index).show();
                        $("#" + index).html(value);
                        $(".btn_submit_loader").html('')
                        $(".btn_submit").show();
                    });
                }
            }
        });
    })

    $('.completion').keyup(function() {
        get_total_completion();
    });
});
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
function get_total_completion() {
    var total = 0;
    $('.completion').each(function(index, value) {
        var completion = ($(this).val() == '') ? 0 : $(this).val();
        total += parseFloat(completion);
    });
    $('.total_completion').text(total);
}
function get_location_id(city_id, restaurant_id)
{
    $.ajax({
        type: "POST",
        url: SITE_URL + "admin/restaurant/get_location",
        dataType: "json",
        data: {'city_id': city_id, 'restaurant_id': restaurant_id},
        success: function(message)
        {
            var val = eval(message);
            var selectList = $("#location_id");
            selectList.empty();
            $.each(val.locations, function(index, value) {
                var option = $('<option>').text(value['name']).val(value['id']);
                selectList.append(option);
            });
            $("#location_id").trigger("chosen:updated");//trigger("liszt:updated");
            $.each(val.resturant_id, function(ind, value1) {
                //$("#location_id").chosen().val(value1['location_id'])
            });
        }
    });
}




function save_flats(id)
{
    var flat_id = $.trim(id.replace('save_', ''));
    $.ajax({
        type: "POST",
        url: SITE_URL + "save_flat",
        dataType: "json",
        data: {'city_id': city_id, 'restaurant_id': restaurant_id},
        success: function(message)
        {
            var val = eval(message);
            var selectList = $("#location_id");
            selectList.empty();
            $.each(val.locations, function(index, value) {
                var option = $('<option>').text(value['name']).val(value['id']);
                selectList.append(option);
            });
            $("#location_id").trigger("chosen:updated");//trigger("liszt:updated");
            $.each(val.resturant_id, function(ind, value1) {
                //$("#location_id").chosen().val(value1['location_id'])
            });
        }
    });
}

function delete_flat(id, building_id) {
    bootbox.confirm("Are you sure?", function(result) {
        if (result) {
            window.location.href = SITE_URL + 'building/delete_flat/' + id + '/' + building_id;
        }
    });
}

function add_slab(building_id) {
    $.ajax({
        type: "POST",
        url: SITE_URL + "building/add_slab",
        dataType: "json",
        data: {'building_id': building_id},
        success: function(message)
        {
            var val = eval(message);
            if (val.error == 'success')
                window.location.reload();
        }
    });
}

function delete_proposed_payment_slab(slab_id) {
    bootbox.confirm("Are you sure, want to delete this?", function(result) {
        if (result) {
            $.ajax({
                type: "POST",
                url: SITE_URL + "building/delete_slab",
                dataType: "json",
                data: {'slab_id': slab_id},
                success: function(message)
                {
                    var val = eval(message);
                    if (val.error == 'success')
                        window.location.reload();
                }
            });
        }
    });

}

