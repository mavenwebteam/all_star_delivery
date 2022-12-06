<!doctype html>
<html>
   <head>
      <meta charset="utf-8">
      <title>ALL STAR DELIVERY - Verify Otp </title>
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

               <h1 style="text-align:center;">Vendor Veriyf Otp</h1>
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
                  @if(Session::has('fail'))
                     <div class="alert alert-danger">
                        {{ session('fail') }}
                     </div>
                  @endif
                  <div class="contsct-form">
                     {!! Form::open(array('url' => 'vendor/verify-otp', 'method' => 'post','name'=>'loginForm','novalidate' => 'novalidate')) !!}   
                     <span class="login_field">
                        <input type="hidden" value='{{ $email }}'  name="email" class="user-fild" />
                        <input type="text" value='{{ $otp }}'  name="otp" class="user-fild" id="otp" placeholder="Enter Otp"/>
                        @if ($errors->has('otp')) 
                          <p class=" alert-danger">{{ $errors->first('otp') }}</p>
                        @endif
                     </span>
                     
                     {!! Form::submit('Submit',['class' => 'login','id' => 'login-id']) !!}
                     <div class="form-group">
                        {{-- <label for="remember"><input type="checkbox" name="remember" id="remember"  {{isset($_COOKIE['email']) ? 'checked' : '' }} >Remember Me</label>           --}}
                     </div>
                     {!! Form::close() !!}
                     <p class="forgot_pass">Already have a acoount <a href="{{ URL::to('/vendor/login') }}">Login Now</a></p>
                     <div id="timerDiv">Resend Otp <span id="timer"></span></div>
                     <div>
                     <form method="post" action="{{ route('vendor.otp.resend') }}">
                        @csrf
                        <input type="hidden" name="email" value="{{ $email }}">
                        <button type="submit" id="resebdOtp" style="text-align: center; display: inline;">Resend OTP</button>
                     </form>
                  </div>
                  </div>
               </div>
            </div>
         </div>
         {{Session::forget('msg')}}
      </section>
   
   </body>
   <script>
      document.onload = hideResendOtp();

      function hideResendOtp(){
         document.getElementById("resebdOtp").style.display = "none";
      }
      
      var timeLeft = 5;
      var elem = document.getElementById('timer');
      var timerId = setInterval(countdown, 1000);
    
    function countdown() {
      if (timeLeft == -1) {
        clearTimeout(timerId);
        document.getElementById("timerDiv").style.display = "none";
        document.getElementById("resebdOtp").style.display = "inline";
      } else {
        elem.innerHTML = timeLeft + ' seconds';
        timeLeft--;
      }
    }
    
   </script>
</html>