<meta charset="utf-8">
<meta name="_token" content="{{ csrf_token() }}"/>
<title>@yield('title')</title>
 
   
<meta name="Description" content="@yield('description')" />
<meta name="keywords" content="@yield('keywords')" />
<meta charset="utf-8">
<meta name="google-site-verification" content="EX-G6DXOYS1FOpVKymHumw0gY4gXmxRqG929xqIzKvY" />
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
<link rel="icon" href="{{ URL::asset('resources/assets/images/favicon.png') }}" type="images/png" sizes="16x16">
<link href="{{ URL::asset('resources/assets/css/bootstrap.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('resources/assets/css/font-awesome.min.css') }}" rel="stylesheet">
<link rel="stylesheet" href="{{ URL::asset('resources/assets/css/style.css') }}" type="text/css">
<script type="text/javascript" src="{{ URL::asset('resources/assets/js/jquery.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('resources/assets/js/bootstrap.min.js') }}"></script>


 <!-- Date Picker -->
  <link rel="stylesheet" href="{{ asset('admin_assets/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
  <!-- datepicker -->
<script src="{{ asset('admin_assets/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-134457716-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-134457716-1');
</script>
<!-- Facebook Pixel Code -->
<script>
  !function(f,b,e,v,n,t,s)
  {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
  n.callMethod.apply(n,arguments):n.queue.push(arguments)};
  if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
  n.queue=[];t=b.createElement(e);t.async=!0;
  t.src=v;s=b.getElementsByTagName(e)[0];
  s.parentNode.insertBefore(t,s)}(window, document,'script',
  'https://connect.facebook.net/en_US/fbevents.js');
  fbq('init', '1789324091130596');
  fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
  src="https://www.facebook.com/tr?id=1789324091130596&ev=PageView&noscript=1"
/></noscript>
<!-- End Facebook Pixel Code -->