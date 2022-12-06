@extends('layouts.vendormaster')
@section('title')
{{env('APP_NAME')}} Vendor Profile
@stop
@section('content') 
<style>
   .alert-danger1{color:#dd4b39;}
   .alert{padding:0px !important;}
</style>
<section class="content">
   <div class="row">
      <div class="col-md-12 dashboard-head">
         <h2>{{ __('vendor.vendor_profile') }}</h2>
         <ul class="breadcrumb">
            <li><a href="{{URL::to('/vendor')}}">{{ __('vendor.home') }}</a></li>
            <li><span>Verify Mobile</span></li>
         </ul>
      </div>
   </div>
<div class="row">
   <div class="col-md-12">
     <!-- Custom Tabs -->
     <div class="nav-tabs-custom">
       <ul class="nav nav-tabs">
         <li class="active"><a href="#tab_1" data-toggle="tab">Verify Mobile</a></li>
       </ul>
       <div class="tab-content">
         <div class="tab-pane active" id="tab_1">
            
            @if(Session::has('msg')) {!! session('msg') !!} @endif
            <!-- left column -->
            <div class="row">
               <div class="col-md-3">
                  {!! Form::open(array('url' => '/vendor/otp/verify', 'method' => 'post','name'=>'otp_verify','files'=>true,'novalidate' => 'novalidate')) !!}
                  <div class="box-body">
                     <div class="form-group">
                        <label for="exampleInputEmail1">OTP</label>
                        {!! Form::text('otp',$otp, ['class' => 'form-control','placeholder' => 'Enter otp','required'=>'required']) !!}
                        @if ($errors->has('otp'))
                           <p class=" alert-danger">{{ $errors->first('otp') }}</p>
                        @endif
                     </div>
                     <!-- /.box-body -->
                     <div class="box-footer">
                        {!! Form::submit(trans('vendor.verify_btn'),['class' => 'btn btn-primary']) !!}
                        <a href="{{URL::to('/vendor')}}" class="btn btn-warning">{{ __('vendor.cancel_btn') }}</a>
                     </div>
                     {!! Form::close() !!}
                  </div>
               </div>
            </div>
         </div>
         
         <!-- /.tab-pane -->
       </div>
       <!-- /.tab-content -->
     </div>
     <!-- nav-tabs-custom -->
   </div>
   <!-- /.col -->
</div>
</section>
@stop