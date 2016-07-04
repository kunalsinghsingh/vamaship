var apiKey = 'KKa29c9118cc08c9052b4356450fd107d6';
var userName = 'concerete_buildr';
var phoneName = $('meta[name=author]').attr("content");
var did = '912233574757';
for (instance in CKEDITOR.instances) {
        CKEDITOR.instances[instance].updateElement();
    }
$(document).ready(function() {
    StateCitySelected(); // for sangam mumbai and maharshtra selected.
    $('.chosen-select').chosen();
    $('#userProject_chosen').css('width', '371px')
        //$("#project").trigger('change');
    var startDate = new Date('01/01/2012');
    var FromEndDate = new Date();
    var ToEndDate = new Date();
    var currentDate = new Date();
    ToEndDate.setDate(ToEndDate.getDate() + 365);
    $(".email_template").click(function() {
        $("#custom_content_div").hide();
    })
    $("#custom_mail_template").click(function() {
        if ($(this).is(':checked') == true) $("#custom_content_div").show();
        else $("#custom_content_div").hide();
    });
    $('#cusotm_sms_template').click(function() {
        if ($(this).is(':checked') == true) $(".sms_text").show();
    });
    $(".sms_template").click(function() {
        $(".sms_text").hide();
    });
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
    $("#project_form").submit(function(e) {
        e.preventDefault();
        $(".btn_submit_loader").html('Please Wait....')
        $("#btn_submit").hide();
        var form_data = $(this).serialize()
        $.ajax({
            type: "POST",
            url: SITE_URL + "building/save",
            dataType: "json",
            data: form_data,
            success: function(message) {
                var val = eval(message);
                if (val.error == 'success') {
                    $(".btn_submit_loader").html('Redirecting....')
                    var page = '';
                    if (val.limit && val.limit != 1) page = '?page=' + val.limit;
                    window.location.href = SITE_URL + 'building' + page;
                } else {
                    $(".btn_submit_loader").html('')
                    $("#btn_submit").show();
                    $.each(val, function(index, value) {
                        var input_name = (index.split("_"));
                        if (value != "") {
                            if (value == "validation.unique_with") {
                                value = "This Restaurant already exists";
                            }
                            $("#" + index).html(value);
                            $("." + input_name[0]).addClass("has-error");
                            $("#" + index).show();
                            $("#" + index).html(value);
                            $("html, body").animate({
                                scrollTop: 0
                            }, 100);
                        }
                    });
                }
            }
        });
    });
    $('#checked_all_enquiry').click(function() {
        if ($(this).is(':checked')) {
            $('.enquiry_check').prop('checked', true);
        } else {
            $('.enquiry_check').prop('checked', false);
        }
    });
    $("#inquiryForm").submit(function() {
        if ($('#starttimer').val() == 1) {
            if (!$('.timer').val()) {
                alert('Please stop Timer');
                return false;
            }
        }
        var conf = true, user_id = $("#user_id").val();
        $.ajax({
            type: "POST",
            url: SITE_URL + "checkMobile",
            dataType: "JSON",
            data: {
                mobile: $("#mobile").val()
            },
            async: false,
            success: function(result) {
                var val = eval(result);
                if(val.error == 'error')
                {
                    if(user_id != val.id) 
                    {
                        conf = confirm("There's already a Customer named "+val.first_name+" "+val.last_name+" for the same Mobile number. Please confirm if you would like to update that customer's info. ");
                        user_id = val.id;
                    }

                }
            },
            error: function(e) {
                console.log(e.responseText);
            }
        });
        if(conf == true)
        {
            $(".btn_submit_loader").html("Please wait while we are redirecting....");
            $("#btn-sbmt").hide();

            $.ajax({
                type: "POST",
                url: SITE_URL + "inquirySave",
                dataType: "json",
                data: $(this).serialize() + "&user_id=" + user_id,
                success: function(message) {
                    $(".btn_submit_loader").html("");
                    $("#btn-sbmt").show();
                    var val = eval(message);
                    if (val.error == 'success') {
                        if (val.user_type == 'sales_manager') {
                            window.location.href = SITE_URL + 'followup';
                        } else {
                            window.location.href = SITE_URL + 'inquiry';
                        }
                    } else {
                        //$(".btn_submit_loader").html('')
                        //$("#btn_submit").show();
                        $.each(val, function(index, value) {
                            var input_name = (index.split("_"));
                            if (value != "") {
                                $("#" + index).html(value);
                                $("." + input_name[0]).addClass("has-error");
                                $("#" + index).show();
                                $("#" + index).html(value);
                                $("html, body").animate({
                                    scrollTop: 0
                                }, 100);
                            }
                        });
                    }
                }
            });
            $("#enquirypopup").modal('close');
        }
    });
    $(".inquiryForm").submit(function(){
        var conf = true, user_id = $("#user_id").val();
        $.ajax({
            type: "POST",
            url: SITE_URL + "checkMobile",
            dataType: "JSON",
            data: {
                mobile: $("#mobile").val()
            },
            async: false,
            success: function(result) {
                var val = eval(result);
                if(val.error == 'error')
                {
                    if(user_id != val.id) 
                    {
                        conf = confirm("There's already a Customer named "+val.first_name+" "+val.last_name+" for the same Mobile number. Please confirm if you would like to update that customer's info. ");
                        user_id = val.id;
                    }

                }
            },
            error: function(e) {
                console.log(e.responseText);
            }
        });
        
        if(conf == true)
        {
            $(".btn_submit_loader").html("Please wait while we are redirecting....");
            $("#btn-sbmt").hide();

            $.ajax({
                type: "POST",
                url: SITE_URL + "inquiryUpdate",
                dataType: "json",
                data: $(this).serialize() + "&user_id=" + user_id,
                success: function(message) {
                    $(".btn_submit_loader").html("");
                    $("#btn-sbmt").show();
                    var val = eval(message);
                    if (val.error == 'success') {
                        if(val.redirect == 'yes')
                        {
                            if (val.user_type == 'sales_manager') {
                                window.location.href = SITE_URL + 'followup';
                            } else {
                                window.location.href = SITE_URL + 'inquiry';
                            }
                        }
                        else
                        {
                            $("#li_name").html(val.name);
                            $("#li_budget").html(val.budget);
                            $("#li_source").html(val.source);
                            $("#li_project").html(val.project);
                            $("#li_mobemail").html(val.mob_email);
                            $("#myModal").modal('close');
                        }
                    } else {
                        $.each(val, function(index, value) {
                            var input_name = (index.split("_"));
                            if (value != "") {
                                $("#" + index).html(value);
                                $("." + input_name[0]).addClass("has-error");
                                $("#" + index).show();
                                $("#" + index).html(value);
                                $("html, body").animate({
                                    scrollTop: 0
                                }, 100);
                            }
                        });
                    }
                }
            });
            $(".closeModal").trigger('click');
        }
    });
    
    var url = SITE_URL + 'inquiry/searchUser';
    $("#customer_search").autocomplete({
        source: url,
        minLength: 3,
        width: 320,
        max: 10,
        select: function(event, ui) {
            $("#mobile").val(ui.item.mobile);
            $("#email").val(ui.item.email);
            $("#first_name").val(ui.item.first_name);
            $("#last_name").val(ui.item.last_name);
            $("#user_id").val(ui.item.id);
        }
    });
    if ($('#site_visit_done').val() == 1) {
        $("#site_visit_date").removeAttr("disabled");
        $("#site_visit_with").prop("disabled", false);
        $("#booking_done").removeAttr("disabled");
    }
    if ($('#booking_done').val() == 1) {
        $("#booking_date").removeAttr("disabled")
    }
    $('#priorityStatus').change(function() {
        if ($(this).find('option:selected').text() == 'Booked') {
            $('#inquiryStatus').val($(this).find('option:selected').text());
        }
    })
    $("#site_visit_date").datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        startDate: currentDate,
        dateFormat: "yy-mm-dd",
        timeFormat: "hh:mm:ss",
        step: 15
    });
    $("#site_visit_actual_date").datetimepicker({
        dayOfWeekStart: 1,
        minDate: currentDate,
        lang: 'en',
        startDate: currentDate,
        dateFormat: "yy-mm-dd",
        timeFormat: "hh:mm:ss",
        step: 15
    });
    $("#booking_date").datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        startDate: currentDate,
        dateFormat: "yy-mm-dd",
        timeFormat: "hh:mm:ss",
        step: 15
    });
    $("#actual_booking_date").datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        startDate: currentDate,
        dateFormat: "yy-mm-dd",
        timeFormat: "hh:mm:ss",
        step: 15
    });
    $("#followup_date").datetimepicker({
        dayOfWeekStart: 1,
        format: 'd/m/Y',
        lang: 'en',
        disabledDates: ['1986/01/08', '1986/01/09', '1986/01/10'],
        startDate: currentDate,
        timepicker: false,
    });
    $("#next_followup_date").datetimepicker({
        dayOfWeekStart: 1,
        minDate: currentDate,
        lang: 'en',
        disabledDates: ['1986/01/08', '1986/01/09', '1986/01/10'],
        startDate: '2016/05/11',
        dateFormat: "yy-mm-dd",
        timeFormat: "hh:mm:ss",
        step: 15
    });
    $('#site_visit_done').change(function() {
        setTimeout(function() {
            if ($('#site_visit_done').val() == 1) {
                $("#site_visit_date").removeAttr("disabled");
                $("#site_visit_date").attr('required', true);
                //                $("#site_visit_date").val(" ")
                $("#no_of_members").removeAttr("disabled");
                $("#site_visit_with").removeAttr("disabled");
                $("#site_visit_done_flag").removeAttr('disabled');
            } else {
                $("#site_visit_with").val("");
                $("#no_of_members").val(" ");
                $("#site_visit_date").attr('disabled', true);
                $("#site_visit_date").attr('required', false);
                $("#site_visit_with").attr('disabled', true);
                $("#no_of_members").attr('disabled', true);
                $("#site_visit_done_flag").attr('disabled', true);
            }
        }, 100);
    });
    if($("#site_visit_done_flag").val() == 1)
    {
        $("#site_visit_actual_date").attr('disabled', false);
        $("#site_visit_actual_date").attr('required', true);
    }
    $("#site_visit_done_flag").change(function() {
        if ($(this).val() == 1) {
            $("#site_visit_actual_date").attr('disabled', false);
            $("#site_visit_actual_date").attr('required', true);
        } else {
            $("#site_visit_actual_date").attr('disabled', true);
            $("#site_visit_actual_date").attr('required', false);
        }
    });
    $('#booking_planned').change(function() {
        setTimeout(function() {
            if ($('#booking_planned').val() == 1) {
                $("#booking_date").removeAttr("disabled");
                $("#booking_date").attr('required', true);
            } else {
                $("#booking_date").attr('disabled', true);
                $("#booking_date").attr('required', false);
            }
        }, 100)
    });
    $('#booking_done').change(function() {
        if ($('#booking_done').val() == 1) {
            $("#actual_booking_date").removeAttr("disabled");
            $("#actual_booking_date").attr('required', true);
        } else {
            $("#actual_booking_date").attr('disabled', true);
            $("#actual_booking_date").attr('required', false);
        }
    });
    $('#site_visit_with').change(function() {
        if ($(this).val() != 'alone' && $(this).val() != '') {
            $("#no_of_members").removeAttr("disabled")
        } else {
            $("#no_of_members").val('');
            $("#no_of_members").prop("disabled", true);
        }
    });
    $('#site_visit_with').trigger('change');
    $('.send_sms_btn').click(function() {
        $('.mob_no').val($(this).attr('data-mobile'));
        $('.client_name').val($(this).attr('data-client-name'));
    });
    $('.email_send_btn').click(function() {
        $('.inquiryId').val($(this).attr('data-inquiryId'));
        var projectBrochure = $(this).attr('data-projectbrochure');
        var html = "";
        if (projectBrochure != '') {
            html += "<br><label>Project Brochure:</label><br><input type='checkbox' value='" + projectBrochure + "'><span>" + projectBrochure + "</span>";
        }
        $("#project_brochure_div").html(html);
        var buildingId = $(this).attr('data-buildingid');
        $.ajax({
            type: 'GET',
            data: {
                buildingId: buildingId
            },
            url: SITE_URL + "getBuildingFloorPlans",
            success: function(result) {
                $("#floor_plan_div").html(result);
            },
            error: function(e) {
                console.log(e.responseText);
            }
        });
    });
    $('#customerSource').change(function() {
        if ($(this).find('option:selected').text() == 'Through Broker') {
            $('.agent_div').show();
            $("#contactSource").val(4);
        } else {
            $("#contactSource").val('');
            $('.agent_div').hide();
        }
    });
    $("#contactSource").change(function() {
        var source = $(this).val();
        if (source == '4') {
            $('.agent_div').show();
        } else {
            $('.agent_div').hide();
        }
    });
    
});

