<div class="container">
<!-- The core Firebase JS SDK is always required and must be listed first -->
<script src="https://www.gstatic.com/firebasejs/7.2.2/firebase-app.js"></script>

<!-- TODO: Add SDKs for Firebase products that you want to use
     https://firebase.google.com/docs/web/setup#available-libraries -->
<script src="https://www.gstatic.com/firebasejs/7.2.2/firebase-analytics.js"></script>


<script src="https://www.gstatic.com/firebasejs/3.1.0/firebase-database.js"></script>


<script>
  // Your web app's Firebase configuration
  var firebaseConfig = {
    apiKey: "AIzaSyCUvrt3XPCutqnDA7yDp2LdPD_V6TqSOEo",
    authDomain: "bringoo-f3698.firebaseapp.com",
    databaseURL: "https://bringoo-f3698.firebaseio.com",
    projectId: "bringoo-f3698",
    storageBucket: "bringoo-f3698.appspot.com",
    messagingSenderId: "1089804023875",
    appId: "1:1089804023875:web:1d10946686adefd6397cf6",
    measurementId: "G-LK4JFBSKVC"
  };
  // Initialize Firebase
  firebase.initializeApp(firebaseConfig);
  firebase.analytics();
</script>
<?php use App\Http\Controllers\Front\homeController;  ?>
  			<div class="row">
  				<div class="col-md-12">
  					<div class="custom-header">
					
					<nav class="navbar navbar-expand-lg navbar-light">
						<span class="logo">
							<a class="navbar-brand" href="{{ URL::to('/') }}">
  								
								
								
  							</a>
						</span>
					  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
						<span class="navbar-toggler-icon"></span>
					  </button>

					  <div class="collapse navbar-collapse" id="navbarSupportedContent">
						<ul class="navbar-nav mr-auto user-login">
						<?php if(!empty(Auth::user())){?>
						 <li class="nav-item active">
							 <span class="user-img">
								@if(Auth::user()->profile_pic &&  File::exists(USER_ROOT_PATHS.Auth::user()->profile_pic)) 
								<img src="<?php echo USER_URLS.Auth::user()->profile_pic; ?>"  alt="">
								@else
									<img src="{{asset('/media/notfound-image.png') }}" alt="">
								@endif
							</span>
						  </li>
						   <li class="nav-item">
							 <span class="user-info">
								<div class="dropdown">
								<button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								{{ Auth::user()->first_name || Auth::user()->last_name ? Auth::user()->first_name.' '.Auth::user()->last_name : Auth::user()->email}}
								</button>
								<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
									<a class="dropdown-item" href="{{ URL::to('/myaccount') }}">Profile</a>
									<a class="dropdown-item" href="{{ URL::to('/logout') }}">Logout</a>
								
								</div>
								</div>
							</span>
						  </li>
						  <li class="nav-item active">
							
						  </li>
						   <li class="nav-item active">
							<a href="{{ URL::to('/signup-store') }}" class="signup">Sign up Store</a>
						  </li>
						  <input type="hidden" id="country_code" name="country_code" value="{{Auth::user()->country_code}}"/>
				<input type="hidden" id="email_id" name="email_id" value="{{Auth::user()->email}}"/>
				<input type="hidden" id="first_name" name="first_name" value="{{Auth::user()->first_name}}"/>
				<input type="hidden" id="last_name" name="last_name" value="{{Auth::user()->last_name}}"/>
				<input type="hidden" id="mobile_no" name="mobile_no" value="{{Auth::user()->mobile}}"/>
				<input type="hidden" id="user_id" name="user_id" value="{{Auth::user()->id}}"/>
				<input type="hidden" id="user_image" name="user_image" value="<?php echo USER_URL.Auth::user()->profile_pic; ?>"/>
				<input type="hidden" id="user_type" name="user_type" value="{{Auth::user()->type}}"/>
 					<script>
					$(document).ready(function() { 
							var country_code    = $("#country_code").val();
							var email_id  = $("#email_id").val(); // consultant
							var first_name  = $("#first_name").val();
							var last_name  = $("#last_name").val();
							var mobile_no  = $("#mobile_no").val();
							var user_id  = $("#user_id").val();
							var user_image  = $("#user_image").val();
							var user_type  = $("#user_type").val();
							
							//email_id  = $("#email_id").val();
							//$('.attachment_outer').hide();
							//email_id  = $("#email_id").val();
							//var chat_node     =  senderID+","+receiverID;
							//var senderMessage =  $('.msg').val();
							
							//var senderMessage = senderMessage.trim();
			//if(senderMessage != '' && senderMessage != null && senderMessage != undefined){
							firebase.database().ref('Users/'+user_id).set({
									'country_code' 	 : country_code,
									'email_id'	 : email_id,
									'first_name': first_name,
									'last_name': last_name,
									'mobile_no': mobile_no,
									'user_id': user_id,
									'device' 	 : 'web',
									'user_image'		 : user_image,
									'user_type'	 : user_type,
									//'type'	 	 : 'text',
									//'url'	 	 : '',
									//'thumbNail'	 : '',
							}); 
					});
					</script>
						<?php }else{ ?> 
						  
						
						  <li class="nav-item active">
							<a href="{{ URL::to('/login') }}" class="login">Login</a>
						  </li>
						  <li class="nav-item">
							<a href="{{ URL::to('/signup') }}" class="signup">Sign up</a>
						  </li>
						  <li class="nav-item">
							<a href="{{ URL::to('/signup-store') }}" class="signup">Sign up Store</a>
						  </li>
						  <?php } ?>
						</ul>
						
							
							
						
					  </div>
					  <?php if(!empty(Auth::user())){?>
					  <span class="add-cart" id="add-cart">
						@include('front.shop.cartitem')
					</span>
					  <?php } ?>
					</nav>
					
  			</div>
 </div>
 </div>
 
 
 
