 <!-- Logo -->
 <style>
 .navbar-custom-menu>.navbar-nav>li img {
    width: 35px;
    height: 35px;
    position: absolute;
    right: 100px;
    border-radius: 50%;
    top: 8px;
}</style>
 <a href="{{ URL::to('/admin') }}" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>Brin</b>goo</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>Outlet</b>Bringoo</span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- Messages: style can be found in dropdown.less-->
         
          <!-- Notifications: style can be found in dropdown.less -->
          
          <!-- Tasks: style can be found in dropdown.less -->
          
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
		   @if(Auth::user()->profile_pic!="")
				 <img src="{{asset('/media/users').'/'.Auth::user()->profile_pic}}" width="70px" height="70px">
			@else
				   <img src="{{asset('/media/no-image.png')}}" width="70px" height="70px">
			@endif	 
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
               <span class="hidden-xs">{{Auth::user()->first_name}}</span>
            </a>
            <ul class="dropdown-menu">
             
                 <li> <a href="{{ URL::to('/outlet/profile') }}" class="dropdown-item">Profile</a></li>
				 <li> <a href="{{ URL::to('/outlet/change-password') }}" class="dropdown-item">Change password</a></li>
               
                 <li><a href="{{ URL::to('/outlet/logout') }}" class="dropdown-item">Sign out</a></li>
               
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button -->
          <!-- <li>
            <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
          </li> -->
        </ul>
      </div>
    </nav>