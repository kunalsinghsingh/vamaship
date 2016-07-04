$(document).ready(function () {
//    alert(123);

    $('.chosen-select').chosen();
    var startDate = new Date('01/01/2012');
    var FromEndDate = new Date();
    var ToEndDate = new Date();
    var currentDate = new Date();
    ToEndDate.setDate(ToEndDate.getDate() + 365);
    $('.from').datepicker({
        format: 'dd/mm/yyyy',
        weekStart: 1,
        startDate: '01/01/2012',
        endDate: FromEndDate,
        autoclose: true
    })
            .on('changeDate', function (selected) {
                startDate = new Date(selected.date.valueOf());
                startDate.setDate(startDate.getDate(new Date(selected.date.valueOf())));
                $('.to').datepicker('setStartDate', startDate);
            });
    $('.to')
            .datepicker({
                format: 'dd/mm/yyyy',
                weekStart: 1,
                startDate: startDate,
                endDate: ToEndDate,
                autoclose: true
            })
            .on('changeDate', function (selected) {
                FromEndDate = new Date(selected.date.valueOf());
                FromEndDate.setDate(FromEndDate.getDate(new Date(selected.date.valueOf())));
                $('.from').datepicker('setEndDate', FromEndDate);
            });
//    setTimeout(function () {
    getInquirySummaryPieChart();
    getInquiryDataBySource();
    getInquiryDataByProject();
    getLeadAnalysisChart();
    getBuildingPreview();
    getFollowupPopup();
   // getNewInquiryPopup()
//    }, 100);


    $('.inquirySummaryButton').click(function () {
        getInquirySummaryPieChart($('#InquiryCallForm').serialize());
    });
    $('.resetButton').click(function () {
        resetFilter($(this));
    });
    $('.SourceBarButton').click(function () {

        getInquiryDataBySource($('#InquirySourceForm').serialize());
    });
    // for sangam
    $('.ProjectBarButton').click(function () {

        getInquiryDataByProject($('#ProjectForm').serialize());
    });
    $('.leadSummaryButton').click(function () {

        getLeadAnalysisChart($('#LeadSummaryForm').serialize(), $('#dashboardType').val());
    });
    $('#buildingPrieviewButton').click(function () {
        getBuildingPreview($('#BuildingPreviewForm').serialize());
    });
    $('.leadDashboardTypeButton').click(function () {
        $('#dashboardType').val($('#dashboardSelect').find('option:selected').val());
        getLeadAnalysisChart($('#LeadDashboardTypeForm').serialize(), $('#dashboardType').val());
    });
});
function getInquirySummaryPieChart(data) {
    $.ajax({
        type: "POST",
        url: SITE_URL + "getInquirySummaryPieChart",
        dataType: "json",
        data: data,
        success: function (message)
        {
            displayInquirySummaryPieChart(message.inquiry);
            displayInquiryStatusPieChart(message.status);
        }
    });
}

