@extends('layouts.master')

@section('title')Contact Us for Complete RTA Support & Services | SAG RTA @stop

@section('description')Need RTA services? Contact SAG Infotech Pvt. Ltd, Rajasthan’s first/category 1st registrar and transfer agent. @stop

@section('keywords')contact for rta services, contact for registrar & transfer agent, rta services, registrar and share transfer agent services @stop

@section('content') 
<script src = "{{ URL::asset('resources/assets/js/angular.min.js') }}"></script> 
<script>

  var app = angular.module('app', []);

app.controller('homeCtrl', function($scope) {

});

      </script>
<section class="service-slider-sec sliderservices5">
  <div class="container">
    <div class="row">
      <div class="col-md-6 banner-text">
        <h1 class="page-title">Contact SAG RTA</h1>
      </div>
      <div class="col-md-6 text-right page-breadcrumb">
        <ul class="breadcrumb">
          <li><a href="{{URL::to('')}}">Home</a></li>
          <li class="active"><span>Contact Us</span></li>
        </ul>
      </div>
    </div>
  </div>
</section>
<section class="main-page-sec">
  <div class="container">
    <div class="row">
      <div class="col-md-12 title-heading">
        <h2>Contact <span>Us</span></h2>
        @if(Session::has('msg')) {!! session('msg') !!} @endif <span class="title-border-light"><i class="fa fa-area-chart"></i></span> </div>
    </div>
    <div class="client-form row" ng-app="app" ng-controller="homeCtrl">
      <div class="col-md-7"> {!! Form::open(array('url' => 'contactpost', 'method' => 'post','name'=>'signupForm','novalidate' => 'novalidate')) !!}
        <div class="form-group row">
          <div class="form-field col-md-6 form-m-bttm"> {!! Form::text('name', null, ['class' => 'form-control','placeholder' => 'Name*','ng-model'=>'name','required'=>'required']) !!}
            
            @if ($errors->has('name'))
            <p class="alert alert-danger">{{ $errors->first('name') }}</p>
            @endif <span style = "color:red" ng-show = "signupForm.name.$dirty && signupForm.name.$invalid"> <span ng-show = "signupForm.name.$error.required">Name is required.</span> </span> </div>
          <div class="form-field col-md-6"> {!! Form::text('company', null, ['class' => 'form-control','placeholder' => 'Company']) !!} </div>
        </div>
        <div class="form-group row">
          <div class="form-field col-md-6 form-m-bttm"> {!! Form::text('email', null, ['class' => 'form-control','placeholder' => 'Email*','ng-model'=>'email','ng-pattern' => '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-z]{2,}$/','required'=>'required']) !!}
            
            @if ($errors->has('email'))
            <p class="alert alert-danger">{{ $errors->first('email') }}</p>
            @endif <span ng-show="signupForm.email.$error.required && signupForm.email.$dirty">Email is required</span> <span ng-show="!signupForm.email.$error.required && signupForm.email.$error.pattern && signupForm.email.$dirty">Invalid email.</span> </div>
          <div class="form-field col-md-6"> {!! Form::text('mobile_number', null, ['class' => 'form-control','placeholder' => 'Mobile No.*','ng-model'=>'mobile_number','ng-pattern' => '/^[0-9]{8,10}$/','required'=>'required']) !!}
            
            @if ($errors->has('mobile_number'))
            <p class="alert alert-danger">{{ $errors->first('mobile_number') }}</p>
            @endif <span ng-show="signupForm.mobile_number.$error.required && signupForm.mobile_number.$dirty">Mobile number is required</span> <span ng-show="!signupForm.mobile_number.$error.required && signupForm.mobile_number.$error.pattern && signupForm.mobile_number.$dirty">Invalid mobile number.</span> </div>
        </div>
        <div class="form-group row">
          <div class="form-field col-md-12"> {!! Form::textarea('message', null, ['class' => 'form-control','placeholder' => 'Message*','ng-model'=>'registered_address','required'=>'required']) !!}
            
            @if ($errors->has('message'))
            <p class="alert alert-danger">{{ $errors->first('message') }}</p>
            @endif <span style = "color:red" ng-show = "signupForm.message.$dirty && signupForm.message.$invalid"> <span ng-show = "signupForm.message.$error.required">Message is required.</span> </span> </div>
        </div>
        {!! Form::submit('Submit',['class' => 'btn btn-register','ng-disabled' => 'signupForm.$invalid']) !!}
        
        {!! Form::close() !!} </div>
      <div class="contact-details col-md-5">
      <div class="row">
       <div class="col-md-1">&nbsp;</div>
      <div class="col-md-11"> <ul class="contact-list">
          <li><em class="fa fa-map" aria-hidden="true"></em> <span>B-9, 2nd & 3rd Floor, Behind WTP South Block, Mahalaxmi Nagar, Malviya Nagar, JAIPUR, RAJASTHAN, 302017 </span> </li>
          <li><em class="fa fa-phone" aria-hidden="true"></em> <span>Phone No. : 0141-4727374 (60 Line)</span> </li>
          <li><em class="fa fa-envelope" aria-hidden="true"></em> <span>Email : <a href="mailto:info@sagrta.com">info@sagrta.com</a>, <a href="mailto:grievances@sagrta.com">grievances@sagrta.com</a></span> </li>
          <li> <em class="fa fa-clock-o" aria-hidden="true"></em><span>10:00 AM 7:00 PM (Mon-Fri) <br>
            10:00 AM 5:00 PM (Sat) </span> </li>
        </ul></div>
        </div>
      </div>
    </div>
  </div>
</section>
<section class="location-map">
<div class="container">
<div class="text-center col-md-12 title-heading">
        <h2>Location <span>Map</span></h2>
        @if(Session::has('msg')) {!! session('msg') !!} @endif <span class="title-border-light"><i class="fa fa-area-chart"></i></span>
         </div>
         </div>
   <iframe class="resp-iframe" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d889.5806480725205!2d75.825981063818!3d26.89325529590306!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x396db6eb3fffffff%3A0x2e11539d7e82aee4!2sSAG+Infotech!5e0!3m2!1sen!2sin!4v1420697520182" width="100%" height="400px" frameborder="0" style="border: 0"></iframe>
</section>
@stop 