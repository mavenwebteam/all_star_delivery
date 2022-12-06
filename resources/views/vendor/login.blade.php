<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>All Star Delivery - Vendor Login</title>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
 <link rel="shortcut icon" type="image/png" href="{{asset('/media/bringoo_favicon.ico') }}"/>
<link href="{{asset('admin_assets/css/style.css') }}" type="text/css" rel="stylesheet" />
<link href="{{asset('admin_assets/css/bootstrap.min.css') }}" type="text/css" rel="stylesheet" />


<link rel="shortcut icon" type="image/png" href="{{asset('assets/images/favicon.ico') }}"/>
<script type="text/javascript" src="{{ URL::asset('assets/js/jquery.min.js') }}"></script>

  <script>
  
         //var mainApp = angular.module("mainApp", []);
          //mainApp.controller('loginController', function($scope) {
          //});
		 
      </script>
<style>
.contsct-form input#remember {
    width: 19px;
}
.contsct-form label {
    width: 100%;
    display: inline-flex;
    padding-left: 17px;
}
</style>
</head>
<body class="login-page">
  <div class="container">
    <div class="row">
      <div class="col-sm-8 col-md-6 col-sm-offset-2  col-md-offset-3">
        <h1 style="text-align:center;">All Star Delivery</h1>
        <!-- <div class="logo"><a href="{{ URL::to('/admin') }}"><img style="width:350px" src="{{asset('/media/imars-logo.png') }}" /></a></div> -->
      </div> 
    </div>
  </div>
<section>
  <div class="container">
    <div class="row">
      <div class="col-sm-8 col-md-6 col-sm-offset-2  col-md-offset-3" ng-app="mainApp" ng-controller="loginController">
     
        @if(Session::has('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        
       @if(Session::has('msg')) {!! session('msg') !!} @endif
      
        <div class="contsct-form">
       
        {!! Form::open(array('url' => 'vendor/loginpost', 'method' => 'post','name'=>'loginForm','novalidate' => 'novalidate')) !!}
          
			<span class="login_field">
		  <img  src="{{asset('/admin_assets/images/user-fild.png') }}" />
			<input type="email" value='{{isset( $_COOKIE["email"]) ?  $_COOKIE["email"] : ''}}'  name="email" class="user-fild" id="email" placeholder="Email or Mobile"/>
            @if ($errors->has('email')) <p class=" alert-danger">{{ $errors->first('email') }}</p> @endif
			</span>
           <!--{!! Form::password('password' ,['class' => 'password-fild','id' => 'password','placeholder'=>'Password','ng-model'=>'password','ng-minlength'=>'6','ng-maxlength'=>'16','required'=>'required']) !!}-->
		   <input type="hidden" name="device_token" id="device_token"/>
		   <div style="display:none;" id="msg"></div>
           <span class="login_field">
		   <img  src="{{asset('/admin_assets/images/password-fild.png') }}" />
           <input type="password" value='{{isset( $_COOKIE["password"]) ?  $_COOKIE["password"] : ''}}' name="password" class="password-fild" id="password"  placeholder="Password"/>
                   
           @if ($errors->has('password')) <p class=" alert-danger">{{ $errors->first('password') }}</p> @endif
		    </span>
           {!! Form::submit('login',['class' => 'login','id' => 'login-id']) !!}
           <div class="form-group">
            <label for="remember"><input type="checkbox" name="remember" id="remember"  {{isset($_COOKIE['email']) ? 'checked' : '' }} >Remember Me</label>          
        </div>          {!! Form::close() !!}
          
          <p class="forgot_pass"><a href="{{ URL::to('/vendor/forgot-password') }}">Forgot Password ?</a></p>
          <p class="forgot_pass">Not have a  account <a href="{{ URL::to('/vendor/signup') }}">Create Now</a></p>
          
        </div>
       
      </div>
    </div>
  </div>
  {{Session::forget('msg')}}
</section>
</body>
</html>

	



