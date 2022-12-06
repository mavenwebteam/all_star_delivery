@extends('layouts.vendormaster')
@section('title')
{{ env('APP_NAME') }}- Setting Manager
@stop
@section('content') 
  <section class="content">
    <div class="row">
      <div class="col-md-12 dashboard-head">
        <h2>Setting</h2>
        <ul class="breadcrumb">
          <li><a href="{{URL::to('/vendor')}}">Home</a></li>
          <li><span>Setting Management</span></li>
        </ul>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
          @if(Session::has('msg')) {!! session('msg') !!} @endif
      </div>
    </div>
    <!-- left column -->
    <div class="col-md-12">
      <!-- general form elements -->
      <div class="box box-primary">
        <div class="box-body">
          <form action="{{ route('vendor.setting.update') }}" method="post">
            @csrf
            @method('put')
            <div class="form-group row">
              <label for="inputEmail3" class="col-sm-1 col-form-label">Notification</label>
              <div class="col-sm-11">
                <input type="checkbox" @if($is_notification) checked @endif name="is_notification" data-toggle="toggle" value="1">
              </div>
            </div>
            <div class="form-group row">
              <div class="col-sm-10">
                <button type="submit" class="btn btn-primary">Update</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!-- /.row -->
  </section>
@stop 
@push('script')
  <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
  <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
@endpush