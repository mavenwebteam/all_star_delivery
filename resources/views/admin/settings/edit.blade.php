@extends('layouts.adminmaster')
@section('title')
{{ env('app.APP_NAME') }} | Admin Setting
@stop
@section('content')
<style>
  .alert-danger1 {
    color: #dd4b39;
  }

  .alert {
    padding: 0px !important;
  }
</style>

<section class="content-header"><h1>Admin Setting</h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Admin Setting</li>
  </ol>
</section>
<section class="content">
  <!-- left column -->
  <div class="col-md-12">
    <!-- general form elements -->
    <div class="box">
      <!-- /.box-header -->
      <!-- form start -->
      {!! Form::open(array('url' => route('admin.setting.update', $setting->id), 'method' =>
      'post','name'=>'editProfile','files'=>true,'novalidate' => 'novalidate')) !!}
      @method('put')
      <div class="box-body">
        <div class="row">
          <div class="col-12 col-sm-12 col-md-6">
            <div class="form-group">
              <label for="exampleInputEmail1">Tax(%)<span class="text-red">*</span></label>
              <input type="text" name="tax" value="{{ $setting->tax }}" id="tax" class="form-control" placeholder="tax">
              <small class="text-muted">applicable only on delivery charges</small>
              @if ($errors->has('tax'))
              <p class="alert alert-danger1">{{ $errors->first('tax') }}</p>
              @endif
            </div>
          </div>
          <div class="col-12 col-sm-12 col-md-6">
            <div class="form-group">
              <label for="exampleInputEmail1">Minimum order amount for delivery<span class="text-red">*</span></label>
              <input type="text" name="min_order_amount_for_delivery" value="{{ $setting->min_order_amount_for_delivery }}" id="min_order_amount_for_delivery" class="form-control" placeholder="min order amount for delivery">
              @if ($errors->has('min_order_amount_for_delivery'))
              <p class="alert alert-danger1">{{ $errors->first('min_order_amount_for_delivery') }}</p>
              @endif
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-12 col-sm-12 col-md-6">
            <div class="form-group">
              <label for="exampleInputEmail1">Driver range for cycle one(in KM)<span class="text-red">*</span></label>
              <input type="text" name="driver_range_1" value="{{ $setting->driver_range_1 }}" id="driver_range_1" class="form-control" placeholder="Driver range in KM">
              @if ($errors->has('driver_range_1'))
              <p class="alert alert-danger1">{{ $errors->first('driver_range_1') }}</p>
              @endif
            </div>
          </div>
          <div class="col-12 col-sm-12 col-md-6">
            <div class="form-group">
              <label for="driver_range_2">Driver range for cycle two(in KM)<span class="text-red">*</span></label>
              <input type="text" name="driver_range_2" value="{{ $setting->driver_range_2 }}" id="driver_range_2" class="form-control" placeholder="Driver range in KM">
              @if ($errors->has('driver_range_2'))
              <p class="alert alert-danger1">{{ $errors->first('driver_range_2') }}</p>
              @endif
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-12 col-sm-12 col-md-6">
            <div class="form-group">
              <label for="driver_range_3">Driver range for cycle three(in KM)<span class="text-red">*</span></label>
              <input type="text" name="driver_range_3" value="{{ $setting->driver_range_3 }}" id="driver_range_3" class="form-control" placeholder="Driver range in KM">
              @if ($errors->has('driver_range_3'))
              <p class="alert alert-danger1">{{ $errors->first('driver_range_3') }}</p>
              @endif
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-12 col-sm-12 col-md-6">
            <div class="form-group">
              <label for="video_customer">Youtube video link for customer</label>
              <textarea name="video_customer" class="form-control" id="video_customer" cols="30" rows="3">{{ $setting->video_customer }}</textarea>
              @if ($errors->has('video_customer'))
              <p class="alert alert-danger1">{{ $errors->first('video_customer') }}</p>
              @endif
            </div>
          </div>
          <div class="col-12 col-sm-12 col-md-6">
            @if(!empty($setting->video_customer))
              <iframe width="360" height="115" src="{{ $setting->video_customer }}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            @endif
          </div>
        </div>
        <div class="row">
          <div class="col-12 col-sm-12 col-md-6">
            <div class="form-group">
              <label for="video_vendor">Youtube video link for Vendor</label>
              <textarea name="video_vendor" class="form-control" id="video_vendor" cols="30" rows="3">{{ $setting->video_vendor }}</textarea>
              @if ($errors->has('video_vendor'))
              <p class="alert alert-danger1">{{ $errors->first('video_vendor') }}</p>
              @endif
            </div>
          </div>
          <div class="col-12 col-sm-12 col-md-6">
            @if(!empty($setting->video_vendor))
              <iframe width="360" height="115" src="{{ $setting->video_vendor }}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            @endif
          </div>
        </div>          
        <div class="row">
          <div class="col-12 col-sm-12 col-md-6">
            <div class="form-group">
              <label for="video_driver">Youtube video link for driver</label>
              <textarea name="video_driver" class="form-control" id="video_driver" cols="30" rows="3">{{ $setting->video_driver }}</textarea>
              @if ($errors->has('video_driver'))
              <p class="alert alert-danger1">{{ $errors->first('video_driver') }}</p>
              @endif
            </div>
          </div>
          <div class="col-12 col-sm-12 col-md-6">
            @if(!empty($setting->video_driver))
            <iframe width="360" height="115" src="{{ $setting->video_driver }}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            @endif
            </div>
        </div>
      </div>
      <div class="box-footer">
        {!! Form::submit('Update',['class' => 'btn btn-primary']) !!}
        <a href="{{URL::to('/admin')}}" class="btn btn-warning">Cancel</a>
      </div>
      {!! Form::close() !!}
    </div>
  </div>
  <!-- /.row -->
</section>
@stop