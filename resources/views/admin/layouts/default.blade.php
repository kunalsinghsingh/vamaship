<!DOCTYPE html>
<html>
    <head>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <meta name="description" content="">
        <meta name="keywords" content="">
        <meta name="author" content="{{session('userPhoneName')}}">
        <!-- <base href="/"> -->
        <title>Vamaship</title>
      
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
        <link rel="stylesheet" href="{{URL::asset("public/admin/styles/daterangepicker.css")}}">
        <link rel="stylesheet" href="{{URL::asset("public/admin/styles/jquery.datetimepicker.css")}}">
        <link rel="stylesheet" href="{{URL::asset("public/admin/styles/bootstrap-multiselect.css")}}">
        <link rel="stylesheet" href="{{URL::asset("public/admin/styles/material.min.css")}}">
        <link rel="stylesheet" href="{{URL::asset("public/admin/styles/jquery-ui-1.10.4.custom.min.css")}}">
        <link rel="stylesheet" href="{{URL::asset("public/admin/styles/plugins/chosen/chosen.min.css")}}">
        <link href='http://fonts.googleapis.com/css?family=Roboto:400,500,700,300' rel='stylesheet' type='text/css'>
        <script type="text/javascript">
            var SITE_URL = '<?php echo URL::to('/') ?>/';
        </script>
        <!-- Match Media polyfill for IE9 -->
        <!--[if IE 9]> <script src="scripts/ie/matchMedia.js"></script>  <![endif]-->
    </head>
    <body id="app" class="app off-canvas">
        <!-- header -->
        <header class="site-head fixedHeader" id="site-head">
            @include('admin.includes.header')
        </header>
        <!-- #end header -->

        <!-- main-container -->

        <div class="main-container clearfix nav-horizontal">
            <!-- main-navigation -->
            <aside class="nav-wrap ps-container" id="site-nav" data-perfect-scrollbar>
                @include('admin.includes.navigation')
            </aside>
            <div class="content-container" id="content">
                <!--dashboard page--> 
                <div class="page page-dashboard">

                    <div class="page-wrap" >
                        @yield('dashboard_content') 

                    </div>  
                    <!--#end page-wrap--> 
                </div>
                <!--#end dashboard page--> 
            </div>
            <!-- #end main-navigation -->

            <!-- content-here -->

            @yield('content') 
        </div> <!-- #end main-container -->

        <!-- theme settings -->
        <div class="site-settings clearfix hidden-xs">
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
                    <!-- <ul class="themes list-unstyled" id="themeColor">
                            <li data-theme="theme-zero" class="active"></li>
                            <li data-theme="theme-one"></li>
                            <li data-theme="theme-two"></li>
                            <li data-theme="theme-three"></li>
                            <li data-theme="theme-four"></li>
                            <li data-theme="theme-five"></li>
                            <li data-theme="theme-six"></li>
                            <li data-theme="theme-seven"></li>
                    </ul>-->
                </div>
            </div>
        </div>
        <!-- #end theme settings -->





        @include('admin.includes.footer')


        @yield('myscript') 

    </body>
</html>