function StateCitySelected() {
    $('#state').val(2182);
    $('#state').trigger('change');
}

function click_to_call(customer_no) {
    if (confirm('Are you sure you want to call?')) {
        $.ajax({
            type: "POST",
            url: SITE_URL + "enquiry/click-to-call",
            dataType: "html",
            data: {
                customer_no: customer_no
            },
            success: function(message) {
                var msg = eval(message);
                //                    alert(eval(message));
            }
        });
    }
}

function sendSms() {
    //alert($('.mob_no').val());
    if($(".mob_no").val() == '')
    {
        alert('Please enter a mobile number');
    }
    else
    {
        $.ajax({
            type: "POST",
            url: SITE_URL + "inquiry/sendSms",
            dataType: "json",
            data: {
                mobile: $('.mob_no').val(),
                sms_text: $('.sms_text').val(),
                template: $('.sms_template:checked').val(),
                name: $('.client_name').val()
            },
            success: function(message) {
                var val = eval(message);
                if (val.error == 'success') {
                    location.reload();
                }
            }
        });
    }
}

function getAjaxBuildings(id) {
    var projectId = id;
    $.ajax({
        type: "POST",
        url: SITE_URL + "inquiry/ajaxBuildings",
        dataType: "json",
        data: {
            projectId: projectId
        },
        success: function(message) {
            //building
            var val = eval(message);
            $('#building').empty();
            $('#building').append('<option value="">Building' + "'" + 's Name</option>');
            $.each(val.buildings, function(index, value) {
                //                    /console.log(value.id);
                $('#building').append($('<option/>', {
                    value: value.id,
                    text: value.name
                }));
            });
        }
    });
}

