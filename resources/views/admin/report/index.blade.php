@extends('layouts.adminmaster')
@section('title')
All Star Delivery - Dashboard
@stop
@section('content')
  <section class="content-header">
    <h1>
      Report
      <small>Control panel</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="{{url('/admin')}}"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Report</li>
    </ol>
  </section>
  <!-- Main content -->
  <section class="content">
      <div class="row">
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-aqua"><i class="fa fa-user-o" aria-hidden="true"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Active Customer</span>
              <span class="info-box-number">{{ $activeCustomer }}</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-green"><i class="fa fa-home" aria-hidden="true"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Active Store</span>
              <span class="info-box-number">{{ $activeStore }}</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-yellow"><i class="fa fa-user-circle" aria-hidden="true"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Active Vendor</span>
              <span class="info-box-number">{{ $activeVendor }}</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-red"><i class="fa fa-shopping-bag" aria-hidden="true"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Total Order</span>
              <span class="info-box-number">{{ $totalOrder }}</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    {{-- Graph Start --}}
    <div class="row">
      <div class="col-sm-12 col-md-8 col-lg-8">
        <div class="box visitors-map">
          <div class="box-header">
            <h2><strong>Earning</strong> report</h2>
          </div>
          <div class="body">
            @include('admin.report.earning_report_chart')
          </div>
        </div>
      </div>
      <div class="col-sm-12 col-md-4 col-lg-4">
        <div class="box visitors-map">
          <div class="box-header">
            <h2><strong>{{__('Orders')}}</strong> {{__('activity')}}</h2>
          </div>
          <div class="body">
            <div id="graph-div">
              @include('admin.report.order_report_chart')
            </div>
          </div>
        </div>
      </div>
      <div class="col-sm-12 col-md-8 col-lg-8">
        <div class="box visitors-map">
          <div class="box-header">
            <h2><strong>Customer</strong> report</h2>
          </div>
          <div class="body">
            @include('admin.report.customer_report_chart')
          </div>
        </div>
      </div>

      <div class="col-sm-12 col-md-4 col-lg-4">
        <div class="box visitors-map">
          <div class="box-header">
            <h2><strong>View in</strong> detail</h2>
            <small>View and download report in excel file</small>
          </div>
          <div class="box-body">
            <div class="row">
              <div class="col-md-9">
                <h3 style="display: initial;">Earning report</h3>
              </div>
              <div class="col-md-3">
                <a href="{{ route('admin.report.earning') }}" target="_blank" class="btn btn-sm btn-warning">Earning <i class="fa fa-paper-plane-o" aria-hidden="true"></i></a>
              </div>
            </div>
            <div class="row">
              <div class="col-md-9">
                <h3 style="display: initial;">Orders report</h3>
              </div>
              <div class="col-md-3">
                <a href="{{ route('admin.report.order') }}" target="_blank" class="btn btn-sm btn-warning">Order <i class="fa fa-paper-plane-o" aria-hidden="true"></i></a>
              </div>
            </div>
            <div class="row">
              <div class="col-md-9">
                <h3 style="display: initial;">Customers report</h3>
              </div>
              <div class="col-md-3">
                <a href="{{ route('admin.report.customer') }}" target="_blank" class="btn btn-sm btn-warning">Customers <i class="fa fa-paper-plane-o" aria-hidden="true"></i></a>
              </div>
            </div>
            <div class="row">
              <div class="col-md-9">
                <h3 style="display: initial;">Stores report</h3>
              </div>
              <div class="col-md-3">
                <a href="{{ route('admin.report.store') }}" target="_blank" class="btn btn-sm btn-warning">Stores <i class="fa fa-paper-plane-o" aria-hidden="true"></i></a>
              </div>
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
@endpush

@push('style')
  <link rel="manifest" href="{{ asset('/manifest.json') }}"></link>
@endpush