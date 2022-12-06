@extends('layouts.master')
@section('title')Sign Up | AllStar @stop
@section('description')Sign Up | AllStar @stop
@section('keywords')Sign Up | AllStar @stop
@section('content')   
 <script src = "{{ URL::asset('resources/assets/js/angular.min.js') }}"></script>   
   <script>
  var app = angular.module('app', []);
	   app.directive('validPasswordC', function() {
  return {
    require: 'ngModel',
    scope: {

      reference: '=validPasswordC'

    },
    link: function(scope, elm, attrs, ctrl) {
      ctrl.$parsers.unshift(function(viewValue, $scope) {

        var noMatch = viewValue != scope.reference
        ctrl.$setValidity('noMatch', !noMatch);
        return (noMatch)?noMatch:!noMatch;
      });

      scope.$watch("reference", function(value) {;
        ctrl.$setValidity('noMatch', value === ctrl.$viewValue);

      });
    }
  }
});
app.controller('homeCtrl', function($scope) {
	$scope.cin = "{{Request::old('cin')}}";
	$scope.company_name = "{{Request::old('company_name')}}";
	$scope.listed = "{{Request::old('listed')}}";
	$scope.type_of_securities = "{{Request::old('type_of_securities')}}";
	$scope.date_of_incorporation = "{{Request::old('date_of_incorporation')}}";
	$scope.contact_name = "{{Request::old('contact_name')}}";
	$scope.registered_address = "{{Request::old('registered_address')}}";
	$scope.email = "{{Request::old('company_name')}}";
	$scope.mobile_number = "{{Request::old('mobile_number')}}";
});
      </script>
      <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script>
  $( function() {
    $( "#date_of_incorporation" ).datepicker({
	  dateFormat: 'yy-mm-dd',
	  yearRange: '1950:2019',	
      changeMonth: true,
      changeYear: true,
	  maxDate: new Date(),
    });
  } );
  </script>
      <section class="signup-head-sec">
   <div class="container"><h2>Sign Up Form</h2></div>
  </section>
    <section class="signup-page-sec">
    <div class="container" ng-app="app" ng-controller="homeCtrl">
    <div class="row">
    <div class="col-md-8 col-sm-offset-2">
         @if(Session::has('msg')) {!! session('msg') !!} @endif
               {!! Form::open(array('url' => 'signuppost', 'method' => 'post','name'=>'signupForm','novalidate' => 'novalidate')) !!}
               <div class="signup-box">
                <div class="form-group">
                <div class="row">
                <div class="col-md-6">
                <label class="col-md-12 control-label">Corporate Identification Number (CIN)</label>
                <div class="col-md-12">
                 {!! Form::text('cin', null, ['class' => 'form-control','placeholder' => 'Corporate Identification Number (CIN)','ng-model'=>'cin','required'=>'required']) !!}
        @if ($errors->has('cin')) <p class="alert alert-danger">{{ $errors->first('cin') }}</p> @endif
                <span style = "color:red" ng-show = "signupForm.cin.$dirty && signupForm.cin.$invalid">
                        <span ng-show = "signupForm.cin.$error.required">CIN is required.</span>
                     </span>
                </div>
                </div>
                <div class="col-md-6">
                <label class="col-md-12 control-label">Company Name</label>
                <div class="col-md-12">
                 {!! Form::text('company_name', null, ['class' => 'form-control','placeholder' => 'Company Name','ng-model'=>'company_name','required'=>'required']) !!}
        @if ($errors->has('company_name')) <p class="alert alert-danger">{{ $errors->first('company_name') }}</p> @endif
                <span style = "color:red" ng-show = "signupForm.company_name.$dirty && signupForm.company_name.$invalid">
                        <span ng-show = "signupForm.company_name.$error.required">Company Name is required.</span>
                     </span>
                </div>
                </div>
                </div>
                </div>
                
                <div class="form-group">
                <div class="row">
                <div class="col-md-6">
                <label class="col-md-12 control-label">Select Listed or not</label>
                <div class="col-md-12">
                 {!! Form::select('listed', [""=>"Select Listed or not","yes"=>"Yes","no"=>"No"], null, ['class' => 'form-control','ng-model'=>'listed','required'=>'required']) !!}
                 
        @if ($errors->has('listed')) <p class="alert alert-danger">{{ $errors->first('listed') }}</p> @endif
                <span style = "color:red" ng-show = "signupForm.listed.$dirty && signupForm.listed.$invalid">
                        <span ng-show = "signupForm.listed.$error.required">Listed is required.</span>
                     </span>
                </div>
                </div>
                <div class="col-md-6">
                <label class="col-md-12 control-label">Select Type Of Security</label>
                <div class="col-md-12">
                 {!! Form::select('type_of_securities', [""=>"Select Type Of Security","type1"=>"Type1","type2"=>"Type2"], null, ['class' => 'form-control','ng-model'=>'type_of_securities','required'=>'required']) !!}
                      
        @if ($errors->has('type_of_securities')) <p class="alert alert-danger">{{ $errors->first('type_of_securities') }}</p> @endif
                <span style = "color:red" ng-show = "signupForm.type_of_securities.$dirty && signupForm.type_of_securities.$invalid">
                        <span ng-show = "signupForm.type_of_securities.$error.required">Type Of Security is required.</span>
                     </span>
                </div>
                </div>
                </div>
                </div>
                
                <div class="form-group">
                <div class="row">
                <div class="col-md-6">
                <label class="col-md-12 control-label">Date of Incorporation</label>
                <div class="col-md-12">
               {!! Form::text('date_of_incorporation', null, ['class' => 'form-control','placeholder' => 'Date of Incorporation','ng-model'=>'date_of_incorporation','id'=>'date_of_incorporation','required'=>'required']) !!}
        @if ($errors->has('date_of_incorporation')) <p class="alert alert-danger">{{ $errors->first('date_of_incorporation') }}</p> @endif
                <span style = "color:red" ng-show = "signupForm.date_of_incorporation.$dirty && signupForm.date_of_incorporation.$invalid">
                        <span ng-show = "signupForm.date_of_incorporation.$error.required">Date of Incorporation is required.</span>
                     </span>
                </div>
                </div>
                <div class="col-md-6">
                <label class="col-md-12 control-label">Contact Name</label>
                <div class="col-md-12">
                 {!! Form::text('contact_name', null, ['class' => 'form-control','placeholder' => 'Corporate Identification Number (CIN)','ng-model'=>'contact_name','required'=>'required']) !!}
        @if ($errors->has('contact_name')) <p class="alert alert-danger">{{ $errors->first('contact_name') }}</p> @endif
                <span style = "color:red" ng-show = "signupForm.contact_name.$dirty && signupForm.contact_name.$invalid">
                        <span ng-show = "signupForm.contact_name.$error.required">Contact Name is required.</span>
                     </span>
                </div>
                </div>
                
                
                </div>
                </div>
                
                <div class="form-group">
                <div class="row">
                <div class="col-md-12">
                <label class="col-md-12 control-label">Registered Address</label>
                <div class="col-md-12">
                 {!! Form::textarea('registered_address', null, ['class' => 'form-control','placeholder' => 'Registered Address','ng-model'=>'registered_address','required'=>'required']) !!}
        @if ($errors->has('registered_address')) <p class="alert alert-danger">{{ $errors->first('registered_address') }}</p> @endif
                <span style = "color:red" ng-show = "signupForm.registered_address.$dirty && signupForm.registered_address.$invalid">
                        <span ng-show = "signupForm.registered_address.$error.required">Registered Address is required.</span>
                     </span>
                </div>
                </div>
                </div>
                </div>
                 <div class="form-group">
                <div class="row">
                
                <div class="col-md-6">
                <label class="col-md-12 control-label">Email</label>
                <div class="col-md-12">
                 {!! Form::text('email', null, ['class' => 'form-control','placeholder' => 'Email','ng-model'=>'email','ng-pattern' => '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-z]{2,}$/','required'=>'required']) !!}
        @if ($errors->has('email')) <p class="alert alert-danger">{{ $errors->first('email') }}</p> @endif
               <span ng-show="signupForm.email.$error.required && signupForm.email.$dirty">Email is required</span>
   <span ng-show="!signupForm.email.$error.required && signupForm.email.$error.pattern && signupForm.email.$dirty">Invalid email.</span>
                </div>
                </div>
                 <div class="col-md-6">
                <label class="col-md-12 control-label">Mobile</label>
                <div class="col-md-12">
                 {!! Form::text('mobile_number', null, ['class' => 'form-control','placeholder' => 'Mobile No.','ng-model'=>'mobile_number','ng-pattern' => '/^[0-9]{8,10}$/','required'=>'required']) !!}
        @if ($errors->has('mobile_number')) <p class="alert alert-danger">{{ $errors->first('mobile_number') }}</p> @endif
               <span ng-show="signupForm.mobile_number.$error.required && signupForm.mobile_number.$dirty">Mobile number is required</span>
   <span ng-show="!signupForm.mobile_number.$error.required && signupForm.mobile_number.$error.pattern && signupForm.mobile_number.$dirty">Invalid mobile number.</span>
                </div>
                </div>
                </div>
                </div>
                <div class="form-group">
                <div class="row">
                <div class="col-md-6">
                <label class="col-md-12 control-label">Password</label>
                <div class="col-md-12">
                  {!! Form::password('password', ['class' => 'form-control','placeholder' => 'Password','ng-model' => 'formData.password','ng-minlength' => '6','ng-maxlength' => '16','required' => 'required']) !!}
         @if ($errors->has('password')) <p class="alert alert-danger signuperr">{{ $errors->first('password') }}</p> @endif
        
    <span ng-show="signupForm.password.$error.required && signupForm.password.$dirty">required</span>
   <span ng-show="!signupForm.password.$error.required && (signupForm.password.$error.minlength || signupForm.password.$error.maxlength) && signupForm.password.$dirty">Passwords must be between 6 and 16 characters.</span>
                </div>
                </div>
                <div class="col-md-6">
                <label class="col-md-12 control-label">Confirm Password</label>
                <div class="col-md-12">
                {!! Form::password('password_confirmation', ['class' => 'form-control','id' => 'password_confirmation','placeholder' => 'Confirm Password','ng-model'=>'formData.password_confirmation','valid-password-c'=>'formData.password','required'=>'required']) !!}
           @if ($errors->has('password_confirmation')) <p class="alert alert-danger signuperr">{{ $errors->first('password_confirmation') }}</p> @endif
           <span ng-show="signupForm.password_confirmation.$error.required && signupForm.password_confirmation.$dirty">Please confirm your password.</span>
   <span ng-show="!signupForm.password_confirmation.$error.required && signupForm.password_confirmation.$error.noMatch && signupForm.password.$dirty">Passwords do not match.</span>
                </div>
                </div>
                </div>
                </div>
                 
                 <div class="form-group">
                 <div class="col-md-4">
                 {!! Form::submit('Register',['class' => 'btn btn-signup','ng-disabled' => 'signupForm.$invalid']) !!} 
                 <a href="login">Login</a>
                 </div>
                 
                </div>
                </div>
                {!! Form::close() !!}
                 </div>
                  </div>
    </div>
</section>
@stop