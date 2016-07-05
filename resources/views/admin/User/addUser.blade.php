<!DOCTYPE html>

@extends('admin.layouts.default')
@section('content')


<div class="main-container clearfix nav-horizontal">
    <!-- main-navigation -->
    <div ng-include="'menu.html'"></div>
    <!-- #end main-navigation -->

    <!-- content-here -->
    <?php
     $segment = Request::segment(4);
      $form_head = Request::segment(1);
  
    ?>
    <div class="content-container fixedHeader" id="content">
        <div class="page page-forms-elements">

            <ol class="breadcrumb breadcrumb-small flt_left">
                <li>Forms</li>
                <li class="active"><a href="">{{ucwords($form_head)}}</a></li>
            </ol>

           
            <div class="clearfix"></div>
            <div class="page-wrap">
                <!-- row -->
                <div class="row">




                    <div class="col-md-12">

                        <div class="panel panel-default panel-hovered panel-stacked mb30">
                            <div class="panel-heading">{{ucwords($form_head)}}</div>
                            <div class="panel-body">
                                <form role="form" class="form-horizontal" id="user_form"  method="post" > <!-- form horizontal acts as a row -->
                                    {!! csrf_field() !!}
                                    <!-- normal control -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <label> Name <span class="err_display">*</span> </label>
                                                <input type="text" class="form-control Nform" name="first_name" placeholder="First Name*" value="{{@$user->first_name}}">
                                                <span class="error_span err_display" id="first_name-err"></span>
                                            </div>

                                        </div> 
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label>Email </label>
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <input type="text" class="form-control Nform" name="email" placeholder="Email" value="{{@$user->email}}">
                                                <span class="error_span err_display" id="email-err"></span>
                                            </div>

                                        </div> 
                                    </div>
                                    <div class="col-md-6">
                                        <label>Contact No <span class="err_display">*</span> </label>
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <input type="text" class="form-control Nform" name="mobile" placeholder="Contact No*" value="{{@$user->mobile}}">
                                                <span class="error_span err_display" id="mobile-err"></span>
                                            </div>

                                        </div> 
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label>Password <span class="err_display">*</span> </label>
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <input type="password" class="form-control Nform" name="password" placeholder="Password*" value="">
                                                <span class="error_span err_display" id="password-err"></span>
                                            </div>

                                        </div> 
                                    </div>
                                
                                   
                                   

                                   
                                       
                                    
                                   
                                    <div class="clearfix"></div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <input type="hidden" value="{{@$user->id}}" class="form-control" id="a_id" name="id">
                                                <input type="hidden" value="{{$form_head}}" class="form-control" id="userType" name="userType">
                                                <button class="btn btn-primary mr5" type="submit">Submit</button>
                                                <button class="btn btn-default">Cancel</button>
                                            </div>	

                                        </div> 
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>



                </div>

            </div> <!-- #end page-wrap -->
        </div> <!-- #end page -->
    </div> <!-- #end content-container -->

</div> <!-- #end main-container -->




@stop
@section('myscript')

<link rel="stylesheet" href="{{URL::asset('public/admin/styles/custome.css')}}">
<script src="{{URL::asset("public/admin/scripts/AdminModule/adminUserModule.js")}}"></script>

@stop

<!-- Dev only -->
<!-- Vendors -->
