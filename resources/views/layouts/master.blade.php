<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<meta name="_token" content="{{ csrf_token() }}"/>
	<link rel="shortcut icon" type="image/png" href="{{asset('/media/bringoo_favicon.ico') }}"/>
	<title>@yield('title')</title>
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/css/media.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/css/owl.carousel.min.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/css/toastr.min.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Varela+Round&display=swap&subset=hebrew,latin-ext,vietnamese" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
   <!--<title>Bringoo</title>-->
	<script src="{{ URL::asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/popper.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/owl.carousel.js') }}"></script>
	<script src="{{ asset('admin_assets/js/loadingoverlay.min.js') }}"></script>
	 <script type="text/javascript" src="{{ asset('assets/js/toastr.min.js') }}"></script>
	 
	
  </head>
  <style>
	.help-inline { color:red;}
span.help-inline.error::before{ border-bottom: 7px solid #a71515; border-left: 7px solid transparent; border-right: 7px solid transparent; content: ""; height: 0; left: 5px; position: absolute; top: -7px; width: 0;}
span.help-inline.error{ position: relative;}
span.help-inline.error{ background: #a71515 none repeat scroll 0 0; border: 1px solid #a71515; border-radius: 3px; color: #fff; display: block; font-size: 12px; margin: 5px 0 0; padding: 2px 8px; width: 100%; z-index: 1;}
	
	</style>
	<body>
	 <aside class="right-side"> 
                        @if(Session::has('error'))
                            <script type="text/javascript"> 
                                $(document).ready(function(e){
                                    showMessage("{{{ Session::get('error') }}}",'error');
                                });
                            </script>
                        @endif
                        
                        @if(Session::has('success'))
                            <script type="text/javascript"> 
                                $(document).ready(function(e){
                                    showMessage("{{{ Session::get('success') }}}",'success');
                                });
                            </script>
                        @endif

                        @if(Session::has('flash_notice'))
                            <script type="text/javascript"> 
                                $(document).ready(function(e){
                                    showMessage("{{{ Session::get('flash_notice') }}}",'success');
                                });
                            </script>
                        @endif
						
						  @if(Session::has('flash_error'))
                            <script type="text/javascript"> 
                                $(document).ready(function(e){
                                    showMessage("{{{ Session::get('flash_error') }}}",'error');
                                });
                            </script>
                        @endif
    
					
                  </aside>
	<header class="clearHeader" id="dynamic">
		 {{-- @include('includes.header') --}}
	</header>
     @yield('content')
	<footer>
		 @include('includes.footer')
	</footer>	
	
    <script>
	
	$('.favouriteProduct').click(function () { 
	 $.LoadingOverlay("show");
        var _this               = $(this);
        var product_id          = $(this).attr('data-product-id'); 
        var favouriteStatus     = $(this).attr('data-favouritestatus');
        var action_type         = $(this).attr('data-actiontype'); 
        var countFav            = $(this).attr('data-count_likes');  
        var entity_type         = $(this).attr('data-entity_type');   
        
        var favourites_icon         = (favouriteStatus == 0) ? 'far fa-heart' : 'fas fa-heart'; 
          
        var updateFavStatus     = (favouriteStatus == 0) ? '1' : '0';
        
         $.ajax({  
           
		 url: '{{ URL::to('/faviourite-product') }}',
            type: "GET",   
            dataType: "json",   
            data: {product_id:product_id, favouriteStatus:favouriteStatus,action_type:action_type}, 
           success: function(response){  
           
               if(response.success){  
                    _this.attr('data-favouritestatus',updateFavStatus);  
                    _this.html("<i class='"+ favourites_icon +"'></i>"); 
                    
				  
                    if(updateFavStatus == 0){
                        var successMessage = response.success;
                        if(successMessage != '') {
                            //toastr.success(successMessage,  {timeOut: 5000})
							
							showMessage(successMessage,"success");
                        }
                    }else{
                         var successMessage = response.success;
                        if(successMessage != '') {
                          // toastr.success(successMessage,  {timeOut: 5000})
						  showMessage(successMessage,"success");
                        } 
                    }
               } 
			   $.LoadingOverlay("hide");
           } 
         });  
});

	 function showMessage(message,type) { 
        toastr.remove()
        if (type == 'success') {
            toastr.success(message);
        } else if (type == 'error') {
            toastr.error(message);
        } else if (type == 'warning') {
            toastr.warning(message);
        } else if (type == 'info') {
            toastr.info(message);
        }
    }
	
      var owl = $('.owl-carousel');
      owl.owlCarousel({
        margin: 10,
        loop: false,
        nav: true,
        dots: false,
        responsive: {
          0: {
            items: 2
          },
          600: {
            items: 2,
            margin: 20
          },
          1000: {
            items: 5,
            margin: 20
          }
        }
      })
	   
	  
    </script>
    <script>
    $(document).ready(function(){
      $(".side-btn").click(function(){
                $(".side_bar").toggleClass("main");
            });
            $(".overlay").click(function(){
                $(".side_bar").removeClass("main");
            });
            $(".side-btn").click(function(){
                $(".custon-sidebar").toggleClass("overlay_outer");
            });
            $(".overlay").click(function(){
                $(".custon-sidebar").removeClass("overlay_outer");
            });
    });
    $(document).ready(function(){
      $('.menu_nav ul li a').click(function(){
        $('li a').removeClass("active");
        $(this).addClass("active");
    });
    });
  </script>
	<script type="text/javascript" src="{{ URL::asset('admin_assets/firebase/firebase.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('admin_assets/firebase/firebase-app.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('admin_assets/firebase/firebase-auth.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('admin_assets/firebase/firebase-database.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('admin_assets/firebase/firebase-messaging.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('admin_assets/firebase/firebase-storage.js') }}"></script>
 <script>
        MsgElem = document.getElementById("msg")
        TokenElem = document.getElementById("token")
        NotisElem = document.getElementById("notis")
        ErrElem = document.getElementById("err")
        // Initialize Firebase
		  var config = {
    apiKey: "AIzaSyCUvrt3XPCutqnDA7yDp2LdPD_V6TqSOEo",
    authDomain: "bringoo-f3698.firebaseapp.com",
    databaseURL: "https://bringoo-f3698.firebaseio.com",
    projectId: "bringoo-f3698",
    storageBucket: "bringoo-f3698.appspot.com",
    messagingSenderId: "1089804023875",
    appId: "1:1089804023875:web:1d10946686adefd6397cf6",
    measurementId: "G-LK4JFBSKVC"
  };
		  firebase.initializeApp(config);

        const messaging = firebase.messaging();
        messaging
            .requestPermission()
            .then(function () {
                MsgElem.innerHTML = "Notification permission granted." 
                console.log("Notification permission granted.");
				
					//alert(messaging.getToken());
                // get the token in the form of promise
                return messaging.getToken()
            })
            .then(function(token) { console.log(token);
               // TokenElem.innerHTML = "token is : " + token;
				$("#device_token").val(token);
			})
            .catch(function (err) { //console.log(err);
                ErrElem.innerHTML =  ErrElem.innerHTML + "; " + err
                console.log("Unable to get permission to notify.", err);
            });

		messaging.onMessage(function(payload) {
            console.log('Message received. ', payload);
    
			const notificationTitle = "[FG]" + payload.notification.title;
			const notificationOptions = {
				body: payload.notification.body,
				requireInteraction: payload.notification.requireInteraction,
				tag: payload.notification.tag
			};

			if (!("Notification" in window)) {
				console.log("This browser does not support system notifications");
			}
			// Let's check whether notification permissions have already been granted
			else {
			  if (Notification.permission === "granted") {
				// If it's okay let's create a notification
				try {
				  var notification = new Notification(notificationTitle, notificationOptions);
				  notification.onclick = function(event) {
					  event.preventDefault(); //prevent the browser from focusing the Notification's tab
					  window.open(payload.notification.tag, '_blank');
					  notification.close();
				  }
				} catch (err) {
				  try { //Need this part as on Android we can only display notifications thru the serviceworker
					navigator.serviceWorker.ready.then(function(registration) {              
					  registration.showNotification(notificationTitle, notificationOptions);
					});
				  } catch (err1) {
					console.log(err1.message);
				  }
				}
			  }
			}
        });
	</script>
	
<script type="text/javascript">
$(document).ready(function() {
	/*
	var s = $(".clearHeader");
	var pos = s.position();					   
	$(window).scroll(function() {
		var windowpos = $(window).scrollTop();
		if (windowpos >= pos.top & windowpos <=1000) {
			s.addClass("darkHeader");
		} else {
			s.removeClass("darkHeader");	
		}
	});*/
/*	
$(window).scroll(function() {    
    var scroll = $(window).scrollTop();
	
    if (scroll > 1) {
        $(".clearHeader").addClass("darkHeader");
    } else {
        $(".clearHeader").removeClass("darkHeader");
    }
});*/
/*
$(window).scroll(function(){
    var scroll = $(window).scrollTop();
    if (scroll >= 20) {
        $('header').addClass("newClass");
    } else {
        $('header').removeClass("newClass");
    }
	//$(document.body).on('touchmove', scrollTop); // for mobile
});*/
var addition_constant = 0;
$(document.body).on('touchmove', onScroll); // for mobile
$(window).on('scroll', onScroll);
function onScroll() { 
  var addition = ($(window).scrollTop() + window.innerHeight);
 var scroll = $(window).scrollTop();
  
  var scrollHeight = (document.body.scrollHeight - 1);
  // var scroll = $(window).scrollTop();
   console.log(scroll);
  //alert(scrollHeight);
  if (scroll >= 20) {
        $('header').addClass("scrollClass");
    } else {
        $('header').removeClass("scrollClass");
    }
	
}
});
</script>

  </body>
</html>
