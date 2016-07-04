$(document).ready(function (){    $(".chosen-select").chosen();    $(document).on('click', '.remove_profile_pic', function (e)    {        e.preventDefault();        var r = confirm("Are you sure you want to Delete this?");        if (r == true)        {            var pic_id = $(this).attr("id");            $("." + pic_id).remove();        }    });    $(document).on('click', '#search_user', function (e) {        e.preventDefault();        var search_text = "text=" + $("#search_user_text").val();        $.ajax({            method: 'POST',            url: SITE_URL + 'admin/search_user',            dataType: 'html',            data: search_text,            success: function (msg)            {                // alert(msg);                $(".search_user_data").html(msg);            }        });    });    $("#user_form").submit(function (e)    {        e.preventDefault();        var form_data = $(this).serialize()        $.ajax({            type: "POST",            url: SITE_URL + "user/saveProfile",            dataType: "json",            data: form_data,            success: function (msg)            {                var data = eval(msg);                if (data.error == 'success')                {                    $(".btn_submit_loader").html('Redirecting....');                    var page = '';                    if (data.limit && data.limit != 1)                        page = '?page=' + data.limit;                    if(data.user_group == 'customer')                        window.location.href = SITE_URL + 'customer';                    else                        window.location.href = SITE_URL + 'user';                }                 else                {                    $.each(data, function (index, value)                    {                        var input_name = (index.split("-"));                        //if (value != "") {                        $("#" + index).html(value);                        //$("." + input_name[0]).addClass("has-error");                        $(".btn_submit_loader").html('')                        $("#btn_submit").show();                        //}                    });                }            }        });    });    $("#profileForm").submit(function (e)    {        e.preventDefault();        var form_data = $(this).serialize();        $.ajax({            type: "POST",            url: SITE_URL + "saveUserProfile",            dataType: "json",            data: form_data,            success: function (msg)            {                var data = eval(msg);                if (data.error == 'success')                {                    window.location.href = SITE_URL + 'dashboard';                } else                {                    if (data.oerror == 'oerror')                    {                        $(".old_password").addClass("has-error");                        $("#old_password-err").html("Please enter correct password");                    }                    if (data.nerror == 'nerror')                    {                        $(".password").addClass("has-error");                        $("#password-err").html("Please enter new password");                    }                    $.each(data, function (index, value)                    {                        var input_name = (index.split("-"));                        if (value != "") {                            $("#" + index).html(value);                            $("." + input_name[0]).addClass("has-error");                            $(".btn_submit_loader").html('')                            $("#btn_submit").show();                        }                    });                }            }        });    });    $('#userType').change(function () {        if ($(this).find('option:selected').text() == 'Broker') {            $('.broker_div').show();        } else {            $('.broker_div').hide();        }        //Channel         if ($(this).find('option:selected').text() == 'Channel') {            $('.channel_div').show();        } else {            $('.channel_div').hide();        }    });});function switch_user_login(switch_id){    var r = confirm("Are you sure to login behalf of this user?");    if (r == false) {        return false;    }    $.ajax({        type: "POST",        url: SITE_URL + "user/userSwitch",        dataType: "json",        data: {'switch_id': switch_id},        success: function (message)        {            var val = eval(message);            if (val.error == 'success') {                window.location.href = SITE_URL + 'dashboard';            } else {                alert(val.error);            }        }    });}function enabled_user(user_id, flag) {    $.ajax({        type: "POST",        url: SITE_URL + "user/enableUser",        dataType: "json",        data: {'user_id': user_id,            'enable_flag': flag},        success: function (message)        {            var val = eval(message);            if (val.error == 'success') {                location.reload();            }        }    });}function deleteUser(id) {    if (confirm('Are you sure want to delete user?')) {        $.ajax({            type: "POST",            url: SITE_URL + "deleteUser",            dataType: "json",            data: {'userId': id},            success: function (message)            {                var val = eval(message);                if (val.error == 'success') {                    location.reload();                }            }        });    }}function getUserDetails(id) {    $.ajax({        type: "POST",        url: SITE_URL + "user/ajaxUserDetail",        dataType: "html",        data: {userId: id},        success: function (message)        {            $(".model_content").html(message);        }    });}