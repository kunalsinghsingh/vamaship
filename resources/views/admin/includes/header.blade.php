

<ul class="list-unstyled right-elems">
    <!-- profile drop -->
	
	
    <li class="profile-drop hidden-xs dropdown">
        <span style='color:white'>Hey! {{Session::get('userName')}}</span>
        <?php 
        if(Session::get('userPic')!=''){
            $user_pic=URL::asset('uploads/user_pic/'.Session::get('userPic'));
        }else{
             $user_pic=URL::asset('public/admin/images/user_icon.jpg');
        }
        
        ?>
        <a href="javascript:;" data-toggle="dropdown">
            <img src="{{$user_pic}}" alt="admin-pic">
        </a>
        <ul class="dropdown-menu dropdown-menu-right">
            <li><a href="{{URL::route('profile')}}"><span class="ion ion-person">&nbsp;&nbsp;</span>Profile</a></li>
            <!--<li><a href="javascript:;"><span class="ion ion-settings">&nbsp;&nbsp;</span>Settings</a></li>-->
            <li class="divider"></li>
            <!--<li><a href="javascript:;"><span class="ion ion-lock-combination">&nbsp;&nbsp;</span>Lock Screen</a></li>-->
            <li><a href="{{URL::route('logout')}}"><span class="ion ion-power">&nbsp;&nbsp;</span>Logout</a></li>
        </ul>
    </li>
</ul>