function displayInquirySummaryPieChart(data) {
    $('#chartContainer').highcharts({
        credits: {
            enabled: false
        },
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie'
        },
        title: {
            text: 'Inquiries'
        },
        tooltip: {
            pointFormat: '{series.name}:<b>{point.y}</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: false,
                    format: '<b>{point.name}</b>: {point.y} ',
                    style: {
                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                    }

                },
                showInLegend: true
            }
        },
        series: [{
                name: 'Records',
                colorByPoint: true,
                data: data
            }]
    });
}
function getInquiryStatusPieChart() {
    $.ajax({
        type: "POST",
        url: SITE_URL + "getInquiryStatusPieChart",
        dataType: "json",
        data: {},
        success: function (message)
        {
            displayInquiryStatusPieChart(message);
        }
    });
}
function getInquiryDataBySource(data) {
    $.ajax({
        type: "POST",
        url: SITE_URL + "getInquiryDataBySource",
        dataType: "json",
        data: data,
        success: function (message)
        {
//            console.log(eval(message));

            displaySourceBarChart(message);
        }
    });
}
function getInquiryDataByProject(data) {
    $.ajax({
        type: "POST",
        url: SITE_URL + "getInquiryDataByProject",
        dataType: "json",
        data: data,
        success: function (message)
        {
//            console.log(eval(message));

            displayProjectBarChart(message);
        }
    });
}
function getBuildingPreview(data) {

    $.ajax({
        type: "POST",
        url: SITE_URL + "getBuildingPreview",
        dataType: "json",
        data: data,
        success: function (message)
        {

            var val = eval(message);
            var append_data = '';
            var append_div = '';
            var status_flat = '';
            var css_class = '';
            var append_label = '';
            $.each(val.flat_floors, function (index1, value1) {
                append_div += '<div class="row "><h4>Floor ' + value1.floor_no + '</h4><div class="flr1 div_floor_' + value1.floor_no + '"> <ul id="display-inline-block"> </ul> </div></div>';
            });
//            var append_data = '<div class="row "><h4>Floor '+ value1.floor_no +'</h4><div class="flr1 div_floor_' + value1.floor_no + '"> <ul id="display-inline-block"> </ul> </div></div>';
            $('.show_flats').html(append_div);
            $.each(val.flat_details, function (index, value) {
                var status_flat = value.flat_status;
                css_class = (status_flat != null) ? "bg_" + status_flat.toLowerCase() : '';
                $('.div_floor_' + value.floor_no).find('#display-inline-block').append('<li class="flt_' + value.flat_on_floor + ' ' + status_flat.toLowerCase() + '" style="margin-right: 3px;"><div class="iconcontainer"><div class="flt1"><div class=" nbox ' + css_class + '">' + value.flat_number + '</div> <ul id="icondisplay-inline-block"><li><i class="fa fa-file-o"></i></li> <li><a href="" onclick="displayFlatDetailsBuildingPreview(' + value.flat_id + ')"  data-toggle="modal" data-target="#myModal1"><i class="fa fa-expand"></i></a></li><li><i class="fa fa-file-o"></i></li> </ul> </div></div> </li>');
//                append_data += '<li class="flt_' + value.flat + '"><div class="nbox ' + css_class + '">' + value.flat_number + '</div> <div class="left-icon"><i class="fa fa-file-o"></i></div> <div class="right-icon"><i class="fa fa-file-o"></i></div> <div class="viewdetail"><a href="#" data-toggle="modal" data-target="#myModal"><i class="fa fa-expand"></i></a></div> </li>';
            });
//          
        }
    });
}
function getLeadAnalysisChart(data, chartName) {
    $.ajax({
        type: "POST",
        url: SITE_URL + "getLeadAnalysisChart",
        dataType: "json",
        data: data,
        success: function (message)
        {

            displayLeadAnalysisChart(message, chartName);
            displayLeadAnalysisPieChart(message.piechartData);
        }
    });
}

function displayInquiryStatusPieChart(data) {
    $('#chartContainer1').highcharts({
        credits: {
            enabled: false
        },
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie'
        },
        title: {
            text: 'Inquiry Status'
        },
        tooltip: {
            pointFormat: '{series.name}:<b>{point.y}</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: false,
                    format: '<b>{point.name}</b>: {point.y} ',
                    style: {
                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                    }

                },
                showInLegend: true
            }
        },
        series: [{
                name: 'Records',
                colorByPoint: true,
                data: data
            }]
    });
}
function displayLeadAnalysisPieChart(data) {
    $('#leadAnalysisPieChart').highcharts({
        credits: {
            enabled: false
        },
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie'
        },
        title: {
            text: 'Inquiry Status'
        },
        tooltip: {
            pointFormat: '{series.name}:<b>{point.y}</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: false,
                    format: '<b>{point.name}</b>: {point.y} ',
                    style: {
                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                    }

                },
                showInLegend: true
            }
        },
        series: [{
                name: 'Records',
                colorByPoint: true,
                data: data
            }]
    });
}

