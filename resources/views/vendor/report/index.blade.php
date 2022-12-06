@extends('layouts.vendormaster')
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
      <li><a href="{{url('/vendor')}}"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Report</li>
    </ol>
  </section>
  <!-- Main content -->
  <section class="content">
    {{-- Graph Start --}}
    <div class="row">
      <div class="col-sm-12 col-md-12 col-lg-12">
        <div class="box visitors-map">
          <div class="box-header">
            <h2><strong>Earning</strong> report</h2>
            <a href="{{ route('vendor.report.earning') }}" class="btn btn-info">View More</a>
          </div>
          <div class="body">
            @include('vendor.report.earning_report_chart')
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