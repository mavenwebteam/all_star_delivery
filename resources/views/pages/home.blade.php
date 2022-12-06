@extends('layouts.master')
@section('title')SAG RTA: Simplified Registrar & Transfer Agent (RTA) Services @stop
@section('description')SAG Infotech Pvt. Ltd. provides easy process of registrar and transfer agent services for smart companies in India. We are category 1st & Rajasthan’s 1st RTA. @stop
@section('keywords')rta services, registrar & share transfer agents services, registrar and transfer agent, registrar and share transfer agent, rta, share transfer agent, registrar services @stop
@section('content') 
<script>
  var app = angular.module('app', []);
app.controller('homeCtrl', function($scope) {
});
      </script>
<section class="slider-sec">
  <div class="container">
    <div class="row">
      <div class="banner-text col-md-8">
        <h1>Registrar & Transfer Agent Services</h1>
        <p>We are category 1st Registrar & Transfer Agent.</p>
        <img src="{{ URL::asset('resources/assets/images/banner.jpg') }}" alt="banner" /> </div>
      <div class="col-md-4">
        <div class="enquery-form" ng-app="app" ng-controller="homeCtrl">
          <h2>Do You Need RTA? </h2>
          {!! Form::open(array('url' => 'enquirepost', 'method' => 'post','name'=>'signupForm','novalidate' => 'novalidate')) !!}
          <p> {!! Form::text('name', null, ['class' => 'form-control','size' => '40','placeholder' => 'Name*','ng-model'=>'name','required'=>'required']) !!}
            
            @if ($errors->has('name'))
          <p class="alert alert-danger">{{ $errors->first('name') }}</p>
          @endif <span style = "color:red" ng-show = "signupForm.name.$dirty && signupForm.name.$invalid"> <span ng-show = "signupForm.name.$error.required">Name is required.</span> </span>
          </p>
          <p> {!! Form::text('email', null, ['class' => 'form-control','size' => '40','placeholder' => 'Email*','ng-model'=>'email','ng-pattern' => '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-z]{2,}$/','required'=>'required']) !!}
            
            @if ($errors->has('email'))
          <p class="alert alert-danger">{{ $errors->first('email') }}</p>
          @endif <span ng-show="signupForm.email.$error.required && signupForm.email.$dirty">Email is required</span> <span ng-show="!signupForm.email.$error.required && signupForm.email.$error.pattern && signupForm.email.$dirty">Invalid email.</span>
          </p>
          <p> {!! Form::text('mobile_number', null, ['class' => 'form-control','placeholder' => 'Mobile No.*','ng-model'=>'mobile_number','ng-pattern' => '/^[0-9]{8,10}$/','required'=>'required']) !!}
            
            @if ($errors->has('mobile_number'))
          <p class="alert alert-danger">{{ $errors->first('mobile_number') }}</p>
          @endif <span ng-show="signupForm.mobile_number.$error.required && signupForm.mobile_number.$dirty">Mobile number is required</span> <span ng-show="!signupForm.mobile_number.$error.required && signupForm.mobile_number.$error.pattern && signupForm.mobile_number.$dirty">Invalid mobile number.</span>
          </p>
          <p> {!! Form::textarea('message', null, ['class' => 'form-control','placeholder' => 'Message*','ng-model'=>'registered_address','required'=>'required']) !!}
            
            @if ($errors->has('message'))
          <p class="alert alert-danger">{{ $errors->first('message') }}</p>
          @endif <span style = "color:red" ng-show = "signupForm.message.$dirty && signupForm.message.$invalid"> <span ng-show = "signupForm.message.$error.required">Message is required.</span> </span>
          </p>
          <p> {!! Form::submit('Enquire Now',['class' => 'btn btn-submit','ng-disabled' => 'signupForm.$invalid']) !!} </p>
          {!! Form::close() !!} </div>
      </div>
    </div>
  </div>
</section>
<section class="main-page-sec homepage-bg">
<div class="container">
<div class="row">
      <div class="col-md-12 title-heading">
        <h2> Rajasthan's 1<sup>st</sup> Registrar & <span> Share Transfer Agent</span></h2>
        <span class="title-border-light"><i class="fa fa-area-chart"></i></span> </div>
      <div class="home-disp-text">
        <p>SAG Infotech is now also a registrar & share transfer agent while being the Rajasthan's 1st RTA services provider. The company has been granted the role of a registrar and transfer agent via authorization by the Securities and Exchange Board of India.
          The company's share transfer agency also claims complete services over multiple requirements of the investors including dematerialization of securities via CDSL/NSDL depositories, dividend payout through ECS transfer.
          Also, the company being the share transfer agent would certainly transfer securities as per the demand of the investors while the company also manages complete record keeping of the transactions.</p>
        <p>SAG RTA is also responsible for all the meetings, mailing and reporting activities on behalf of the investors with regulatory reporting to the authorities every time it is required to be furnished.
          The company will settle all the IEPF claims while performing all the basic RTA services such as Transmission/ Name Deletion/ Transposition/ Change of Name/Change of Client Signature and Address of the investors via its registrar & share transfer agents services.
          SAG Infotech is a pioneer in the tax industry from 2 decades with its industry as well as profession-specific tax software for the chartered accountant, company secretaries and individual business firms.
          SAG RTA is dedicated towards all the share registrar services with its world-class infrastructure and professional pool of skilled RTA agents working all day long to simplify the RTA services.
          As a new share transfer agent, we are obliged to offer quick solutions to the back end corporate process for the mutual fund houses by strategically providing customized services to all of our respected clients.</p>
      </div>
    </div>
