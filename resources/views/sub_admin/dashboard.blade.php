@extends('layouts.sub_admin_master')
@section('title')
All Star Delivery - Dashboard
@stop
@section('content')
  <section class="content-header">
    <h1>
      Dashboard
      <small>Control panel</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="{{url('/sub-admin')}}"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Dashboard</li>
    </ol>
  </section>
  <!-- Main content -->
  <section class="content">
    <!-- Small boxes (Stat box) -->
    <div class="row">
      <div class="col-lg-3 col-xs-6">
        <a href="{{ route('subAdmin.promocode.index') }}">
        <!-- small box -->
        <div class="small-box bg-aqua">
          <div class="inner">
            <h3>{{!empty($ongoingOffer) ? $ongoingOffer : 0}} </h3>
            <p>Ongoing offer</p>
          </div>
          <div class="icon">
            <i class="ion ion-bag"></i>
          </div>
          <?php $date =  date("m/d/Y"); ?>
          <span class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></span>
        </div>
        </a>
      </div>
      <!-- ./col -->
      <div class="col-lg-3 col-xs-6">
        <a href="{{ route('subAdmin.orders.index') }}">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <h3>{{!empty($total_order) ? $total_order : 0}}</h3>
              <p>Total Orders </p>
            </div>
            <div class="icon">
              <i class="fa fa-first-order"></i>
            </div>
            <span class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></span>
          </div>
        </a>
      </div>
      <!-- ./col -->
      <div class="col-lg-3 col-xs-6">
        <a href="{{ route('subAdmin.users.index') }}">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3>{{!empty($total_user) ? $total_user : 0}}</h3>
              <p>Registered Users </p>
            </div>
            <div class="icon">
              <i class="fa fa-user"></i>
            </div>
            <span class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></span>
          </div>
        </a>
      </div>
      <!-- ./col -->
      <div class="col-lg-3 col-xs-6">
        <a href="{{ route('subAdmin.store.index') }}">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
            <h3>{{!empty($total_store) ? $total_store : 0}}</h3>
              <p>Registered Stores</p>
            </div>
            <div class="icon">
              <i class="fa fa-shopping-cart" aria-hidden="true"></i>
            </div>
            <span href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></span>
          </div>
        </a>
      </div>
      <!-- ./col -->
    </div>
    <!-- /.row -->
    <!-- Main row -->
    <div class="row">  
      <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <a href="#">
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3>Ks {{!empty($total_earning) ? $total_earning : 0}}</h3>
              <p>Total Earning </p>
            </div>
            <div class="icon">
              <i class="fa fa-money" aria-hidden="true"></i>
            </div>
            <span href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></span>
          </div>
        </a>
      </div>
    </div>
    <!-- /.row -->
    <!-- /.row (main row) -->
    {{-- Graph Start --}}
    <div class="row">
      <div class="col-sm-12 col-md-12 col-lg-12">
        <div class="box visitors-map">
          <div class="box-header">
            <h2><strong>{{__('Orders')}}</strong> {{__('activity')}}</h2>
          </div>
          <div class="body">
            <form method="get" action="{{ route('subAdmin.dashboard') }}" id="graphForm">
              <div class="row" style="margin-left: 2rem; margin-right: 2rem;">
                <div class="col-md-4">
                  <input name="start_date" id="datepicker1" placeholder="Start Date" type="text" class="form-control" autocomplete="off">
                </div>
                <div class="col-md-4">
                  <input name="end_date" id="datepicker2" placeholder="End Date" type="text" class="form-control" autocomplete="off">
                </div>
                <div class="col-md-4">
                  <button class="btn btn-outline-primary" data-toggle="tooltip" data-placement="top" title="Filter"><i class="fa fa-filter" aria-hidden="true"></i></button>
                  <a href="{{ route('subAdmin.dashboard') }}" class="btn btn-outline-warning" data-toggle="tooltip" data-placement="top" title="Reset"><i class="fa fa-undo" aria-hidden="true"></i></a>
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
  </section>
  <!-- /.content -->
@stop 

@push('script')
  <script>
    /**
    * Date picker for graph
    */
    $(function () {
      $('#datepicker1').datepicker({
        autoclose: true
      })
    });
    $(function () {
      $('#datepicker2').datepicker({
        autoclose: true
      })
    });
  </script>
  {{-- firebase web notification setup start --}}
    {{-- Firebase  --}}
    <script src="https://www.gstatic.com/firebasejs/7.23.0/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/7.23.0/firebase-messaging.js"></script>
    <script>  
      var firebaseConfig = {
          apiKey: "AIzaSyC2K8cemvjJAnLdS9E5vb9DeODsseUmpOQ",
          authDomain: "testfcm-84b33.firebaseapp.com",
          // databaseURL: "https://XXXX.firebaseio.com",
          projectId: "testfcm-84b33",
          storageBucket: "testfcm-84b33.appspot.com",
          messagingSenderId: "706377950722",
          appId: "1:706377950722:android:a0b2340e14c6c45222a91b"        
          // measurementId: "XXX"
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
                    // alert('Token saved successfully.');
                },
                error: function (err) {
                    console.log('User Chat Token Error'+ err);
                },
            });
        }).catch(function (err) {
            console.log('User Chat Token Error'+ err);
        });
      }  
      // self executing function here
      window.onload = function(e){
        initFirebaseMessagingRegistration();
      };
      messaging.onMessage(function(payload) {
        const noteTitle = payload.notification.title;
        const noteOptions = {
            body: payload.notification.body,
            icon: payload.notification.icon,
        };
        new Notification(noteTitle, noteOptions);
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