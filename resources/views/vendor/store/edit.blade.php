@extends('layouts.vendormaster')
@section('title')
{{ env('APP_NAME') }} | Store Profile
@stop
@section('content') 
<style>
   .border-first {
   display: inline-block;
   width: 100%;
   }
   .error{
      color:#E93F3F;
   }
   .mapicon {
    position: absolute;
    top: 5px;
    right: 20px;
    width: 24px;
   }

   .mapicon img {
      max-width: 24px;
   }

   .with_icon_map input {
      width: calc(100% - 36px);
   }
</style>

<style>
   .pac-container {
   background-color: #fff;
   position: absolute!important;
   z-index: 10000;
   border-radius: 2px;
   border-top: 1px solid #d9d9d9;
   font-family: Arial,sans-serif;
   box-shadow: 0 2px 6px rgba(0,0,0,0.3);
   -moz-box-sizing: border-box;
   -webkit-box-sizing: border-box;
   box-sizing: border-box;
   overflow: hidden;
   }
</style>

<section class="content-header">
   <h1>{{ __('vendor.menu_store_profile') }}</h1>
   <ol class="breadcrumb">
      <li><a href="{{url('/vendor')}}"><i class="fa fa-dashboard"></i> {{ __('vendor.home') }}</a></li>
      <li class="active">{{ __('vendor.menu_store_profile') }}</li>
   </ol>
