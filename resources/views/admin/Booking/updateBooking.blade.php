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
                            <form role="form" class="form-horizontal" action="/vamaship/booking/update/{{$user->id}}" method="post"> <!-- form horizontal acts as a row -->
                                    {!! csrf_field() !!}
                                    <!-- normal control -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <label>Book Title <span class="err_display">*</span> </label>
                                                <input type="text" class="form-control Nform" name="book_title" placeholder="Book Title*" value="{{@$user->book_title}}">
                                                <span class="error_span err_display" id="book_title-err"></span>
                                            </div>

                                        </div> 
                                    </div>



                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <label>Contact Person Name <span class="err_display">*</span> </label>
                                                <input type="text" class="form-control Nform" name="name" placeholder="Contact Person Name*" value="{{@$user->name}}">
                                                <span class="error_span err_display" id="name-err"></span>
                                            </div>

                                        </div> 
                                    </div>




                                    <div class="col-md-6">
                                        <label>Contact No.  </label>
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <input type="text" class="form-control Nform" name="mobile" placeholder="Contact no." value="{{@$user->mobile}}">
                                                <span class="error_span err_display" id="mobile-err"></span>
                                            </div>

                                        </div> 
                                    </div>
                                    <div class="col-md-6">
                                        <label>Address 1</label>
                                        <div class="form-group">
                                            <div class="col-md-12">
                                               <textarea class="form-control"   value="" id="exampleInputOfficeAddress" name="add1">{{isset($user->address1) ? $user->address1:''}}</textarea>
                                                <span class="error_span err_display" id="add1-err"></span>
                                            </div>

                                        </div> 
                                    </div>


                                     <div class="col-md-6">
                                        <label>Address 2</label>
                                        <div class="form-group">
                                            <div class="col-md-12">
                                               <textarea class="form-control"   value="" id="exampleInputOfficeAddress" name="add2">{{isset($user->address2) ? $user->address2:''}}</textarea>
                                                <span class="error_span err_display" id="add2-err"></span>
                                            </div>

                                        </div> 
                                    </div>


                                     <div class="col-md-6">
                                        <label>Address 3</label>
                                        <div class="form-group">
                                            <div class="col-md-12">
                                              <textarea class="form-control"   value="" id="exampleInputOfficeAddress" name="add3">{{isset($user->address3) ? $user->address3:''}}</textarea>
                                                <span class="error_span err_display" id="add3-err"></span>
                                            </div>

                                        </div> 
                                    </div>
                                    <div class="col-md-6">
                                        <label>pincode <span class="err_display">*</span> </label>
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <input type="text" class="form-control Nform" name="pincode" placeholder="Pincode*" value="{{@$user->pincode}}">
                                                <span class="error_span err_display" id="pincode-err"></span>
                                            </div>

                                        </div> 
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label>City <span class="err_display">*</span> </label>
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <input type="text" class="form-control Nform" name="city" placeholder="City" value="{{@$user->city}}">
                                                <span class="error_span err_display" id="city-err"></span>
                                            </div>

                                        </div> 
                                    </div>
                                    <div class="col-md-6">
                                        <label>State <span class="err_display">*</span> </label>
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <input type="text" class="form-control Nform" name="state" placeholder="state" value="{{@$user->state}}">
                                                <span class="error_span err_display" id="state-err"></span>
                                            </div>

                                        </div> 
                                    </div>
                                   
                                   

                                   
                                       
                                   
                                    <div class="clearfix"></div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <input type="hidden" value="{{@$user->id}}" class="form-control" id="a_id" name="id">
                                                <input type="hidden" value="{{$form_head}}" class="form-control" id="userType" name="userType">
                                                <button class="btn btn-primary mr5" type="submit" id="booking_submit">Submit</button>
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




<div class="site-settings clearfix hidden-xs" style="visibility:hidden;">
    <div class="settings clearfix">
        <div class="trigger ion ion-settings left"></div>
        <div class="wrapper left">
            <ul class="list-unstyled other-settings">
                <li class="clearfix mb10">
                    <div class="left small">Nav Horizontal</div>
                    <div class="md-switch right">
                        <label>
                            <input type="checkbox" id="navHorizontal"> 
                            <span>&nbsp;</span> 
                        </label>
                    </div>


                </li>
                <li class="clearfix mb10">
                    <div class="left small">Fixed Header</div>
                    <div class="md-switch right">
                        <label>
                            <input type="checkbox"  id="fixedHeader"> 
                            <span>&nbsp;</span> 
                        </label>
                    </div>
                </li>
                <li class="clearfix mb10">
                    <div class="left small">Nav Full</div>
                    <div class="md-switch right">
                        <label>
                            <input type="checkbox"  id="navFull"> 
                            <span>&nbsp;</span> 
                        </label>
                    </div>
                </li>
            </ul>
            <hr/>
            <ul class="themes list-unstyled" id="themeColor">
                <li data-theme="theme-zero" class="active"></li>
                <li data-theme="theme-one"></li>
                <li data-theme="theme-two"></li>
                <li data-theme="theme-three"></li>
                <li data-theme="theme-four"></li>
                <li data-theme="theme-five"></li>
                <li data-theme="theme-six"></li>
                <li data-theme="theme-seven"></li>
            </ul>
        </div>
    </div>
</div>
@stop
@section('myscript')

<link rel="stylesheet" href="{{URL::asset('public/admin/styles/custome.css')}}">
<script src="{{URL::asset("public/admin/scripts/AdminModule/adminUserModule.js")}}"></script>
<script src="{{URL::asset('public/admin/scripts/AdminModule/userPic.js')}}"></script>
@stop

<!-- Dev only -->
<!-- Vendors -->
