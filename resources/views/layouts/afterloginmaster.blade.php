
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<meta name="_token" content="{{ csrf_token() }}"/>
	<link rel="shortcut icon" type="image/png" href="{{asset('/media/bringoo_favicon.ico') }}"/>
	<title>@yield('title')</title>
    <link rel="stylesheet" href="{{ URL::asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/css/media.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/css/owl.carousel.min.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/css/toastr.min.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Varela+Round&display=swap&subset=hebrew,latin-ext,vietnamese" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
	
	
   <!--<title>Bringoo</title>-->
<style>
	.help-inline { color:red;}
span.help-inline.error::before{ border-bottom: 7px solid #a71515; border-left: 7px solid transparent; border-right: 7px solid transparent; content: ""; height: 0; left: 5px; position: absolute; top: -7px; width: 0;}
span.help-inline.error{ position: relative;}
span.help-inline.error{ background: #a71515 none repeat scroll 0 0; border: 1px solid #a71515; border-radius: 3px; color: #fff; display: block; font-size: 12px; margin: 5px 0 0; padding: 2px 8px; width: 100%; z-index: 1;}
	
	</style>
  </head>
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
    
					
                  </aside>
	
     @yield('content')
	<footer>
		 @include('includes.footer')
	</footer>	
<script src="{{ URL::asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/popper.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/owl.carousel.js') }}"></script>
	<script src="{{ asset('admin_assets/js/loadingoverlay.min.js') }}"></script>
	 <script type="text/javascript" src="{{ asset('assets/js/toastr.min.js') }}"></script>
    <script>
      var owl = $('.owl-carousel');
      owl.owlCarousel({
        margin: 10,
        loop: true,
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
    </script>
  </body>
</html>