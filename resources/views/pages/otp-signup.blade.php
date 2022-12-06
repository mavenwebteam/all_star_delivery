@extends('layouts.master')
@section('title')OTP Confirmation | SAG RTA @stop
@section('description')OTP Confirmation | SAG RTA @stop
@section('keywords')OTP Confirmation | SAG RTA @stop
@section('content')   
 <script src = "{{ URL::asset('resources/assets/js/angular.min.js') }}"></script>   
   <script>
  var app = angular.module('app', []);
	  
app.controller('homeCtrl', function($scope) {
});
      </script>
      <section class="signup-head-sec">
   <div class="container"><h2>OTP Confirmation Form</h2></div>
  </section>
       <section class="signup-page-sec">
    <div class="container" ng-app="app" ng-controller="homeCtrl">
    <div class="row">
    <div class="col-md-8 col-md-offset-2">
       <div class="signup-box">
       <h2>OTP Confirmation</h2>
         <div class="row">
        <div class="col-md-6 col-md-offset-3">
        @if(Session::has('msg')) {!! session('msg') !!} @endif
               {!! Form::open(array('url' => 'confirm-signuppost', 'method' => 'post','name'=>'signupForm','novalidate' => 'novalidate')) !!}
               <div class="form-group">
                <label class="col-md-12 control-label">OTP <span>*</span></label>
                <div class="col-md-8">
                 {!! Form::text('otp', null, ['class' => 'form-control','placeholder' => 'OTP(6 Digit)','ng-model'=>'otp','ng-pattern' => '/^[0-9]{6}$/','required'=>'required']) !!}
        @if ($errors->has('otp')) <p class="alert alert-danger">{{ $errors->first('otp') }}</p> @endif
               <span ng-show="signupForm.otp.$error.required && signupForm.otp.$dirty">OTP is required</span>
   <span ng-show="!signupForm.otp.$error.required && signupForm.otp.$error.pattern && signupForm.otp.$dirty">Invalid OTP.</span>
                </div>
                 </div>
              <div class="form-group"> <div class="col-md-12">
                 {!! Form::submit('Confirm',['class' => 'btn btn-login','ng-disabled' => 'signupForm.$invalid']) !!}
                 </div></div>
               </div>
                
                {!! Form::close() !!}
                </div> 
       </div>
       </div>
       </div>
       </div>
       </section>
    
@stop