</section>
<div id="msg-data" > </div>
<section class="content">
   <div class="row">
      <div class="col-xs-12">
         <div class="box">
            <div class="box-header">
               <h3 class="box-title"></h3>
            </div>
            <div class="box-body" id="replace-div">
               @if(Session::has('message'))
                  <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
               @endif
               {!! Form::open(array('route' => 'vendor.store.post', 'method' => 'post','name'=>'editStoreForm','files'=>true,'novalidate' => 'novalidate','id' => 'editStoreForm')) !!}
         
               <div class="col-sm-12 p-r-30">
                  <div class="panel form-horizontal panel-transparent">
                     <div class="panel-body">
                        <div class="row">
                           <div class="border-first" style=" border-style: solid;  border-width: 1px; min-height: 208px;
                              margin-bottom: 42px; padding:10px;">
                              <br>
                              <div class="col-12 col-sm-12 col-md-6">
                                 <div class="row form-group">
                                    <label class="col-lg-4 col-sm-6 col-xs-12 control-label">{{ __('vendor.store_name') }} (en)<span class="red-star">*</span></label>
                                    <div class="col-lg-8 col-sm-6 col-xs-12">
                                       {!! Form::text('name',object_get($storedata,'name', null), ['class' => 'form-control','placeholder' => trans('vendor.store_name'),'required'=>'required','id'=>'store_name','onkeypress' => 'error_remove()' ]) !!}
                                       @error('name')
                                          <p class="error">{{ $message }}</p>
                                       @enderror
                                    </div>
                                    
                                 </div>
                              </div>
                              
                              <div class="col-12 col-sm-12 col-md-6">
                                 <div class="row form-group">
                                    <label class="col-lg-4 col-sm-6 col-xs-12 control-label">{{ __('vendor.store_name') }} (Burmese)<span class="red-star">*</span></label>
                                    <div class="col-lg-8 col-sm-6 col-xs-12">
                                       {!! Form::text('name_burmese',object_get($storedata,'name_burmese', null), ['class' => 'form-control','placeholder' => 'Store Name (Burmese)','required'=>'required','id'=>'store_name_burmese','onkeypress' => 'error_remove()' ]) !!}
                                       @error('name_burmese')
                                          <p class="error">{{ $message }}</p>
                                       @enderror
                                    </div>
                                    
                                 </div>
                              </div>
                              <div class="col-12 col-sm-12 col-md-6">
                                 <div class="row form-group">
                                    <label class="col-lg-4 col-sm-6 col-xs-12 control-label">{{ __('vendor.store_logo') }}<span class="red-star">*</span></label>
                                    <div class="col-lg-8 col-sm-6 col-xs-12">
                                       {!! Form::file('logo', ['class' => 'form-control','id'=>'logo','accept'=>'image/*' ]) !!}
                                       <small class="form-text text-muted">Please Upload jpg, jpeg, png formats only. Dimensions : 210 x 210</small>
                                       @error('logo')
                                          <p class="error">{{ $message }}</p>
                                       @enderror
                                       @if(!empty(object_get($storedata, 'store_logo', NULL)))
                                       <img src="{{asset('media/store/thumb/'.$storedata->store_logo)}}" style="width:50px; height:50px" alt="">
                                       @endif
                                    </div>
                                 </div>
                              </div>
                              <div class="col-12 col-sm-12 col-md-6">
                                 <div class="row form-group">
                                    <label class="col-lg-4 col-sm-6 col-xs-12 control-label">{{ __('vendor.image') }}<span class="red-star">*</span></label>
                                    <div class="col-lg-8 col-sm-6 col-xs-12">
                                       {!! Form::file('image', ['class' => 'form-control','id'=>'image','accept'=>'image/*' ]) !!}
                                       <small class="form-text text-muted">Please Upload jpg, jpeg, png formats only. Dimensions : 210 x 210</small>
                                       @error('image')
                                          <p class="error">{{ $message }}</p>
                                       @enderror
                                       @if(!empty(object_get($storedata,'image', NULL)))
                                       <img src="{{asset('media/store/thumb/'.$storedata->image)}}" style="width:50px; height:50px" alt="">
                                       @endif
                                    </div>

                                 </div>
                              </div>
                              <div class="col-12 col-sm-12 col-md-6">
                                 <div class="row form-group">
                                    <label class="col-lg-4 col-sm-6 col-xs-12 control-label">{{ __('vendor.email') }}<span class="red-star">*</span></label>
                                    <div class="col-lg-8 col-sm-6 col-xs-12">
                                       {!! Form::text('email',object_get($vendor,'email',NULL), ['class' => 'form-control','placeholder' => trans('vendor.email'),'required'=>'required','id'=>'email','onkeypress' => 'error_remove()', 'readonly'=>'true' ]) !!}
                                       @error('email')
                                          <p class="error">{{ $message }}</p>
                                       @enderror
                                    </div>
                                 </div>
                              </div>
                              <div class="col-12 col-sm-12 col-md-6">
                                 <div class="row form-group">
                                    <label class="col-lg-4 col-sm-6 col-xs-12 control-label">{{ __('vendor.mobile') }}<span class="red-star">*</span></label>
                                    <div class="col-lg-8 col-sm-6 col-xs-12">
                                       <div class="row">
                                          <div class="col-lg-6 col-sm-6 col-xs-12">
                                                <select class="form-control" name="country_code" required='required' id='country_code' onkeypress=error_remove() >
                                                   <option value="">-choose-</option>
                                                   @foreach($countryList as $contry)
                                                         <option value="{{ $contry->phonecode }}" @if($contry->phonecode== object_get($storedata,'country_code', '+95')) {{ 'selected' }} @endif>{{ $contry->phonecode." ".$contry->name }}</option>
                                                   @endforeach
                                                </select>
                                                @error('country_code')
                                                   <p class="error">{{ $message }}</p>
                                                @enderror
                                          </div>
                                          <div class="col-lg-6 col-sm-6 col-xs-12">
                                             {!! Form::text('mobile', object_get($storedata,'mobile', NULL), ['class' => 'form-control','placeholder' => 'Mobile ','required'=>'required','id'=>'mobile','onkeypress' => 'error_remove()']) !!} 
                                             @error('mobile')
                                                <p class="error">{{ $message }}</p>
                                             @enderror  
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-12 col-sm-12 col-md-6">
                                 <div class="row form-group with_icon_map">
                                    <label class="col-lg-4 col-sm-6 col-xs-12 control-label">{{ __('vendor.business_address') }}<span class="red-star">*</span></label>
                                    <div class="col-lg-8 col-sm-6 col-xs-12">
                                       {!! Form::text('address', object_get($storedata,'address', null), ['class' => 'form-control address','placeholder' => 'Address ','required'=>'required','id'=>'address','onkeypress' => 'error_remove()' ]) !!}
                                       @error('address')
                                       <p class="error">{{ $message }}</p>
                                       @enderror
                                       <a href="https://www.google.com/maps" target="_blank" class="mapicon"><img src="{{ asset("img/location.png") }}" alt=""></a>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-12 col-sm-12 col-md-6">
                                 <div class="row form-group">
                                    <label class="col-lg-4 col-sm-6 col-xs-12 control-label">{{ __('vendor.city') }}<span class="red-star">*</span></label>
                                    <div class="col-lg-8 col-sm-6 col-xs-12">
                                       {!! Form::text('city',object_get($storedata,'city',null), ['class' => 'form-control city','placeholder' => 'City autofilled ','required'=>'required','id'=>'city','onkeypress' => 'error_remove()' ]) !!}
                                       @error('city')
                                          <p class="error">{{ $message }}</p>
                                       @enderror
                                    </div>
                                 </div>
                              </div>
                              <div class="col-12 col-sm-12 col-md-6">
                                 <div class="row form-group">
                                    <label class="col-lg-4 col-sm-6 col-xs-12 control-label">{{ __('vendor.latitude') }}<span class="red-star">*</span></label>
                                    <div class="col-lg-8 col-sm-6 col-xs-12">
                                       {!! Form::text('latitude',object_get($storedata,'lat', null), ['class' => 'form-control','placeholder' => trans('vendor.latitude'),'required'=>'required','id'=>'latitude','onkeypress' => 'error_remove()' ]) !!}
                                       <small class="text-muted">pick latitude from google map</small>
                                       @error('latitude')
                                          <p class="error">{{ $message }}</p>
                                       @enderror
                                    </div>
                                    
                                 </div>
                              </div>
                              <div class="col-12 col-sm-12 col-md-6">
                                 <div class="row form-group">
                                    <label class="col-lg-4 col-sm-6 col-xs-12 control-label">{{ __('vendor.longitude') }}<span class="red-star">*</span></label>
                                    <div class="col-lg-8 col-sm-6 col-xs-12">
                                       {!! Form::text('longitude',object_get($storedata,'lng', null), ['class' => 'form-control','placeholder' => trans('vendor.longitude'),'required'=>'required','id'=>'store_name_burmese','onkeypress' => 'error_remove()' ]) !!}
                                       <small class="text-muted">pick longitude from google map</small>
                                       @error('longitude')
                                          <p class="error">{{ $message }}</p>
                                       @enderror
                                    </div>
                                 </div>
                              </div>                              
                              <div class="col-12 col-sm-12 col-md-6">
                                 <div class="row form-group">
                                    <label class="col-lg-4 col-sm-6 col-xs-12 control-label">{{ __('vendor.select_business_category') }}<span class="red-star">*</span></label>
                                    <div class="col-lg-8 col-sm-6 col-xs-12">
                                       <select class="form-control" name="business_category_id" required='required' id='business_category_id' onkeypress=error_remove() >
                                             <option value="">-choose-</option>
                                             @foreach($businessCategory as $category)
                                                <option value="{{ $category->id }}" 
                                                   @if($storedata) 
                                                      @if($storedata->business_category_id==$category->id) {{ 'selected' }} @endif 
                                                   @endif   
                                                   {{(old('business_category_id')==$category->id)? 'selected':''}}
                                                >
                                                   {{ $category->name_en }}
                                                </option>
                                             @endforeach
                                       </select>
                                       @error('business_category_id')
                                          <p class="error">{{ $message }}</p>
                                       @enderror  
                                    </div>
                                 </div>
                              </div>
                              <div class="col-12 col-sm-12 col-md-6">
                                 <div class="row form-group">
                                       <label class="col-lg-4 col-sm-6 col-xs-12 control-label">{{ __('vendor.store_open_time') }}<span class="red-star">*</span></label>
                                       <div class="col-lg-8 col-sm-6 col-xs-12">
                                          {!! Form::text('open_at', object_get($storedata,'open_at', null), ['class' => 'form-control timepicker','placeholder' => '08:30 AM','required'=>'required','id'=>'open_at','readonly'=>'true','onkeypress' => 'error_remove()']) !!}
                                          @error('open_at')
                                             <p class="error">{{ $message }}</p>
                                          @enderror 
                                       </div>
                                 </div>
                              </div>
                              <div class="col-12 col-sm-12 col-md-6">
                                 <div class="row form-group">
                                       <label class="col-lg-4 col-sm-6 col-xs-12 control-label">{{ __('vendor.store_close_time') }}<span class="red-star">*</span></label>
                                       <div class="col-lg-8 col-sm-6 col-xs-12">
                                          {!! Form::text('close_at', object_get($storedata,'close_at', null), ['class' => 'form-control timepicker','placeholder' => '09:30 PM','required'=>'required','id'=>'close_at','readonly'=>'true','onkeypress' => 'error_remove()']) !!}
                                          @error('close_at')
                                             <p class="error">{{ $message }}</p>
                                          @enderror 
                                       </div>
                                 </div>
                              </div>
                              <div class="col-6 col-sm-12 col-md-6">
                                 <div class="row form-group">
                                    <label class="col-lg-4 col-sm-6 col-xs-12 control-label">{{ __('vendor.closeing_day') }}</label>
                                    <div class="col-lg-8 col-sm-6 col-xs-12">
                                       <?php $helper=new App\Helpers;?>
                                       {!! Form::select('closing_day',$helper->SelectClosingDay(), $storedata ? $storedata->closing_day : '', ['class' => 'form-control','id'=>'closing_day','onkeypress' => 'error_remove()']) !!}
                                       @error('closing_day')
                                          <p class="error">{{ $message }}</p>
                                       @enderror
                                    </div>
                                 </div>
                              </div>
                              <div class="col-12 col-sm-12 col-md-6">
                                 <div class="row form-group">
                                    <label class="col-lg-4 col-sm-6 col-xs-12 control-label">{{ __('vendor.about_store') }} (en)<span class="red-star">*</span></label>
                                    <div class="col-lg-8 col-sm-6 col-xs-12">
                                       {!! Form::textarea('description', object_get($storedata,'description', null), ['class' => 'form-control','placeholder' => trans('vendor.about_store'),'required'=>'required','id'=>'description','rows'=>3,'onkeypress' => 'error_remove()' ]) !!}
                                       @error('description')
                                          <p class="error">{{ $message }}</p>
                                       @enderror   
                                    </div>
                                 </div>
                              </div>
                              <div class="col-12 col-sm-12 col-md-6">
                                 <div class="row form-group">
                                    <label class="col-lg-4 col-sm-6 col-xs-12 control-label">{{ __('vendor.about_store') }} (Burmese)<span class="red-star">*</span></label>
                                    <div class="col-lg-8 col-sm-6 col-xs-12">
                                       {!! Form::textarea('description_burmese', object_get($storedata, 'description_burmese', null), ['class' => 'form-control','placeholder' => trans('vendor.about_store'),'required'=>'required','id'=>'description_burmese','rows'=>3,'onkeypress' => 'error_remove()' ]) !!}
                                       @error('description_burmese')
                                          <p class="error">{{ $message }}</p>
                                       @enderror  
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="row form-btn text-center">
                        <div class="col-sm-12 p-r-30">
                           <div class="col-md-12"> 
                              {!! Form::submit(trans('vendor.save_btn'), ['class' => 'btn btn-primary btn-flat subbtn', 'type' => 'submit','id'=>'loading-button']) !!}
                              <a href="{{ route('vendor.dashboard') }}" class="btn btn-secondary">{{ __('vendor.cancel_btn') }}</a>
                           </div>
                        </div>
                        {!! Form::close() !!}
                     </div>
                  <!-- /.box-body -->
                  </div>
               </div>
               <!-- /.col -->
            </div>
         </div>
      </div>
   </div>
   <!-- /.row -->
</section>
<script></script>
<script src="{{ asset('admin_assets/js/bootstrap-datetimepicker.min.js') }}"></script>
<link rel="stylesheet" href="{{ asset('admin_assets/css/bootstrap-datetimepicker.min.css') }}">
<script type="text/javascript">
   $('#open_at').timepicker({
   showMeridian: false     
   });
   $('#close_at').timepicker({
   showMeridian: false     
   });	
</script>
@stop