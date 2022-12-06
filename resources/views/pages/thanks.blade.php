@extends('layouts.master')
@section('title')Thanks For Contact Us | AllStar @stop
@section('description')Thanks For Contact Us @stop
@section('keywords')Thanks For Contact Us @stop
@section('content')
<style>
.alert-success {
    color: #155724;
    background-color: #d4edda;
    border-color: #c3e6cb;
    font-size: 14px !important;
    padding: 7px !important;
}
</style>
   <section class="thankyou">
    	<div class="container">
    		<div class="row">
    			<div class="col-md-12 text-center">
    				<div class="thankyou-outer">
    					<img src="{{asset('/assets/img/check.png') }}">
    					<h2>Thank you</h2>
    					<h4>@if(Session::has('msg')) {!! session('msg') !!} @endif</h4>
    					<a href= "{{ URL::to('/') }}"><button class="back-btn btn">Back To Home</button></a>
    					
    				</div>
    			</div>
    		</div>
    	</div>
    </section>
@stop



      



