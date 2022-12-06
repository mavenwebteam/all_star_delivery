@extends('layouts.master')
@section('title')Thanks For Contact Us | SAG RTA @stop
@section('description')Thanks For Contact Us @stop
@section('keywords')Thanks For Contact Us @stop
@section('content')

  <section class="signup-head-sec">
	  <div class="container"><h2>Forgot Password</h2></div>
  </section>
       <section class="login-page-sec">
    <div class="container" ng-app="app" ng-controller="signCtrl">
    <div class="row">
    <div class="col-md-8 col-md-offset-2">
       <div class="login-box">
        <h2>Forgot Password</h2>
       @if(Session::has('msg')) {!! session('msg') !!} @endif
                 {!! Form::open(array('url' => 'forgot-password-post-test', 'method' => 'post','name'=>'signinForm','novalidate' => 'novalidate')) !!}
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
                 <div class="col-md-12">
                  {!! Form::submit('Send',['class' => 'btn btn-login', 'ng-disabled' => 'signinForm.$invalid']) !!}
                  <a href="{{URL::to('/signin')}}" class="btn btn-register" id="forgot_password1" >Login</a>
                  
                 </div>
                   
                 </div>
                  {!! Form::close() !!}  
        
</div>
</div>
</div>
</div>
</section>    
@stop

      

