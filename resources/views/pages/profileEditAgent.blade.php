@extends('layouts.master')
@section('title')Edit Profile | SAG RTA @stop
@section('description')Edit Profile | SAG RTA @stop
@section('keywords')Edit Profile | SAG RTA @stop
@section('content')   
 <script src = "{{ URL::asset('resources/assets/js/angular.min.js') }}"></script>   
   <script>
  var app = angular.module('app', []);
app.controller('homeCtrl', function($scope) {
	$scope.company_name = "{{$user->company_name}}";
	$scope.email = "{{$user->email}}";
	$scope.gst_no = "{{$user->gst_no}}";
	$scope.pan_no = "{{$user->pan_no}}";
	$scope.name = "{{$user->name}}";
	$scope.mobile = "{{$user->mobile}}";
});
      </script>
      <section class="signup-head-sec">
   <div class="container"><h2>Edit Profile</h2></div>
  </section>
    <section class="signup-page-sec">
    <div class="container" ng-app="app" ng-controller="homeCtrl">
    <div class="row">
    <div class="col-md-8 col-md-offset-2">
                @if(Session::has('msg')) {!! session('msg') !!} @endif
               {!! Form::open(array('url' => 'editagentprofilepost', 'method' => 'post','name'=>'signupForm','novalidate' => 'novalidate')) !!}
               <div class="signup-box">
              <h2>Edit Profile</h2>
                 <div class="form-group">
                <div class="row">
                <div class="col-md-6">
                <label class="col-md-12 control-label">Name<span>*</span></label>
                <div class="col-md-12">
                 {!! Form::text('name', $user->name, ['class' => 'form-control','placeholder' => 'Name','ng-model'=>'name','required'=>'required']) !!}
        @if ($errors->has('name')) <p class="alert alert-danger">{{ $errors->first('name') }}</p> @endif
               <span ng-show="signupForm.name.$error.required && signupForm.name.$dirty"> Name is required</span>
                </div>
                </div>
                 <div class="col-md-6">
                <label class="col-md-12 control-label">Email<span>*</span></label>
                <div class="col-md-12">
                {!! Form::text('email', $user->email, ['class' => 'form-control','placeholder' => 'Email','ng-model'=>'email','ng-pattern' => '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-z]{2,}$/','required'=>'required']) !!}
        @if ($errors->has('email')) <p class="alert alert-danger">{{ $errors->first('email') }}</p> @endif
               <span ng-show="signupForm.email.$error.required && signupForm.email.$dirty">Email is required</span>
   <span ng-show="!signupForm.email.$error.required && signupForm.email.$error.pattern && signupForm.email.$dirty">Invalid email.</span>
                </div>
                </div>
                </div>
                </div>
                <div class="form-group">
                <div class="row">
                <div class="col-md-6">
                <label class="col-md-12 control-label">Mobile<span>*</span></label>
                <div class="col-md-12">
                 {!! Form::text('mobile', $user->mobile, ['class' => 'form-control','placeholder' => '9999999999','ng-model'=>'mobile','ng-pattern' => '/^[0-9]{8,10}$/','required'=>'required']) !!}
                 <p class="not">Note:- OTP Will Be Sent on This Mobile No.</p>
        @if ($errors->has('mobile')) <p class="alert alert-danger">{{ $errors->first('mobile') }}</p> @endif
               <span ng-show="signupForm.mobile.$error.required && signupForm.mobile.$dirty"> Mobile is required</span>
   <span ng-show="!signupForm.mobile.$error.required && signupForm.mobile.$error.pattern && signupForm.mobile.$dirty">Invalid mobile number.</span>
                </div>
                </div>
                <div class="col-md-6">
                <label class="col-md-12 control-label">Company Name</label>
                <div class="col-md-12">
                 {!! Form::text('company_name', $user->company_name, ['class' => 'form-control','placeholder' => 'Company Name','ng-model'=>'company_name']) !!}
        @if ($errors->has('company_name')) <p class="alert alert-danger">{{ $errors->first('company_name') }}</p> @endif

                </div>
                </div>
                </div>
                </div>

                <div class="form-group">
                <div class="row">
                <div class="col-md-6">
                <label class="col-md-12 control-label">GST No<span>*</span></label>
                <div class="col-md-12">
                 {!! Form::text('gst_no', $user->gst_no, ['class' => 'form-control uppercase','placeholder' => 'GST No','ng-model'=>'gst_no','ng-pattern' => '/^([0]{1}[1-9]{1}|[1-2]{1}[0-9]{1}|[3]{1}[0-7]{1})([a-zA-Z]{5}[0-9]{4}[a-zA-Z]{1}[1-9a-zA-Z]{1}[zZ]{1}[0-9a-zA-Z]{1})+$/','required'=>'required']) !!}
        @if ($errors->has('gst_no')) <p class="alert alert-danger">{{ $errors->first('gst_no') }}</p> @endif
               <span ng-show="signupForm.gst_no.$error.required && signupForm.gst_no.$dirty">GST number is required</span>
   <span ng-show="!signupForm.gst_no.$error.required && signupForm.gst_no.$error.pattern && signupForm.gst_no.$dirty">Invalid GST number.</span>
                </div>
                </div>

                 <div class="col-md-6">
                <label class="col-md-12 control-label">PAN No<span>*</span></label>
                <div class="col-md-12">
                 {!! Form::text('pan_no', $user->pan_no, ['class' => 'form-control  uppercase','placeholder' => 'PAN No','ng-model'=>'pan_no','ng-pattern' => '/^([a-zA-Z]){5}([0-9]){4}([a-zA-Z]){1}?$/','required'=>'required']) !!}
                @if ($errors->has('pan_no')) <p class="alert alert-danger">{{ $errors->first('pan_no') }}</p> @endif
               <span ng-show="signupForm.pan_no.$error.required && signupForm.pan_no.$dirty">PAN number is required</span>
   <span ng-show="!signupForm.pan_no.$error.required && signupForm.pan_no.$error.pattern && signupForm.pan_no.$dirty">Invalid PAN number.</span>
                </div>
                </div>
                </div>
                </div>
                  <div class="form-group">
                 <div class="col-md-4">
                 {!! Form::submit('Update',['class' => 'btn btn-signup','ng-disabled' => 'signupForm.$invalid']) !!}
                <a href="{{URL::to('/myaccount')}}" class="btn btn-register" id="forgot_password1">Cancel</a>
                 </div>
                </div>
                   </div>
                {!! Form::close() !!}
                </div>
            </div>
        </div>
</section>

@stop