function getAjaxCampaigns(id) {
    $.ajax({
        type: "POST",
        url: SITE_URL + "inquiry/ajaxCampaigns",
        dataType: "json",
        data: {
            projectId: id
        },
        success: function(message) {
            var val = eval(message);
            $('#campaigns').empty();
            $('#campaigns').append('<option value="">Campaigns' + "'" + 's Name</option>');
            $.each(val, function(index, value) {
                //                    /console.log(value.id);
                $('#campaigns').append($('<option/>', {
                    value: value.campaign_id,
                    text: value.name
                }));
            });
        }
    });
}

function getAjaxFlats(id) {
    var building_id = id;
    $.ajax({
        type: "POST",
        url: SITE_URL + "inquiry/ajaxFlats",
        dataType: "json",
        data: {
            building_id: building_id
        },
        success: function(message) {
            var val = eval(message);
            $('#flat').empty();
            $('#flat').append('<option value="">Flat</option>');
            $.each(val.flats, function(index, value) {
                var txt = (value.status == 4) ? ' (Tentative)' : '';
                $('#flat').append($('<option/>', {
                    value: value.id,
                    text: value.custom_flat_id + txt
                }));
            });
        }
    });
}

function getFlatType(id) {
    $.ajax({
        type: 'POST',
        url: SITE_URL + "inquiry/ajaxFlatType",
        data: {
            flat_id: id
        },
        success: function(result) {
            var data = jQuery.parseJSON(result);
            $("#flat_type option[value='" + data[0].flat_type + "']").attr('selected', true);
        },
        error: function(e) {
            console.log(e.responseText);
        }
    });
}

