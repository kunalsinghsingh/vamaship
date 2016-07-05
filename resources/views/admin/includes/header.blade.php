

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
            
           
            <li><a href="{{URL::route('logout')}}"><span class="ion ion-power">&nbsp;&nbsp;</span>Logout</a></li>
        </ul>
    </li>
</ul>

