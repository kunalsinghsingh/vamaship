@extends('admin.layouts.default')
@section('dashboard_content')

@stop
@section('myscript')
<link rel="stylesheet" href="{{URL::asset('public/admin/styles/custome.css')}}">
<script src="{{URL::asset('public/admin/scripts/highcharts.js')}}"></script>
<script src="{{URL::asset('public/admin/scripts/AdminModule/dashboardCharts.js')}}"></script>
@stop