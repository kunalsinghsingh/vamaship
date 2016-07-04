<html>
    <head>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <meta name="description" content="Materia - Admin Template">
        <meta name="keywords" content="materia, webapp, admin, dashboard, template, ui">
        <meta name="author" content="solutionportal">
        <!-- <base href="/"> -->
        <title> Sangam</title>
        <!-- Icons -->
        <link rel="shortcut icon" href="{{asset('favicon.png')}}">
<!--        <link rel="stylesheet" href="{{URL::asset("public/admin/fonts/ionicons/css/ionicons.min.css")}}">
        <link rel="stylesheet" href="{{URL::asset("public/admin/fonts/font-awesome/css/font-awesome.min.css")}}">
         Plugins 
        <link rel="stylesheet" href="{{URL::asset("public/admin/styles/plugins/c3.css")}}">
        <link rel="stylesheet" href="{{URL::asset("public/admin/styles/plugins/waves.css")}}">
        <link rel="stylesheet" href="{{URL::asset("public/admin/styles/plugins/perfect-scrollbar.css")}}">-->


        <!-- Css/Less Stylesheets -->
        <link rel="stylesheet" href="{{URL::asset("public/admin/styles/bootstrap.css")}}">
        <link rel="stylesheet" href="{{URL::asset("public/admin/styles/bootstrap.min.css")}}">
<!--        <link rel="stylesheet" href="{{URL::asset("public/admin/styles/bootstrap_multiselect.css")}}">
        <link rel="stylesheet" href="{{URL::asset("public/admin/styles/main.min.css")}}">
        <link rel="stylesheet" href="{{URL::asset("public/admin/styles/custome.css")}}">
        <link rel="stylesheet" href="{{URL::asset("public/admin/styles/jquery-ui-1.10.4.custom.min.css")}}">
        <link rel="stylesheet" href="{{URL::asset("public/admin/styles/plugins/chosen/chosen.min.css")}}">-->
        <!--<link href='http://fonts.googleapis.com/css?family=Roboto:400,500,700,300' rel='stylesheet' type='text/css'>-->

<!--INSIDE CSS START-->
<style type="text/css">
html,body{ width: 100%; height: 100%; margin: 0px; padding:0px;}
.login_body_bg{ float: left; position: relative; overflow-x: hidden; width: 100%; height: 100%; background: url(public/admin/images/login_bg.jpg) no-repeat center center scroll; -webkit-background-size: cover; -moz-background-size: cover; background-size: cover; -o-background-size: cover;}
/*****************************HEADER START*****************************/
.login_header{ float: left; width: 100%; /*background: #000; */margin: 0px; padding: 0px;}
.login_logo{ float: left; width: 100%; margin: 0px; padding: 0px;}
.login_logo img{ float: right; margin: 20px;}
/*****************************LOGIN COLUMN START*****************************/
.login_col{ float: left; position: absolute; width: 100%; top: 50%; left: 50%; transform: translate(-50%, -50%); /*background: #000;*/}
.big_user_col{ float: left; width: 100%; text-align: center; margin:20px 0;}
.wb-inv { text-align: center;}
.login_input{background: none; width: 100%!important; color: #fff!important; border: 3px solid #fff; font-size: 18px; height: 45px;}
.fp_txt{ color: #fff!important; font-weight: 100;}
.login_input::-webkit-input-placeholder { /* Chrome/Opera/Safari */color: #fff;}
.login_input::-moz-placeholder { /* Firefox 19+ */ color: #fff; }
.login_input:-ms-input-placeholder { /* IE 10+ */ color: #fff; }
.login_input:-moz-placeholder { /* Firefox 18- */ color: #fff;}
.login_input:focus {border:3px solid #fff;}
.login_btn_col{ float: left; width: 100%; text-align: center;}
.login_btn{ background: #ec1c23; padding: 10px 30px!important; color:#fff; font-weight: 100!important; font-size: 16px;}
.err_display{color:red;}
@media only screen and (max-width: 320px), only screen and (max-width: 768px) {
.login_logo{ float: left; width: 100%; margin: 0px; padding: 0px; text-align: center!important;}
.login_logo img{ float:none!important; margin: 20px;}
.mt_small_100{ margin: 100px 0 0 0!important;}
}
</style>
<!--INSIDE CSS START-->

</head>
<body>
    <div class="login_body_bg">
<!----------------------------HEADRE START---------------------------->
         <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <header class="login_header">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                       <div class="login_logo">
                           <img src="{{asset("public/admin/images/login_logo.png")}}">
                       </div>
                    </div>
                </header>
            </div>
         </div>
<!----------------------------HEADRE END---------------------------->

<!----------------------------LOGIN COLUMN START---------------------------->
<!--<div id="flash_error" class='err_display'></div>-->        
<div class="login_col">
          <div class="container">
            <div class="row">
                @if(Session::get('flash_error'))
                <div class="col-md-12 big_user_col hidden-xs">
                    <div class="alert alert-danger">{{ Session::get('flash_error') }}</div>
               </div>
                @endif
               <div class="col-md-12 big_user_col hidden-xs">
                  <img src="{{asset("public/admin/images/login_user_B_icon.png")}}" width="123" height="123">
               </div>
                
                <div class="clearfix"></div>
                <div class="col-md-12 mt_small_100">
                <form class="form-inline" role="form" method="POST" action="{{URL::route('userLogin')}}">
                    <div class="form-group col-md-6 col-xs-12">
                        <label class="wb-inv col-md-2 col-xs-12"><img src="{{asset("public/admin/images/login_user_S_icon.png")}}"> </label>
                        <span class=" col-md-10">
                         <input type="email" class="form-control login_input" name="email" placeholder="Username" />
                        @if ($errors->has('email')) <p class="help-block err_display">{{ $errors->first('email') }}</p> @endif
                        </span>
                    </div>
                    <div class="form-group col-md-6 col-xs-12">
                        <label class="wb-inv col-md-2 col-xs-12" ><img src="{{asset("public/admin/images/login_lock_icon.png")}}"> </label>
                        <span class=" col-md-10">
                            <input type="password" class="form-control login_input" placeholder="Password" name="password" id="password"/>
                             @if ($errors->has('password')) <p class="help-block err_display">{{ $errors->first('password')}}</p> @endif
                        </span>
                        <label class="pull-right"><a href="{{URL::route('forgotPassword')}}" class="fp_txt">Forgot your Password ?</a> </label>
                    </div>
                    <div class="form-group col-md-12 col-xs-12 login_btn_col">
                        <button type="submit" class="btn login_btn">Login &raquo;</button>
                    </div>
                </form>
                </div>
                </div>
            </div>
        </div>
<!----------------------------LOGIN COLUMN END---------------------------->

    </div>
</body>
</html>