function getAjaxCity(id) {
    var state_id = id;
    $.ajax({
        type: "POST",
        url: SITE_URL + "inquiry/ajaxCity",
        dataType: "json",
        data: {
            state_id: state_id
        },
        success: function(message) {
            var val = eval(message);
            $('#city').empty();
            $('#city').append('<option value="">Select City</option>');
            $.each(val.city, function(index, value) {
                $('#city').append($('<option/>', {
                    value: value.id,
                    text: value.name
                }));
            });
            if ($('#state').find('option:selected').val() == '2182') {
                $('#city').val(3351);
            }
        }
    });
}

function saveQiickInquiry() {
    var conf = true,
        user_id = $("#user_id").val();
    $.ajax({
        type: "POST",
        url: SITE_URL + "checkMobile",
        dataType: "JSON",
        data: {
            mobile: $("#mobile").val()
        },
        async: false,
        success: function(result) {
            var val = eval(result);
            if (val.error == 'error') {
                conf = confirm("There's already a Customer named " + val.first_name + " " + val.last_name + " for the same Mobile number. Please confirm if you would like to update that customer's info. ");
                user_id = val.id;
            }
        },
        error: function(e) {
            console.log(e.responseText);
        }
    });
    if (conf == true) {
        $.ajax({
            type: "POST",
            url: SITE_URL + "inquirySave",
            dataType: "json",
            data: $('#quickInquiryForm').serialize() + "&user_id=" + user_id,
            success: function(message) {
                $("#btn-sbmt").show();
                //                $(".btn_submit_loader").html("");
                var val = eval(message);
                if (val.error == 'success') {
                    if (val.user_type == 'sales_manager') {
                        window.location.href = SITE_URL + 'followup';
                    } else {
                        window.location.href = SITE_URL + 'inquiry';
                    }
                } else {
                    //$(".btn_submit_loader").html('')
                    //$("#btn_submit").show();
                    $.each(val, function(index, value) {
                        var input_name = (index.split("_"));
                        if (value != "") {
                            $("#" + index).html(value);
                            $("." + input_name[0]).addClass("has-error");
                            $("#" + index).show();
                            $("#" + index).html(value);
                            $("html, body").animate({
                                scrollTop: 0
                            }, 100);
                        }
                    });
                }
            }
        });
    }
}

