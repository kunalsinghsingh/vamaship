<html>
    <head>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <meta name="description" content="Materia - Admin Template">
        <meta name="keywords" content="materia, webapp, admin, dashboard, template, ui">
        <meta name="author" content="solutionportal">
        <!-- <base href="/"> -->
        <title> Datamatics</title>
        <!-- Icons -->
        <link rel="shortcut icon" href="{{asset('favicon.ico')}}">
        <link rel="stylesheet" href="{{URL::asset("public/admin/fonts/ionicons/css/ionicons.min.css")}}">
        <link rel="stylesheet" href="{{URL::asset("public/admin/fonts/font-awesome/css/font-awesome.min.css")}}">
        <!-- Plugins -->
        <link rel="stylesheet" href="{{URL::asset("public/admin/styles/plugins/c3.css")}}">
        <link rel="stylesheet" href="{{URL::asset("public/admin/styles/plugins/waves.css")}}">
        <link rel="stylesheet" href="{{URL::asset("public/admin/styles/plugins/perfect-scrollbar.css")}}">


        <!-- Css/Less Stylesheets -->
        <link rel="stylesheet" href="{{URL::asset("public/admin/styles/bootstrap.css")}}">
        <link rel="stylesheet" href="{{URL::asset("public/admin/styles/bootstrap.min.css")}}">
        <link rel="stylesheet" href="{{URL::asset("public/admin/styles/bootstrap_multiselect.css")}}">
        <link rel="stylesheet" href="{{URL::asset("public/admin/styles/main.min.css")}}">
        <link rel="stylesheet" href="{{URL::asset("public/admin/styles/custome.css")}}">
        <link rel="stylesheet" href="{{URL::asset("public/admin/styles/jquery-ui-1.10.4.custom.min.css")}}">
        <link rel="stylesheet" href="{{URL::asset("public/admin/styles/plugins/chosen/chosen.min.css")}}">
        <link href='http://fonts.googleapis.com/css?family=Roboto:400,500,700,300' rel='stylesheet' type='text/css'>
        <script src="{{URL::asset("public/admin/scripts/jquery.min.js")}}"></script>
        <script src="{{URL::asset("public/admin/scripts/jquery-ui-1.10.3.min.js")}}"></script>
        <script src="{{URL::asset("public/admin/scripts/bootstrap.min.js")}}"></script>


    </head>
    <body  id="app" class="app off-canvas body-full bgimage">
        <div class="main-container clearfix">

            <!-- content-here -->
            <div class="content-container" id="content">
                <div class="page page-auth">
                    <div class="auth-container">

                        <div class="form-head mb20">
                            <h1 class="site-logo h2 mb5 mt5 text-center text-uppercase text-bold mb20" style="margin-right: 28px;"><a href="{{URL::to('/')}}"><img src="{{asset("public/admin/images/logo-dark.png")}}" /></a></h1>
                            <h5 class="text-normal h5 text-center">Reset Your Password</h5>
                        </div>
                        <div id="flash_error" class='err_display'>{{ Session::get('flash_error') }}</div>
                        <div class="form-container">
                            <form class="form-horizontal" method="POST" action="{{URL::route('changePass')}}">
                                {!! csrf_field() !!}
                                <div class="md-input-container md-float-label">

                                    <input type="password"  class="md-input" name="password" id="password">
                                    <label>New Password</label>
                                    @if ($errors->has('password')) <p class="help-block err_display">{{ $errors->first('password') }}</p> @endif
                                </div>

                                <div class="md-input-container md-float-label">
                                    <input value="{{$code}}" name="code" type="hidden">
                                    <input type="password"  class="md-input" name="retype_password" id="retype_password">
                                    <label>Retype Password</label>
                                    @if ($errors->has('retype_password')) <p class="help-block err_display">{{ $errors->first('retype_password')}}</p> @endif
                                </div>

                                <div class="btn-group btn-group-justified mb15">
                                    <div class="btn-group">
                                        <button type="submit" class="btn btn-info">Reset Password</button>
                                    </div>
                                </div> 
                                <!--							<div class="clearfix text-center small">
                                                                                                <p>Don't have an account? <a href="signup.html">Create Now</a></p>
                                                                                        </div>-->
                                @if(Session::has('userGroup'))
                                <input type="hidden" id="voiceAgent" value="{{Session::get('userGroup')}}">
                                @endif
                            </form>
                        </div>

                    </div> <!-- #end signin-container -->
                </div>



            </div> 
            <!-- #end content-container -->

        </div>
        
    </body>
    
</html>