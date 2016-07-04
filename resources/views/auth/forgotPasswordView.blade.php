
<html>
    <head>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <meta name="description" content="Materia - Admin Template">
        <meta name="keywords" content="materia, webapp, admin, dashboard, template, ui">
        <meta name="author" content="solutionportal">
        <!-- <base href="/"> -->
        <title>vamaship</title>
        <!-- Icons -->
        <link rel="shortcut icon" href="{{asset('favicon.png')}}">
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


    </head>
    <body  id="app" class="app off-canvas body-full bgimage">
        <div class="main-container clearfix">

            <!-- content-here -->
            <div class="content-container" id="content">
                <div class="page page-auth">

                    <div class="auth-container">

                        <div class="form-head mb20">
                            <h4 >User Register Here</h4>
                        </div>
                        <div id="flash_error" class='err_display'>{{ Session::get('flash_error') }}</div>
                        <div class="form-container">
                            <form class="form-horizontal" action="{{URL::route('sendNewPassword')}}" method='post'>
                               <div class="md-input-container md-float-label">
                                    <input type="text" name='email' class="md-input">
                                    <label>Username</label>
                                    @if ($errors->has('name')) <p class="help-block err_display">{{ $errors->first('name') }}</p> @endif
                                </div>



                                <div class="md-input-container md-float-label">
                                    <input type="email" name='email' class="md-input">
                                    <label>Email Id</label>
                                    @if ($errors->has('email')) <p class="help-block err_display">{{ $errors->first('email') }}</p> @endif
                                </div>

                                <div class="md-input-container md-float-label">
                                    <input type="text" name='email' class="md-input">
                                    <label>Contact No.</label>
                                    @if ($errors->has('mobile')) <p class="help-block err_display">{{ $errors->first('mobile') }}</p> @endif
                                </div>


                                 <div class="md-input-container md-float-label">
                                    <input type="text" name='email' class="md-input">
                                    <label>Password.</label>
                                    @if ($errors->has('password')) <p class="help-block err_display">{{ $errors->first('password') }}</p> @endif
                                </div>


                                <button type="submit" class="btn btn-info btn-block text-uppercase btn-lg">Register here !</button>
                            </form>
                        </div>

                    </div> <!-- #end signin-container -->
                </div>



            </div> 
            <!-- #end content-container -->

        </div>
    </body>
</html>