</div>
</section>
<section class="main-page-sec">
  <div class="container">
    <div class="row">
      <div class="col-md-12 title-heading">
        <h2> Our RTA <span> Services</span></h2>
        <span class="title-border-light"><i class="fa fa-area-chart"></i></span> </div>
      <div class="col-md-4">
        <div class="product-item-box">
          <div class="product-icon"><em class="fa fa-gamepad" aria-hidden="true"></em></div>
          <p>Securities Dematerialization through NSDL and CDSL Depositories</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="product-item-box">
          <div class="product-icon"><em class="fa fa-ban" aria-hidden="true"></em></div>
          <p>Payout of Dividend / Interest and ECS Transfers</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="product-item-box">
          <div class="product-icon"><em class="fa fa-money" aria-hidden="true"></em></div>
          <p>Transfer of Securities and Investor Record-keeping</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="product-item-box">
          <div class="product-icon"><em class="fa fa-clone" aria-hidden="true"></em></div>
          <p>Revalidation of Dividend</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="product-item-box">
          <div class="product-icon"><em class="fa fa-envelope" aria-hidden="true"></em></div>
          <p>Online, Phone and Email Assistance for Investor Inquiries</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="product-item-box">
          <div class="product-icon"><em class="fa fa-file" aria-hidden="true"></em></div>
          <p>Regulatory Reporting</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="product-item-box">
          <div class="product-icon"><em class="fa fa-share-alt" aria-hidden="true"></em></div>
          <p>Reporting, Mailing and Meeting Services for Beneficial Holders</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="product-item-box">
          <div class="product-icon"><em class="fa fa-certificate" aria-hidden="true"></em></div>
          <p>Share Certificate Transfer</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="product-item-box">
          <div class="product-icon"><em class="fa fa-certificate" aria-hidden="true"></em></div>
          <p>Issuance of Duplicate Share Certificate </p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="product-item-box">
          <div class="product-icon"><em class="fa fa-handshake-o" aria-hidden="true"></em></div>
          <p>Settlement of IEPF Claims</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="product-item-box">
          <div class="product-icon"><em class="fa fa-trash" aria-hidden="true"></em></div>
          <p>Transmission/ Name Deletion/ Transposition/ Change of Name</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="product-item-box">
          <div class="product-icon"><em class="fa fa-edit" aria-hidden="true"></em></div>
          <p>Change of Client Signature and Address</p>
        </div>
      </div>
    </div>
  </div>
</section>
<section class="buisiness-page-sec">
  <div class="container">
    <div class="row">
      <div class="col-md-12 title-heading">
        <h2>Other Business <span>Expertise</span></h2>
        <span class="title-border-light"><i class="fa fa-area-chart"></i></span>
        <p class="business-title">We are the leading tax solution provider to 25,000+ clients across India,<br />
          with products ranging from income tax to GST software.</p>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6">
        <div class="feature"> <a href="#">
          <div class="fbox-photo"> <img src="{{ URL::asset('resources/assets/images/software-development.jpg') }}" alt="Software Development"> </div>
          <div class="fbox-over">
            <h3 class="title">Software Development</h3>
            <div class="fbox-content">
              <p>We offer multiple tax software solutions as per the Indian tax compliance to the clients for more than 2 decades</p>
            </div>
          </div>
          </a> </div>
      </div>
      <div class="col-md-6">
        <div class="feature"> <a href="#">
          <div class="fbox-photo"> <img src="{{ URL::asset('resources/assets/images/website-mobile-app-development.jpg') }}" alt="Mobile App Development"> </div>
          <div class="fbox-over">
            <h3 class="title">Website &amp; Mobile App Development</h3>
            <div class="fbox-content">
              <p>We also offers unmatched services in website &amp; mobile development to the clients both domestic &amp; overseas.</p>
            </div>
          </div>
          </a> </div>
      </div>
    </div>
  </div>
</section>
<section class="hm-about-sec">
  <div class="container">
    <div class="row">
      <div class="col-md-12 title-heading">
        <h2>ABOUT <span>US</span></h2>
        <span class="title-border-light"><i class="fa fa-area-chart"></i></span> </div>
    </div>
    <div class="row">
      <div class="col-md-6">
        <h3>WHO WE ARE</h3>
        <p>We at SAG Infotech, continuously delivering the best of tax solutions to the professionals and taxpayers since 2 decades.</p>
        <p>Keeping in mind the requirement of tax software at every step by the tax professionals made us offer some of the flagship products such as Genius, Gen GST, Gen CompLaw and many more.</p>
        <p>In our endeavour to keep going, here we present the best in segment Registrar & Share Transfer Agent Services to the issuers.</p>
      </div>
      <div class="col-md-6"> <img src="{{ URL::asset('resources/assets/images/about-us.png') }}" alt=""> </div>
    </div>
  </div>
</section>
@stop 