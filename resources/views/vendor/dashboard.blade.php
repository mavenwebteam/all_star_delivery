@extends('layouts.vendormaster')
@section('title')
  All Star Delivery - Dashboard
@stop
@section('content')
  <section class="content-header">
    <h1>
      {{ __('vendor.menu_dashboard') }}
      <small> {{ __('vendor.control_panel') }}</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> {{ __('vendor.home') }}</a></li>
      <li class="active"> {{ __('vendor.menu_dashboard') }}</li>
    </ol>
  </section>
  
 <!-- Main content -->
 <section class="content">
	@if(Session::has('msg')) {!! session('msg') !!} @endif
  <!-- Small boxes (Stat box) -->
  <div class="row">
    {{-- Today’s order --}}
    <div class="col-lg-3 col-xs-6">
      <!-- small box -->
      <a href="{{ route('vendor.orders.today') }}" data-toggle="tooltip" title="Current Order">
      <div class="small-box bg-aqua">
        <div class="inner">
          <h3>{{!empty($total_today_order) ? $total_today_order : 0}} </h3>
          <p> {{ __('vendor.todays_order') }}</p>
        </div>
        <div class="icon">
          <i class="ion ion-bag"></i>
        </div>
        <span class="small-box-footer"> {{ __('vendor.more_info') }} <i class="fa fa-arrow-circle-right"></i></span>
      </div>
      </a>
    </div>
    <!-- ./col -->
    {{-- Out of Stock Products --}}
    <div class="col-lg-3 col-xs-6">
      <!-- small box -->
      <a href="{{ route('vendor.menu-manager.index', ['stock'=>2]) }}" data-toggle="tooltip" title="Menu Manager">
      <div class="small-box bg-red">
        <div class="inner">
          <h3>{{!empty($total_outofstock_product) ? $total_outofstock_product : 0}}</h3>
          <p>{{ __('vendor.out_of_stock_product') }}</p>
        </div>
        <div class="icon">
          <i class="fa fa-product-hunt"></i>
        </div>
        <span class="small-box-footer">{{ __('vendor.more_info') }} <i class="fa fa-arrow-circle-right"></i></span>
      </div>
      </a>
    </div>
    {{-- In Stock Products --}}
    <div class="col-lg-3 col-xs-6">
      <!-- small box -->
      <a href="{{ route('vendor.menu-manager.index',  ['stock'=>1]) }}" data-toggle="tooltip" title="Menu Manager">
      <div class="small-box bg-green">
        <div class="inner">
          <h3>{{!empty($total_instock_product) ? $total_instock_product : 0}}</h3>
          <p>{{ __('vendor.in_stock_product') }}</p>
        </div>
        <div class="icon">
          <i class="fa fa-product-hunt"></i>
        </div>
        <span href="#" class="small-box-footer">{{ __('vendor.more_info') }} <i class="fa fa-arrow-circle-right"></i></span>
      </div>
      </a>
    </div>
    {{-- Order not yet accepted  --}}
    <div class="col-lg-3 col-xs-6">
      <!-- small box -->
      <a href="{{ route('vendor.orders.notyetAccepted') }}" data-toggle="tooltip" title="Recent order">
      <div class="small-box bg-blue">
        <div class="inner">
          <h3>{{ $order_not_yet_accepted }}</h3>
          <p>{{ __('vendor.order_not_yet_accepted') }}</p>
        </div>
        <div class="icon">
          <i class="fa fa-shopping-cart" aria-hidden="true"></i>
        </div>
        <span href="#" class="small-box-footer">{{ __('vendor.more_info') }} <i class="fa fa-arrow-circle-right"></i></span>
      </div>
      </a>
    </div>
  </div>
  <!-- /.row -->
 
  <div class="row">
    {{-- Total Earning --}}
    <div class="col-lg-3 col-xs-6">
      <!-- small box --> 
      <a href="{{ route('vendor.orders.delivered') }}" data-toggle="tooltip" title="Earning">
        <div class="small-box bg-yellow">
          <div class="inner">
            <h3>Ks {{!empty($todays_earning) ? $todays_earning : 0}}</h3>
            <p>{{ __('vendor.today_total_earning') }}</p>
          </div>
          <div class="icon">
          <i class="fa fa-money" aria-hidden="true"></i>
          </div>
          <span href="#" class="small-box-footer">{{ __('vendor.more_info') }} <i class="fa fa-arrow-circle-right"></i></span>
        </div>
      </a>
    </div>
     {{-- todays cancel order --}}
     <div class="col-lg-3 col-xs-6">
      <!-- small box -->
      <a href="{{ route('vendor.orders.todaycancel') }}" data-toggle="tooltip" title="Orders">
      <div class="small-box bg-purple">
        <div class="inner">
          <h3>{{!empty($todays_cancel_order) ? $todays_cancel_order : 0}}</h3>
          <p>{{ __('vendor.todays_cancel_order') }}</p>
        </div>
        <div class="icon">
          <i class="fa fa-shopping-bag" aria-hidden="true"></i>
        </div>
        <span class="small-box-footer">{{ __('vendor.more_info') }} <i class="fa fa-arrow-circle-right"></i></span>
      </div>
      </a>
    </div>
    {{-- todays commission amount --}}
    <div class="col-lg-3 col-xs-6">
      <!-- small box -->
      <a href="{{ route('vendor.orders.delivered') }}" data-toggle="tooltip" title="Orders">
      <div class="small-box bg-teal">
        <div class="inner">
          <h3>{{!empty($todays_commission_amount) ? $todays_commission_amount : 0}}</h3>
          <p>{{ __('vendor.todays_commission_amount') }}</p>
        </div>
        <div class="icon">
          <i class="fa fa-credit-card" aria-hidden="true"></i>
        </div>
        <span class="small-box-footer">{{ __('vendor.more_info') }} <i class="fa fa-arrow-circle-right"></i></span>
      </div>
      </a>
    </div>
  </div>
  <!-- /.row -->
  @if($is_store)
  {{-- graph --}}
  <div class="row">
    <div class="col-sm-12 col-md-12 col-lg-12">
      <div class="box visitors-map">
        <div class="box-header">
          <h2><strong>{{__('Orders')}}</strong> {{__('activity')}}</h2>
        </div>
        <div class="body">
          <form method="get" action="{{ route('vendor.dashboard') }}" id="graphForm">
            <div class="row" style="margin-left: 2rem; margin-right: 2rem;">
              <div class="col-md-4">
                <input name="start_date" id="datepicker1" placeholder="Start Date" type="text" class="form-control" autocomplete="off">
              </div>
              <div class="col-md-4">
                <input name="end_date" id="datepicker2" placeholder="End Date" type="text" class="form-control" autocomplete="off">
              </div>
              <div class="col-md-4">
                <button class="btn btn-outline-primary" data-toggle="tooltip" data-placement="top" title="Filter"><i class="fa fa-filter" aria-hidden="true"></i></button>
                <a href="{{ route('vendor.dashboard') }}" class="btn btn-outline-warning" data-toggle="tooltip" data-placement="top" title="Reset"><i class="fa fa-undo" aria-hidden="true"></i></a>
              </div>
            </div>
          </form>
          <div id="graph-div">
            @include('includes.vendor-graph')
          </div>
        </div>
      </div>
    </div>
  </div>
  @endif
  </section>
  <!-- /.content -->
