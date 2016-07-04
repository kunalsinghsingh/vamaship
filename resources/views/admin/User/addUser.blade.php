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
                                <form role="form" class="form-horizontal" id="user_form"  method="post" enctype="multipart/form-data"> <!-- form horizontal acts as a row -->
                                    {!! csrf_field() !!}
                                    <!-- normal control -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <label>First Name <span class="err_display">*</span> </label>
                                                <input type="text" class="form-control Nform" name="first_name" placeholder="First Name*" value="{{@$user->first_name}}">
                                                <span class="error_span err_display" id="first_name-err"></span>
                                            </div>

                                        </div> 
                                    </div>
                                    <div class="col-md-6">
                                        <label>Last Name  </label>
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <input type="text" class="form-control Nform" name="last_name" placeholder="Last Name" value="{{@$user->last_name}}">
                                                <span class="error_span err_display" id="last_name-err"></span>
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
                                    <div class="col-md-6">
                                        <label>Confirm Password <span class="err_display">*</span> </label>
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <input type="password" class="form-control Nform" name="password_confirm" placeholder="Confirm Password*" value="">
                                                <span class="error_span err_display" id="password_confirm-err"></span>
                                            </div>

                                        </div> 
                                    </div>
                                   
                                   

                                   
                                       <div class="col-md-12">
                                        <label for="exampleInputEmail1">Profile Picture</label>
                                        <div id="user_pic_thumb_image">
                                            @if(isset($user->profile_pic) && ($user->profile_pic != ""))
                                            <?php
                                            if (isset($user->profile_pic)) {
                                                $pic_id = (explode(".", $user->profile_pic));
                                            }
                                            ?>
                                            <div class="{{$pic_id[0]}}">
                                                @if(isset($user->profile_pic) && File::exists('uploads/user_pic/'.$user->profile_pic) )

                                                <img src="{{URL::to('/').'/uploads/user_pic/'.$user->profile_pic}}" height="100" width="100">
                                                <br>
                                                <a href="javascript:void();" class="remove_profile_pic" id="{{$pic_id[0]}}">Remove</a>
                                                <input type="hidden" name="profile_image" value="{{$user->profile_pic}}">

                                                @endif
                                            </div>
                                            @else

                                            <input type="hidden" name="profile_image" value="">
                                            @endif
                                        </div>
                                        <div class="col-md-12 form-group">

                                            <div id="user_pic_filelist" ></div>
                                            <div id="user_pic_container" class="fileUpload btn btn-primary">
                                                <!--<input type="file" style="margin-top:20px;"   id="user_pic_pickfiles" >-->
                                                <!--<div >-->
                                                <span>Choose</span>
                                                <input type="file" id="user_pic_pickfiles" class="choose" />
                                                <!--</div>-->
                                                <!--<label style="margin:5px 0 0 0">Select Image</label>-->
                                                <span class="error err_display" id="user_pic_image_err"></span>

                                            </div>
                                            <span id="name_err" class="error err_display"> </span>
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
<script type="text/javascript">
function select_manager() {
         var name=$("#userType").val();
         $.ajax({
            type: "POST",
            url: '../selectmanager',
            data: {'usergroup_name':name},
            success: function (data) {
              $("#manager").html(data);
               
            }
           
           }); 
      }

</script>
<link rel="stylesheet" href="{{URL::asset('public/admin/styles/custome.css')}}">
<script src="{{URL::asset("public/admin/scripts/AdminModule/adminUserModule.js")}}"></script>
<script src="{{URL::asset('public/admin/scripts/AdminModule/userPic.js')}}"></script>
@stop

<!-- Dev only -->
<!-- Vendors -->
