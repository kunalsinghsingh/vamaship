@extends('admin.layouts.default')
@section('content')
<link rel="stylesheet" href="public/admin/styles/custome.css">
<div class="main-container clearfix nav-horizontal">
    <!-- main-navigation -->
    <div ng-include="'menu.html'"></div>
    <!-- #end main-navigation -->

    <!-- content-here -->

    <div class="content-container" id="content">
        <div class="page page-ui-tables">
            <ol class="breadcrumb breadcrumb-small flt_left">
                <li>Home</li>
                <li class="active"><a href="{{URL::route('user')}}">Users</a></li>
            </ol>
            
			<div class="flt_right col-md-1">
                <a href="{{URL::route('addUser')}}" class="btn btn-info form-control  waves-effect" md-ink-ripple="">Add User<div class="md-ripple-container"></div></a>
            </div>
            <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">User Details</h4>
                        </div>
                        <div class="modal-body model_content">
                            ...
                        </div>
                    
                    </div>
                </div>
            </div>



           

            <div class="page-wrap">
                <!-- row -->
                <div class="row">
                    <!-- Basic Table -->

                    <div class="clearfix"></div>
                     @if(Session::get('save'))
                    <div class="alert alert-info text-success">{{Session::get('save')}}</div>
                    @endif
                    <!-- Data Table -->
                    <div class="col-md-12">
                        <div class="panel panel-lined panel-hovered mb20" style="padding-bottom: 80px">

                            <div class="panel-body">
                                <!--<div class="col-md-1"><h3>Filter</h3></div>-->
                                <div class="col-md-10">
                                    <form class="form-inline" action="{{URL::route('user')}}">


                                    </form>
                                </div>

                            </div>	

                            <!-- data table -->
                            <div class=" table-responsive">
                                <table class="table table-bordered table-striped Ntable"  id="DataTables_Table_0" role="grid" aria-describedby="DataTables_Table_0_info">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                           
                                            <th>Email</th>
                                            <th>Contact No.</th>
                                            <th style="width: 120px !important;">Action</th>
                                            
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @if($users)			
                                        @foreach($users as $data)

                                        <tr>
                                            
                                            <td>{{@$data -> first_name.' '.@$data -> last_name}}</td>
                                            
                                            <td>{{@$data -> email}}</td>
                                            <td>{{@$data -> mobile}}</td>
                                            
                                            <td>
                                                <a href="{{URL::route('editUser')}}/{{$data -> id}}" class="btn btn-info smbtn" title="Edit"><div class="fa fa-edit"></div></a>
                                                <a href="javascript:;" onclick="deleteUser({{$data -> id}})" class="btn btn-danger smbtn" title="Delete"><div class="fa fa-trash"></div></a>
                                            </td>
                                            
                                        </tr>	
                                        @endforeach
                                        @endif

                                    </tbody>
                                </table>
                            </div>
                            <!-- #end data table -->



                            <div class="dataTables_paginate ">
                                <div>{!! $users->render() !!}</div>
                            </div>

                        </div>



                    </div>	


                </div>
                <!-- #end row -->
            </div> <!-- #end page-wrap -->
        </div>


    </div>
    <!-- #end content-container -->

</div> 
@stop
@section('myscript')

<link rel="stylesheet" href="{{URL::asset('public/admin/styles/custome.css')}}">
<script src="{{URL::asset("public/admin/scripts/AdminModule/adminUserModule.js")}}"></script>
<!--<script src="{{URL::asset('public/admin/scripts/AdminModule/userPic.js')}}"></script>-->
@stop