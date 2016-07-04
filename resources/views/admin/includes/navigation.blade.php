<div class="nav-head">
    <!-- site logo -->
    
</div>

<!-- Site nav (vertical) -->
<?php
//date_default_timezone_set('asia/calcutta');
if (Session::get('userPic') != '') {
    $user_pic = URL::asset('uploads/user_pic/' . Session::get('userPic'));
} else {
    $user_pic = URL::asset('public/admin/images/user_icon.jpg');
}
?>
<nav class="site-nav clearfix" role="navigation">
    <div class="profile clearfix mb15">
        <img src="{{$user_pic}}" alt="admin">
        <div class="group">
            <h5 class="name">Hey! {{Session::get('userName')}}</h5>
        </div>
    </div>

    <!-- navigation -->
    <ul class="list-unstyled clearfix nav-list mb15">
        <li>
            <a href="{{URL::route('user')}}">
                <i class="ion ion-person-stalker"></i>
                <span class="text">Manage User Profile</span>
            </a>
        </li>
        
        <li>
            <a href="{{URL::route('booking')}}">
                <i class="ion ion-speakerphone"></i>
                <span class="text">My Inquiries</span>
            </a>
        </li>
       
        

    </ul> <!-- #end navigation -->
</nav>

<!-- nav-foot -->
<footer class="nav-foot">
    <p>2015 &copy; <span>MATERIA</span></p>
</footer>