function saveBroker() {
    var form_data = $('#user_form').serialize();
    $.ajax({
        type: "POST",
        url: SITE_URL + "user/saveProfile",
        dataType: "json",
        data: form_data,
        success: function(msg) {
            var data = eval(msg);
            var editInquiryId = $('#editInquiryId').val();
            var brokerForm = $('#brokerForm').val();
            if (data.error == 'success') {
                $(".btn_submit_loader").html('Redirecting....');
                if (brokerForm == 'inquiryListing') {
                    window.location.href = SITE_URL + 'inquiry';
                } else {
                    if (editInquiryId != 0 && editInquiryId != undefined) {
                        window.location.href = SITE_URL + 'editInquiry/' + editInquiryId;
                    } else {
                        window.location.href = SITE_URL + 'addInquiry';
                    }
                }
            } else {
                $.each(data, function(index, value) {
                    var input_name = (index.split("-"));
                    //if (value != "") {
                    $("#" + index).html(value);
                    //$("." + input_name[0]).addClass("has-error");
                    $(".btn_submit_loader").html('')
                    $("#btn_submit").show();
                    //}
                });
            }
        }
    });
}

function getAjaxUserBySalesManager(id) {
    $.ajax({
        type: "POST",
        url: SITE_URL + "inquiry/ajaxUserBySalesManager",
        dataType: "json",
        data: {
            managerId: id
        },
        success: function(message) {
            var val = eval(message);
            $('#lead_user').empty();
            $('#lead_user').append('<option value="">Select</option>');
            if (val.users) $.each(val.users, function(index, value) {
                $('#lead_user').append('<option value="' + value.id + '">' + value.first_name + ' ' + value.last_name + '</option>');
                $('#lead_user').trigger("chosen:updated");
            });
        }
    });
}

function getAjaxProjectUser(id) {
    if (id != '') $("#other_interested_projects option[value='" + id + "']").remove();
    $.ajax({
        type: "POST",
        url: SITE_URL + "inquiry/ajaxProjectUsers",
        dataType: "json",
        data: {
            project_id: id
        },
        success: function(message) {
            var val = eval(message);
            //$('#lead_user').empty();
            $('#lead_user').empty().trigger('chosen:updated');
            //$('#lead_user').append('<option value="">Select</option>');
            $.each(val.users, function(index, value) {
                //$('#lead_user').append('<option value="' + value.id + '">' + value.first_name + '</option>');
                $('#lead_user').append($("<option/>", {
                    value: value.id,
                    text: value.first_name + ' ' + value.last_name
                }));
                $('#lead_user').trigger("chosen:updated");
            });
        }
    });
}

function get_ajax_project_sales_manager(id) {
    $.ajax({
        type: "POST",
        url: SITE_URL + "enquiry/ajax_project_sales_manager",
        dataType: "json",
        data: {
            project_id: id
        },
        success: function(message) {
            var val = eval(message);
            //$('#lead_user').empty();
            $('#designated_sales_manager').empty();
            $('#designated_sales_manager').append('<option value="">Select</option>');
            if (val.users) $.each(val.users, function(index, value) {
                $('#designated_sales_manager').append('<option value="' + value.id + '">' + value.first_name + ' ' + value.first_name + '</option>');
                //                $('#designated_sales_manager').append($("<option/>", {
                //                    value: value.id,
                //                    text: value.first_name
                //                }));
                //                $('#lead_user').trigger("chosen:updated");
            });
        }
    });
}