@stop


@push('script')
 {{-- firebase web notification setup start --}}
    {{-- Firebase  --}}
    <script src="https://www.gstatic.com/firebasejs/7.23.0/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/7.23.0/firebase-messaging.js"></script>
    <script>  
      var firebaseConfig = {
        apiKey: "AIzaSyCwxBqRLQX3kQ0vHiNtWmL2VrkgaP3Mc2I",
        authDomain: "all-star-delivery-c143f.firebaseapp.com",
        projectId: "all-star-delivery-c143f",
        storageBucket: "all-star-delivery-c143f.appspot.com",
        messagingSenderId: "706377950722",
        appId: "1:706377950722:web:51fbe7c1eed9a5aa22a91b",
        measurementId: "G-EVH5CKXFGW"
      };  
      firebase.initializeApp(firebaseConfig);
      const messaging = firebase.messaging();
      function initFirebaseMessagingRegistration() {
        messaging
        .requestPermission()
        .then(function () {
            return messaging.getToken()
            console.log(messaging.getToken());
        })
        .then(function(token) {
            // console.log(token);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                url: '{{ route("save-token") }}',
                type: 'POST',
                data: {
                    token: token
                },
                dataType: 'JSON',
                success: function (response) {
                    //alert('Token saved successfully.');
                },
                error: function (err) {
                    console.log('User Chat Token Error'+ err);
                },
            });
        }).catch(function (err) {
            console.log('User Chat Token Error'+ err);
        });
      }  

      /**
       *Save token in DB on page load
      */
      setTimeout(function(){ initFirebaseMessagingRegistration(); }, 1000);

      messaging.onMessage(function(payload) {
        // console.log('Message received. ', payload);
        const noteTitle = payload.notification.title;
        const noteOptions = {
            body: payload.notification.body,
            icon: payload.notification.icon,
            url: payload.notification.url,
        };
        var obj = new Notification(noteTitle, noteOptions);
        alert(noteTitle);
      });


      
    </script>
    <script>
      if ('serviceWorker' in navigator) {
          window.addEventListener('load', () => {
          var test = navigator.serviceWorker.register('{{ asset("/firebase-messaging-sw.js") }}');
        });
      }
    </script>
  {{-- firebase web notification setup end --}}
@endpush

@push('style')
  <link rel="manifest" href="{{ asset('/manifest.json') }}"></link>
@endpush