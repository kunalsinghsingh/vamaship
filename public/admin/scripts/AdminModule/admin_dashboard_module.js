$(document).ready(function()
{


    //get_ajax_flats($("#building").val());

    var startDate = new Date('01/01/2012');
    var FromEndDate = new Date();
    var ToEndDate = new Date();

    ToEndDate.setDate(ToEndDate.getDate() + 365);

    $('#master_from').datepicker({
        format: 'dd/mm/yyyy',
        weekStart: 1,
        startDate: '01/01/2012',
        endDate: FromEndDate,
        autoclose: true,
        onSelect: function(selected) {
            $("#master_to").datepicker("option", "minDate", selected)
        }

    })
            .on('changeDate', function(selected) {
                if (selected)
                    startDate = new Date(selected.date.valueOf());
                startDate.setDate(startDate.getDate(new Date(selected.date.valueOf())));
                $('#master_to').datepicker('setStartDate', startDate);
            });
    $('#master_to')
            .datepicker({
                format: 'dd/mm/yyyy',
                weekStart: 1,
                startDate: startDate,
                endDate: ToEndDate,
                autoclose: true,
                onSelect: function(selected) {
                    $("#master_from").datepicker("option", "maxDate", selected)
                }
            })
            .on('changeDate', function(selected) {
                if (selected)
                    FromEndDate = new Date(selected.date.valueOf());
                FromEndDate.setDate(FromEndDate.getDate(new Date(selected.date.valueOf())));
                $('#master_from').datepicker('setEndDate', FromEndDate);
            });

    $('#master_from2').datepicker({
        format: 'dd/mm/yyyy',
        weekStart: 1,
        startDate: '01/01/2012',
        endDate: FromEndDate,
        autoclose: true,
        onSelect: function(selected) {
            $("#master_to2").datepicker("option", "minDate", selected)
        }

    })
            .on('changeDate', function(selected) {
                if (selected)
                    startDate = new Date(selected.date.valueOf());
                startDate.setDate(startDate.getDate(new Date(selected.date.valueOf())));
                $('#master_to2').datepicker('setStartDate', startDate);
            });
    $('#master_to2')
            .datepicker({
                format: 'dd/mm/yyyy',
                weekStart: 1,
                startDate: startDate,
                endDate: ToEndDate,
                autoclose: true,
                onSelect: function(selected) {
                    $("#master_from2").datepicker("option", "maxDate", selected)
                }
            })
            .on('changeDate', function(selected) {
                if (selected)
                    FromEndDate = new Date(selected.date.valueOf());
                FromEndDate.setDate(FromEndDate.getDate(new Date(selected.date.valueOf())));
                $('#master_from2').datepicker('setEndDate', FromEndDate);
            });

    $('#master_from3').datepicker({
        format: 'dd/mm/yyyy',
        weekStart: 1,
        startDate: '01/01/2012',
        endDate: FromEndDate,
        autoclose: true,
        onSelect: function(selected) {
            $("#master_to3").datepicker("option", "minDate", selected)
        }
    })
            .on('changeDate', function(selected) {
                if (selected)
                    startDate = new Date(selected.date.valueOf());
                startDate.setDate(startDate.getDate(new Date(selected.date.valueOf())));
                $('#master_to3').datepicker('setStartDate', startDate);
            });
    $('#master_to3')
            .datepicker({
                format: 'dd/mm/yyyy',
                weekStart: 1,
                startDate: startDate,
                endDate: ToEndDate,
                autoclose: true,
                onSelect: function(selected) {
                    $("#master_from3").datepicker("option", "maxDate", selected)
                }
            })
            .on('changeDate', function(selected) {
                if (selected)
                    FromEndDate = new Date(selected.date.valueOf());
                FromEndDate.setDate(FromEndDate.getDate(new Date(selected.date.valueOf())));
                $('#master_from3').datepicker('setEndDate', FromEndDate);
            });

    $('#master_from4').datepicker({
        format: 'dd/mm/yyyy',
        weekStart: 1,
        startDate: '01/01/2012',
        endDate: FromEndDate,
        autoclose: true,
        onSelect: function(selected) {
            $("#master_to4").datepicker("option", "minDate", selected)
        }
    })
            .on('changeDate', function(selected) {
                if (selected)
                    startDate = new Date(selected.date.valueOf());
                startDate.setDate(startDate.getDate(new Date(selected.date.valueOf())));
                $('#master_to4').datepicker('setStartDate', startDate);
            });
    $('#master_to4')
            .datepicker({
                format: 'dd/mm/yyyy',
                weekStart: 1,
                startDate: startDate,
                endDate: ToEndDate,
                autoclose: true,
                onSelect: function(selected) {
                    $("#master_from4").datepicker("option", "maxDate", selected)
                }
            })
            .on('changeDate', function(selected) {
                if (selected)
                    FromEndDate = new Date(selected.date.valueOf());
                FromEndDate.setDate(FromEndDate.getDate(new Date(selected.date.valueOf())));
                $('#master_from4').datepicker('setEndDate', FromEndDate);
            });
    $('#master_from5').datepicker({
        format: 'dd/mm/yyyy',
        weekStart: 1,
        startDate: '01/01/2012',
        endDate: FromEndDate,
        autoclose: true,
        onSelect: function(selected) {
            $("#master_to5").datepicker("option", "minDate", selected)
        }
    })
            .on('changeDate', function(selected) {
                if (selected)
                    startDate = new Date(selected.date.valueOf());
                startDate.setDate(startDate.getDate(new Date(selected.date.valueOf())));
                $('#master_to4').datepicker('setStartDate', startDate);
            });
    $('#master_to5')
            .datepicker({
                format: 'dd/mm/yyyy',
                weekStart: 1,
                startDate: startDate,
                endDate: ToEndDate,
                autoclose: true,
                onSelect: function(selected) {
                    $("#master_from5").datepicker("option", "maxDate", selected)
                }
            })
            .on('changeDate', function(selected) {
                if (selected)
                    FromEndDate = new Date(selected.date.valueOf());
                FromEndDate.setDate(FromEndDate.getDate(new Date(selected.date.valueOf())));
                $('#master_from4').datepicker('setEndDate', FromEndDate);
            });
    $('.from_date').change(function() {
        validate_dates();
    });
    $('.to_date').change(function() {
        validate_dates();
    });

    function validate_dates() {
//        alert(123);
        var fromDate = $('.from_date').datepicker('getDate');
        var toDate = $('.to_date').datepicker('getDate');
        if (fromDate && toDate)
            if (fromDate >= toDate) {
                alert('Please select proper date range');
                $('.from_date, .to_date').val('');
                return false;
            }
    }
    var target = $('.show_graph').val();
//    alert(target);
    if (target)
        $('html,body').animate({
            scrollTop: $('#' + target).offset().top
        }, 'slow');
});

