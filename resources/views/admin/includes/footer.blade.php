<!-- Dev only -->
<!-- Vendors -->


<script src="{{URL::asset("public/admin/scripts/vendors.js")}}"></script>
<script src="{{URL::asset("public/admin/scripts/plugins/d3.min.js")}}"></script>
<script src="{{URL::asset("public/admin/scripts/jquery-ui-1.10.3.min.js")}}"></script>
<script src="{{URL::asset("public/admin/scripts/plugins/c3.min.js")}}"></script>
<script src="{{URL::asset("public/admin/scripts/plugins/screenfull.js")}}"></script>
<script src="{{URL::asset("public/admin/scripts/plugins/perfect-scrollbar.min.js")}}"></script>
<script src="{{URL::asset("public/admin/scripts/plugins/waves.min.js")}}"></script> 
<script src="{{URL::asset("public/admin/scripts/plugins/jquery.sparkline.min.js")}}"></script>
<script src="{{URL::asset("public/admin/scripts/plugins/jquery.easypiechart.min.js")}}"></script>
<script src="{{URL::asset("public/admin/scripts/plugins/bootstrap-rating.min.js")}}"></script>
<script src="{{URL::asset("public/admin/scripts/plugins/bootstrap-datepicker.min.js")}}"></script>
<script src="{{URL::asset("public/admin/scripts/jquery.datetimepicker.full.js")}}"></script>
<script src="{{URL::asset("public/admin/scripts/bootstrap-multiselect.js")}}"></script>
<script src="{{URL::asset("public/admin/scripts/plugins/chosen/chosen.jquery.js")}}"></script>
<script src="{{URL::asset('public/admin/scripts/plugins/plupload/plupload.full.js')}}"></script>
<script src="{{URL::asset('public/admin/scripts/plugins/ckeditor/ckeditor.js')}}"></script>
<script src="{{URL::asset("public/admin/scripts/app.js")}}"></script>
<script src="{{URL::asset("public/admin/scripts/moment.js")}}"></script>
<script src="{{URL::asset("public/admin/scripts/daterangepicker.js")}}"></script>
<script src="{{URL::asset("public/admin/scripts/c3.init.js")}}"></script>

<!--<script src="{{URL::asset('public/admin/scripts/AdminModule/common.js')}}"></script>-->
<script src="{{URL::asset("public/admin/scripts/index.init.js")}}"></script>
<script type="text/javascript">
//$(document).ready(function () {
//    
//$("#fixedHeader").prop('checked', false);
//$(".chosen-select").chosen();
//});

</script>


 <script src="http://code.highcharts.com/highcharts.js"></script>
<script src="http://code.highcharts.com/modules/exporting.js"></script>

	<script type="text/javascript">
	$(document).ready(function(){
	$('.bmultiselect').multiselect();
			$("#fixedHeader").prop('checked', false); 	
			$('input.daterange').daterangepicker({
				showDropdowns: true
				});
				
				
		});

	</script>
		<script>
	$(function () {
     $('#container1').highcharts({
         chart: {
            type: 'bar'
        },
        title: {
            text: ' '
        },
		
        subtitle: {
            text: ' '
        },
        xAxis: {
            categories: ['Aeon', 'Zeon', 'Pristine', 'Hillcrest','vcvxc'],
            title: {
                text: null
            } 
        },
        yAxis: {
            min: 0,
            title: {
                text: ' ',
                align: 'high'
            },
            labels: {
                overflow: 'justify'
            }
        },
        tooltip: {
            valueSuffix: ' millions'
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
            borderWidth: 2,
            backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
            shadow: true
        },
        credits: {
            enabled: false
        },
        series: [{
            name: 'New',
            data: [10, 35, 130, 203, 40],
			color:'#3F51B5'
        }, 
		{
            name: 'Call Back',
            data: [35, 41, 60, 45, 200],
			color:'#4CAF50'
        }, 
		{
            name: 'Interested',
            data: [50, 31, 200, 130, 160],
			color:'#f44336'
        }, 
		{
            name: 'Not Interested',
            data: [150, 80, 120, 100, 30],
			color:'#E91E63'
        }, 
		{
            name: 'Out of Budget',
            data: [120, 100, 30, 70, 140],
			color:'#38B4EE'
        }, 
        {
            name: 'Site Visit Planned',
            data: [60, 45, 200, 130, 160],
			color:'#20000b'
        }, {
            name: 'Site Visited',
            data: [200, 156, 80, 200, 180],
			color:'#ffb505'
        }, {
            name: 'Booked',
            data: [160, 200, 60, 120, 80],
			color:'#4e3415'
        }]
    });
});</script>
<script>
$(function(){
    var url = window.location.href;
    $("ul.nav-list").find('a[href="'+url+'"]').parent('li').addClass('active');
})
</script>
	<style>
	.inline-mid{
		display: inline-block;
		vertical-align: middle;
		    margin-bottom: 15px;}</style>