<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>All Star Delivery - Vendor Login</title>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
 <link rel="shortcut icon" type="image/png" href="{{asset('/media/bringoo_favicon.ico') }}"/>
<link href="{{asset('admin_assets/css/style.css') }}" type="text/css" rel="stylesheet" />
<link href="{{asset('admin_assets/css/bootstrap.min.css') }}" type="text/css" rel="stylesheet" />


<link rel="shortcut icon" type="image/png" href="{{asset('assets/images/favicon.ico') }}"/>
<script type="text/javascript" src="{{ URL::asset('assets/js/jquery.min.js') }}"></script>

  <script>
  
         //var mainApp = angular.module("mainApp", []);
          //mainApp.controller('loginController', function($scope) {
          //});
		 
      </script>
<style>
.alert-success {
    color: #c50e0e;
    background-color: #eb9491;
    border-color: #eb9491;
    font-size: 14px !important;
    padding: 7px !important;
}
</style>

<body class="login-page">
	<div class="container">
	  <div class="row">
   <section class="thankyou">
    	<div class="container">
    		<div class="row">
    			<div class="col-md-12 text-center">
    				<div class="thankyou-outer">
    					<img src="{{asset('/assets/img/check.png') }}">
    					<h2>Thank you</h2>
    					<h4>@if(Session::has('msg')) {!! session('msg') !!} @endif</h4>
						<br/>
						<br/>
						<br/>
    					<a href= "{{ URL::to('/') }}"><button class="back-btn btn">Back To Home</button></a>
    				</div>
    			</div>
    		</div>
    	</div>
    </section>
	  </div>
	</div>
</body>
</html>



      