function get_ajax_followup_done($enquiry_id) {
    $.ajax({
        type: "POST",
        url: SITE_URL + "enquiry/ajax_followup_done",
        dataType: "json",
        data: {
            enquiry_id: $enquiry_id
        },
        success: function(message) {
            if (message == 'success') {
                location.reload();
            }
        }
    });
}

function get_inquiry(id, type) {
    $.ajax({
        type: "POST",
        url: SITE_URL + "inquiry/ajaxInquiry",
        dataType: "html",
        data: {
            enquiry_id: id
        },
        success: function(message) {
            $(".model_content").html(message);
            if (type == 'followup') {
                $('.edit_enquiry').hide();
            }
        }
    });
}

function transfer_enquiry() {
    var enquiry = new Array();
    $('.enquiry_check').each(function() {
        if ($(this).is(':checked')) {
            enquiry.push($(this).attr('data-enquiry-id'));
        }
    });
    if (enquiry.length > 0) {
        $('.transfer_enquiry_btn').attr('data-target', '.transfer_modal');
        return true;
    } else {
        $('.transfer_enquiry_btn').removeAttr('data-target', '.transfer_modal');
        alert('Please select atleast one inquiry');
        return false;
    }
}

function get_transfer_enquiry() {
    var enquiry = new Array();
    $('.enquiry_check').each(function() {
        if ($(this).is(':checked')) {
            enquiry.push($(this).attr('data-enquiry-id'));
        }
    });
    $.ajax({
        type: "POST",
        url: SITE_URL + "transferInquiry",
        dataType: "json",
        data: {
            enquiry_id: enquiry,
            user_id: $('.sales_user').val()
        },
        success: function(message) {
            location.reload();
        }
    });
}

function getInquiryExport(project, from, to, inquiryStatus, salesAgent, salesManager, inquirySource) {
    $.ajax({
        type: "POST",
        url: SITE_URL + "export",
        dataType: "json",
        data: {
            project: project,
            from: from,
            to: to,
            inquiryStatus: inquiryStatus,
            salesAgent: salesAgent,
            salesManager: salesManager,
            inquirySource: inquirySource,
        },
        success: function(message) {
            location.reload();
        }
    });
}

function deleteInquiry(inquiryId) {
    if (confirm('Are you sure you want to delete this inquiry?')) {
        $.ajax({
            type: "POST",
            url: SITE_URL + "deleteInquiry",
            dataType: "json",
            data: {
                inquiryId: inquiryId
            },
            success: function(message) {
                if (message == 1) {
                    location.reload();
                }
                else
                {
                    alert("Sorry inquiry cannot be deleted!");
                }
            }
        })
    }
}

function SendEmail() {
    for (instance in CKEDITOR.instances) {
        CKEDITOR.instances[instance].updateElement();
    }
    $.ajax({
        type: "POST",
        url: SITE_URL + "trigger-mail",
        dataType: "json",
        data: $('#emailModalForm').serialize(),
        success: function(message) {
            if (message == 1) {
                location.reload();
            }
            else
            {
                alert('Please enter the email ID');
            }
        }
    });
}

function clickToCall(number,id) {
    //number = '9769368033';
   // var txt;
   console.log(number+'>>>>'+phoneName);
    var r = confirm("Are you sure want to make this call?");
    if (r == true) {
        $.ajax({
            type: "GET",
            url: 'http://cloudagent.in/CAServices/PhoneManualDial.php?apiKey=' + apiKey + '&userName=' + userName + '&custNumber=' + number + '&phoneName=' + phoneName + '&did=' + did + '&uui='+id,
            dataType: "json",
            //data: $('#emailModalForm').serialize(),
            success: function(message) {
                //            if (message == 1) {
                // location.reload();
                //            }
            }
        });
    } else {
        //txt = "You pressed Cancel!";
    }
}
function getSecondary(primary, table)
{
    var parts = primary.split('-');
    if(parts[0] == 'sec')
    {
        $.ajax({
            type: 'POST',
            data: {primary : parts[1], table : table},
            url: SITE_URL+'get-secondary',
            success: function(result)
            {
                $("#"+table).html();
                $("#"+table).html(result);
            },
            error: function(e)
            {
                console.log(e.responseText);
            }
        })
    }
}