function displaySourceBarChart(data) {
    $('#barContainer').highcharts({
        credits: {
            enabled: false
        },
        chart: {
            type: 'column'
        },
        title: {
            text: ''
        },
        subtitle: {
            text: ''
        },
        xAxis: {
            categories: data.sourceName,
            crosshair: true
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Records'
            }
        },
        tooltip: {
            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                    '<td style="padding:0"><b>{point.y}</b></td></tr>',
            footerFormat: '</table>',
            shared: true,
            useHTML: true
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            }
        },
        series: data.sourceData
    });
}
function displayProjectBarChart(data) {
    $('#barProjectContainer').highcharts({
        credits: {
            enabled: false
        },
        chart: {
            type: 'column'
        },
        title: {
            text: ''
        },
        subtitle: {
            text: ''
        },
        xAxis: {
            categories: data.sourceName,
            crosshair: true
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Records'
            }
        },
        tooltip: {
            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                    '<td style="padding:0"><b>{point.y}</b></td></tr>',
            footerFormat: '</table>',
            shared: true,
            useHTML: true
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            }
        },
        series: data.sourceData
    });
}
function displayLeadAnalysisChart(data, chartName) {
    if (chartName) {
        var nameOfChart = chartName.toUpperCase() + ' WISE CHARTS';
    }
    $('#leadContainer').highcharts({
        chart: {
            type: 'bar'
        },
        title: {
            text: nameOfChart
        },
        subtitle: {
            text: ''
        },
        xAxis: {
            categories: data.Projects,
            title: {
                text: null
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Projects',
                align: 'high'
            },
            labels: {
                overflow: 'justify'
            }
        },
        tooltip: {
            valueSuffix: ' Records'
        },
        plotOptions: {
            bar: {
                dataLabels: {
                    enabled: true
                }
            }
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'top',
            x: -40,
            y: 80,
            floating: true,
            borderWidth: 1,
            backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
            shadow: true
        },
        credits: {
            enabled: false
        },
        series: data.SourceData
    })
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
            $('#building').append('<option value="">Building' + "'" + 's Name</option>');
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
function resetFilter(This) {
    var fullDate = new Date();
    var twoDigitMonth = ((fullDate.getMonth().length + 1) != 1) ? (fullDate.getMonth() + 1) : '0' + (fullDate.getMonth() + 1);
    var currentDate = fullDate.getDate() + "-" + twoDigitMonth + "-" + fullDate.getFullYear();
    var firstDate = 1 + "-" + twoDigitMonth + "-" + fullDate.getFullYear();
//    This.parents('form').find('.salesAgent').val('');
//    $.each(This.parents('form').find('.project').find('option'), function (index, value) {
//        if (value.value == '') {
//            $(this).prop('selected', true);
//        }
//    });
//    This.parents('form').find('.project').find('option:first').prop('selected', true);
//    This.parents('form').find('.project').prop('selectedIndex', 0);
    This.parents('form').find('.project').attr('selectedIndex', '-1');
    This.parents('form').find('.salesAgent').find('option:first').prop('selected', true);
    This.parents('form').find('.from').val(firstDate);
    This.parents('form').find('.to').val(currentDate);

}

function getFollowupPopup() {

    $.ajax({
        type: "POST",
        url: SITE_URL + "getFollowupPopup",
        dataType: "html",
        data: {},
        success: function (message)
        {
            if (message != 1) {
                $(".model_content").html(message);
                $(".followup_modal").modal('show');
            }
        }
    });
}
function getNewInquiryPopup() {
  $.ajax({
        type: "POST",
        url: SITE_URL + "getNewInquiryPopup",
        dataType: "html",
        data: {},
        success: function (message)
        {
            if (message != 1) {
                $(".Inquiry_model_content").html(message);
                $(".inquiryModal").modal('show');
            }
        }
    });
}

function displayFlatDetailsBuildingPreview(flatId){
     $.ajax({
        type: "POST",
        url: SITE_URL + "getFlatData",
        dataType: "html",
        data: {flatId: flatId},
        success: function(message)
        {
            $(".model_content_data").html(message);
        }
    });
}
