@extends('layouts.vendormaster')
@section('title')
{{env('APP_NAME')}} - Change Password
@stop
@section('content') 
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
	  <style>
.alert-danger1{color:#dd4b39;}
.alert{padding:0px !important;}

</style>
<section class="content">
      <div class="row">
      <div class="col-md-12 dashboard-head">
        <h2>Change <span>Password</span></h2>
        <ul class="breadcrumb">
          <li><a href="{{URL::to('/vendor')}}">Home</a></li>
          <li><span>Change password</span></li>
        </ul>
      </div>
</div>

 <div class="row">

        <!-- left column -->
        <div class="col-md-12">
          
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Change password</h3>
            </div> 
 @if(Session::has('msg')) {!! session('msg') !!} @endif
{!! Form::open(array('url' => 'vendor/change-password-post', 'method' => 'post','name'=>'changePassword','files'=>true,'novalidate' => 'novalidate')) !!}
<div class="col-sm-12 p-r-30">
        <div class="panel form-horizontal panel-transparent">
            <div class="panel-body">
                <div class="row">
				  <div class="col-12 col-sm-12 col-md-12">
   <div class="form-group">
          <label class="col-lg-2 col-sm-6 col-xs-12 control-label">Old Password <span class="red-star">*</span></label>
          <div class="col-lg-6 col-sm-6 col-xs-12"> 
          {!! Form::password('old_password', ['class' => 'form-control','placeholder' => 'Old Password','ng-model' => 'formData.password','ng-minlength' => '6','ng-maxlength' => '16','required' => 'required']) !!}
        @if ($errors->has('password'))
        <p class=" alert-danger1">{{ $errors->first('old_password') }}</p>
        @endif 
      
         </div>
        </div>
		</div>
		<div class="col-12 col-sm-12 col-md-12">
		<div class="form-group">
          <label class="col-md-2 control-label">New Password <span class="red-star">*</span></label>
          <div class="col-lg-6 col-sm-6 col-xs-12"> 
          {!! Form::password('password', ['class' => 'form-control','placeholder' => 'Password','ng-model' => 'formData.password','ng-minlength' => '6','ng-maxlength' => '16','required' => 'required']) !!}
        @if ($errors->has('password'))
        <p class=" alert-danger1">{{ $errors->first('password') }}</p>
        @endif 
      
         </div>
        </div>
		</div>
		<div class="col-12 col-sm-12 col-md-12">
   <div class="form-group">
          <label class="col-md-2 control-label">Confirm Password <span class="red-star">*</span></label>
          <div class="col-lg-6 col-sm-6 col-xs-12">
          {!! Form::password('password_confirmation', ['class' => 'form-control','id' => 'password_confirmation','placeholder' => 'Confirm Password','ng-model'=>'formData.password_confirmation','valid-password-c'=>'formData.password','required'=>'required']) !!}
        @if ($errors->has('password_confirmation'))
        <p class=" alert-danger1">{{ $errors->first('password_confirmation') }}</p>
        @endif
          </div>
        </div>
		</div>
		 </div>
					
					 
                </div>
            </div>
        </div>
    
     <div class="row form-btn text-center">
        <div class="col-sm-12 p-r-30">
        <div class="col-md-12"> 
        {!! Form::submit('Save',['class' => 'btn btn-primary btn-flat subbtn', 'type' => 'submit']) !!}
        {!! Form::button('Cancel',['class' => 'btn btn-flat subbtn','data-dismiss'=>'modal']) !!}
        </div>
    </div>
    {!! Form::close() !!}
   </div>
            <!-- /.box-body -->
          </div>
         
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
@stop 