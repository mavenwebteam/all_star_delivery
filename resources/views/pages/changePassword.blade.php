@extends('layouts.master')
@section('title')Change password | SAG RTA @stop
@section('description')Change password | SAG RTA @stop
@section('keywords')Change password | SAG RTA @stop
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

});
      </script>
     <section class="myaccount-head-sec">
   <div class="container"><h2>Change Password</h2></div>
  </section>
  <section class="myaccount-page-sec">
  <div class="container">
    <div class="row">
      <div class="col-md-3">
        <div class="myaccount-box-lt">
          <h2 class="card-title mb-0">My Account</h2>
          <div class="sidebar-content">
          @include('includes.myaccount-menu')
          </div>
        </div>
      </div>
        <div class="col-md-9">
       <div class="row">
          <div class="col-md-12">
            <div class="card" ng-app="app" ng-controller="homeCtrl">
      <div class="card-header">
        <div class="form-group">
          <div class="col-md-8">
            <h2 class="card-title">Change Password</h2>
          </div>
        </div>
      </div>
     <div class="card-body pass-chang-l">
        @if(Session::has('msg')) {!! session('msg') !!} @endif
               {!! Form::open(array('url' => '/myaccount/change-password-post', 'method' => 'post','name'=>'signupForm','novalidate' => 'novalidate')) !!}

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
                 <div class="col-md-4 ">
                 {!! Form::submit('Update',['class' => 'btn btn-submit','ng-disabled' => 'signupForm.$invalid']) !!}
                 </div>
                </div>
                {!! Form::close() !!}
     </div> 
      </div>
		   </div>
		   </div>
		   </div>
		   </div>
		   </div>
</section>
@stop