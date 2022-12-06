@extends('layouts.afterloginmaster')
@section('title')
AllStar | Reset Password 
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



<section class="signup">
     <div class="container">
       <div class="row">
         <div class="col-md-12">
           <div class="signup-outer">
             <div class="signup-inner">
               <a href="{{ URL::to('/') }}" class="logo-outer">
                 <img src="{{asset('/assets/img/footer-logo.png') }}" alt="#">
               </a>
             </div>
             <div class="signup-btm">
               <h3>Reset Password</h3>
                {!! Form::open(array('url' => '/create-password-post', 'method' => 'post','name'=>'addContentForm','files'=>true,'novalidate' => 'novalidate','id' => 'reset_password')) !!}
				{!! Form::hidden('uniqurl',$uniqurl) !!}
                 <div class="form-group <?php echo ($errors->first('password')?'has-error':''); ?>">
                                 {{ Form::password('password', ['class' => 'form-control', 'placeholder'=>"New Password"]) }}
								  {!! Form::hidden('uniqurl',$uniqurl) !!}
								   <span class="help-inline"></span>
								   @if ($errors->has('password')) <p class=" alert-danger signuperr">{{ $errors->first('password') }}</p> @endif
                                </div>
                 
                 <div class="form-group <?php echo ($errors->first('password_confirmation')?'has-error':''); ?>">
                                 {{ Form::password('password_confirmation', ['class' => 'form-control', 'placeholder'=>"Confirm Password"]) }}
								   <span class="help-inline"></span>
								    @if ($errors->has('password_confirmation')) <p class=" alert-danger signuperr">{{ $errors->first('password_confirmation') }}</p> @endif
                                </div>
                  <button type="submit" class="btn btn-primary" >Submit <img src="{{asset('/assets/img/send.png') }} " alt="#"></button>
                    
                 
                {{ Form::close() }} 
             </div>
           </div>
         </div>
       </div>
     </div> 
    </section>
@stop