function get_ajax_sales_person(id, user_type, div)
{
    var project_id = id;
    $.ajax({
        type: "POST",
        url: SITE_URL + "dashboard/get_ajax_sales_person",
        dataType: "json",
        data: {projetc_id: project_id, user_type: user_type},
        success: function(message)
        {
            //building
            var option_show = '';
            if (user_type == 'sales_agent') {
                option_show = 'Sales Agent';
            } else if (user_type == 'sales_manager') {
                option_show = 'Sales Manager';
            }
//            alert(user_type)
            var val = eval(message);
            $('#' + div).empty();
            $('#' + div).append('<option value="">' + option_show + '</option>');
            $.each(val.data, function(index, value) {
//                    /console.log(value.id);
                $('#' + div).append($('<option/>', {
                    value: value.id,
                    text: value.first_name
                }));
            });
        }
    });
}

function get_ajax_flats(id)
{
    var building_id = id;
    $.ajax({
        type: "POST",
        url: SITE_URL + "enquiry/ajax_flats",
        dataType: "json",
        data: {building_id: building_id},
        success: function(message)
        {
            var val = eval(message);
            $('#flat').empty();
            $('#flat').append('<option value="">Select</option>');
            $.each(val.flats, function(index, value) {
//                    /console.log(value.id);
                $('#flat').append($('<option/>', {
                    value: value.id,
                    text: value.custom_flat_id
                }));
            });


        }
    });

}

function get_ajax_project_user(id) {
    $.ajax({
        type: "POST",
        url: SITE_URL + "enquiry/ajax_project_users",
        dataType: "json",
        data: {project_id: id},
        success: function(message)
        {
            var val = eval(message);
            //$('#lead_user').empty();
            $('#lead_user').empty().trigger('chosen:updated');
            //$('#lead_user').append('<option value="">Select</option>');
            $.each(val.users, function(index, value) {
                //alert(value.id);
                //$('#lead_user').append('<option value="' + value.id + '">' + value.first_name + '</option>');
                $('#lead_user').append($("<option/>", {
                    value: value.id,
                    text: value.first_name
                }));
                $('#lead_user').trigger("chosen:updated");
            });
        }
    });
}


function get_inquiry(id)
{
    $.ajax({
        type: "POST",
        url: SITE_URL + "enquiry/ajax_inquiry",
        dataType: "html",
        data: {enquiry_id: id},
        success: function(message)
        {
            $(".model_content").html(message);
        }
    });
}


