<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>{{Config::get("Site.title")}} - Admin Login</title>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
 <link rel="shortcut icon" type="image/png" href="{{asset('/media/bringoo_favicon.ico') }}"/>
<link href="{{asset('admin_assets/css/style.css') }}" type="text/css" rel="stylesheet" />
<link href="{{asset('admin_assets/css/bootstrap.min.css') }}" type="text/css" rel="stylesheet" />

<link rel="shortcut icon" type="image/png" href="{{asset('assets/images/favicon.ico') }}"/>
<script src="{{ URL::asset('assets/js/jquery.min.js') }}"></script>
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
        <div class="logo">
          <a href=""><img src="{{asset('/media/logo.png') }}" height="200" width="200"/></a>
       </div>
      </div> 
    </div>
  </div>
<section>
  <div class="container">
    <div class="row">
      <div class="col-sm-8 col-md-6 col-sm-offset-2  col-md-offset-3" ng-app="mainApp" ng-controller="loginController">
        @if(Session::has('msg')) {!! session('msg') !!} @endif
        <div class="contsct-form">
          {!! Form::open(array('url' => 'admin/loginpost', 'method' => 'post','name'=>'loginForm','novalidate' => 'novalidate')) !!}          
            <span class="login_field">
              <img  src="{{asset('/admin_assets/images/user-fild.png') }}" />
              <input type="email" value='{{isset( $_COOKIE["email"]) ?  $_COOKIE["email"] : ''}}'  name="email" class="user-fild" id="email" placeholder="Email Or Mobile"/>
              @if ($errors->has('email')) <p class=" alert-danger">{{ $errors->first('email') }}</p> @endif
            </span>
            <span class="login_field">
              <img  src="{{asset('/admin_assets/images/password-fild.png') }}" />
              <input type="password" value='{{isset( $_COOKIE["password"]) ?  $_COOKIE["password"] : ''}}' name="password" class="password-fild" id="password"  placeholder="Password"/>       
              @if ($errors->has('password')) <p class=" alert-danger">{{ $errors->first('password') }}</p> @endif
            </span>
            {!! Form::submit('login',['class' => 'login','id' => 'login-id']) !!}
            <div class="form-group">
              <label for="remember"><input type="checkbox" name="remember" id="remember"  {{isset($_COOKIE['email']) ? 'checked' : '' }} >Remember Me</label>          
            </div>
          {!! Form::close() !!}
          <p class="forgot_pass"><a href="{{ URL::to('/admin/forgot-password') }}">Forgot Password ?</a></p>  
        </div>
      </div>
    </div>
  </div>
  {{Session::forget('msg')}}
</section>
</body>
</html>

