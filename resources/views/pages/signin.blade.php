@extends('layouts.master')
@section('title')Signin | SAG RTA @stop
@section('description')Signin | SAG RTA @stop
@section('keywords')Signin | SAG RTA @stop
@section('content')   
 <script type="text/javascript">
	 var appmd = angular.module('app', []);
    appmd.controller('signCtrl', function ($scope) {  
$scope.email = "{{Request::old('email')}}";
}); 
  </script>  
  <section class="signup-head-sec">
	  <div class="container"><h2>Sign In Form</h2></div>
  </section>
     <section class="login-page-sec">
    <div class="container" ng-app="app" ng-controller="signCtrl">
    <div class="row">
    <div class="col-md-8 col-md-offset-2">
       <div class="login-box">
        <h2>Login</h2>
         @if(Session::has('msg')) {!! session('msg') !!} @endif
                 {!! Form::open(array('url' => 'signinpost', 'method' => 'post','name'=>'signinForm','novalidate' => 'novalidate')) !!}
                 <div class="form-group">
                <label class="col-md-12 control-label">Email</label>
                <div class="col-md-12">
                 
                
                 
                  {!! Form::text('email', null, ['class' => 'form-control','placeholder' => 'Email','ng-model'=>'email','ng-pattern' => '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-z]{2,}$/','required'=>'required']) !!}
        @if ($errors->has('email')) <p class="alert alert-danger">{{ $errors->first('email') }}</p> @endif
               <span ng-show="signinForm.email.$error.required && signinForm.email.$dirty">Email is required</span>
   <span ng-show="!signinForm.email.$error.required && signinForm.email.$error.pattern && signinForm.email.$dirty">Invalid email.</span>
                </div>
                </div>
                 <div class="form-group">
                <label class="col-md-12 control-label">Password</label>
                <div class="col-md-12">
                 {!! Form::password('password', ['class' => 'form-control','ng-model'=>'password','required'=>'required']) !!}
        @if ($errors->has('password')) <p class="alert alert-danger">{{ $errors->first('password') }}</p> @endif
               <span ng-show = "signinForm.password.$dirty && signinForm.password.$invalid">
                        <span ng-show = "signinForm.password.$error.required">Password is required.</span>
                     </span>
                </div>
                </div>
                 <div class="form-group">
                 <div class="col-md-12">
                  {!! Form::submit('Login',['class' => 'btn btn-login', 'ng-disabled' => 'signinForm.$invalid']) !!}
                   <a href="{{URL::to('/forgot-password')}}" class="btn btn-register"   id="forgot_password1" >Forgot Password</a>
                  
                 </div>
                   
                 </div>
                  {!! Form::close() !!}
</div>
</div>
</div>
</div>
</section>
@stop