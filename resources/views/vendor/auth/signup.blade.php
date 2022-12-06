<!doctype html>
<html>
   <head>
      <meta charset="utf-8">
      <title>ALL STAR DELIVERY - Vendor Signup </title>
      <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
      <link rel="shortcut icon" type="image/png" href="{{asset('/media/bringoo_favicon.ico') }}"/>
      <link href="{{asset('admin_assets/css/style.css') }}" type="text/css" rel="stylesheet" />
      <link href="{{asset('admin_assets/css/bootstrap.min.css') }}" type="text/css" rel="stylesheet" />
      <link rel="shortcut icon" type="image/png" href="{{asset('assets/images/favicon.ico') }}"/>
      <script src="{{asset('assets/js/jquery-latest.min.js') }}" type="text/javascript"></script>
      <script src = "{{asset('assets/js/angular.min.js') }}"></script>
      <script>
         var mainApp = angular.module("mainApp", []);
          mainApp.controller('loginController', function($scope) {
          });
         
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
         <div class="logo">
            <a href=""><img src="{{asset('/media/logo.png') }}" height="200" width="200"/></a>
         </div>

               <h1 style="text-align:center;">Vendor Registration</h1>
            </div>
         </div>
      </div>
      <section>
         <div class="container">
            <div class="row">
               <div class="col-sm-8 col-md-6 col-sm-offset-2  col-md-offset-3" ng-app="mainApp" ng-controller="loginController">
                  @if(Session::has('msg')) {!! session('msg') !!} @endif
                  <div class="contsct-form">
                     {!! Form::open(array('url' => 'vendor/signup', 'method' => 'post','name'=>'loginForm','novalidate' => 'novalidate')) !!}   
                     <span class="login_field">
                        <input type="text" value='{{ old('first_name') }}'  name="first_name" class="user-fild" id="first_name" placeholder="First name"/>
                        @if ($errors->has('first_name')) 
                          <p class=" alert-danger">{{ $errors->first('first_name') }}</p>
                        @endif
                     </span>
                     <span class="login_field">
                        <input type="text" value='{{ old('last_name') }}'  name="last_name" class="user-fild" id="last_name" placeholder="Last name"/>
                        @if ($errors->has('last_name')) 
                          <p class=" alert-danger">{{ $errors->first('last_name') }}</p>
                        @endif
                     </span>
                     <span class="login_field">
                        <select name="country_code">
                           <option value="">-country code-</option>
                           @foreach ($countries as $country)
                              <option value="{{ $country->phonecode }}"  {{(old('country_code')==$country->phonecode)? 'selected':''}}>{{ $country->phonecode.' '.$country->name }}</option> 
                           @endforeach
                        </select>
                        @if ($errors->has('country_code')) 
                          <p class=" alert-danger">{{ $errors->first('country_code') }}</p>
                        @endif
                        <input type="text" value='{{ old('mobile') }}'  name="mobile" class="user-fild" id="mobile" placeholder="Mobile"/>
                        @if ($errors->has('mobile')) 
                          <p class=" alert-danger">{{ $errors->first('mobile') }}</p>
                        @endif
                     </span>
                     <span class="login_field">
                        <img  src="{{asset('/admin_assets/images/user-fild.png') }}" />
                        <input type="email" value='{{ old('email') }}'  name="email" class="user-fild" id="email" placeholder="Email"/>
                        @if ($errors->has('email')) 
                          <p class=" alert-danger">{{ $errors->first('email') }}</p>
                        @endif
                     </span>
                     <span class="login_field">
                        <img  src="{{asset('/admin_assets/images/password-fild.png') }}" />
                        <input type="password" value='{{ old('password') }}' name="password" class="password-fild" id="password"  placeholder="Password"/>
                        @if ($errors->has('password')) 
                          <p class=" alert-danger">{{ $errors->first('password') }}</p>
                        @endif
                     </span>
                     <span class="login_field">
                        <img  src="{{asset('/admin_assets/images/password-fild.png') }}" />
                        <input type="password" value='{{ old('confirm_password') }}' name="confirm_password" class="password-fild" id="confirm_password"  placeholder="Confirm password"/>
                        @if ($errors->has('confirm_password')) 
                          <p class=" alert-danger">{{ $errors->first('confirm_password') }}</p>
                        @endif
                     </span>
                     {!! Form::submit('Signup',['class' => 'login','id' => 'login-id']) !!}
                     <div class="form-group">
                        {{-- <label for="remember"><input type="checkbox" name="remember" id="remember"  {{isset($_COOKIE['email']) ? 'checked' : '' }} >Remember Me</label>           --}}
                     </div>
                     {!! Form::close() !!}
                     <p class="forgot_pass">Already have a acoount <a href="{{ URL::to('/vendor/login') }}">Login Now</a></p>
                  </div>
               </div>
            </div>
         </div>
         {{Session::forget('msg')}}
      </section>
   </body>
</html>