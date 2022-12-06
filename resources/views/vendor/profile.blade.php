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
            <li><span>{{ __('vendor.profile_management') }}</span></li>
         </ul>
      </div>
   </div>
<div class="row">
   <div class="col-md-12">
     <!-- Custom Tabs -->
     <div class="nav-tabs-custom">
       <ul class="nav nav-tabs">
         <li class="active"><a href="#tab_1" data-toggle="tab">{{ __('vendor.profile') }}</a></li>
         <li><a href="#tab_2" data-toggle="tab">{{ __('vendor.change_password') }}</a></li>
       </ul>
       <div class="tab-content">
         <div class="tab-pane active" id="tab_1">
            
            @if(Session::has('msg')) {!! session('msg') !!} @endif
            <!-- left column -->
            <div class="row">
               <div class="col-md-12">
                  <!-- general form elements -->
                
                  
                     <!-- /.box-header -->
                     <!-- form start -->
                    
                     {!! Form::open(array('url' => '/vendor/edit-profile-post', 'method' => 'post','name'=>'editProfile','files'=>true,'novalidate' => 'novalidate')) !!}
                     <div class="box-body">
                        <div class="form-group">
                           <label for="exampleInputEmail1">{{ __('vendor.first_name') }}</label>
                           {!! Form::text('first_name',$admindata->first_name, ['class' => 'form-control','placeholder' => trans('vendor.first_name'),'required'=>'required', 'readonly' => 'true']) !!}
                           @if ($errors->has('first_name'))
                           <p class=" alert-danger">{{ $errors->first('first_name') }}</p>
                           @endif
                        </div>
                        <div class="form-group">
                           <label for="exampleInputEmail1">{{ __('vendor.last_name') }}</label>
                           {!! Form::text('last_name',$admindata->last_name, ['class' => 'form-control','placeholder' => trans('vendor.last_name'),'required'=>'required', 'readonly' => 'true']) !!}
                           @if ($errors->has('last_name'))
                           <p class=" alert-danger">{{ $errors->first('last_name') }}</p>
                           @endif
                        </div>
                        <div class="form-group">
                           <label for="exampleInputEmail1">{{ __('vendor.email') }}</label>
                           {!! Form::text('email',$admindata->email, ['class' => ' form-control','placeholder' => trans('vendor.email'),'required'=>'required', 'readonly' => 'true']) !!}
                           @if ($errors->has('email'))
                           <p class=" alert-danger">{{ $errors->first('email') }}</p>
                           @endif
                        </div>
                        <div class="form-group">
                           <label for="mobile">{{ __('vendor.mobile') }}</label>
                           {!! Form::text('text',$admindata->mobile, ['class' => 'form-control','name'=>'mobile','placeholder' => trans('vendor.mobile'),'required'=>'required']) !!}
                           @if ($errors->has('mobile'))
                           <p class=" alert-danger">{{ $errors->first('mobile') }}</p>
                           @endif
                        </div>
                       
                        <div class="row form-group">
                           <label class="col-md-1 col-sm-6 col-xs-12 control-label">{{ __('vendor.image') }}</label>     
                           <div class="col-md-8">
                              <span class="import-excel">
                              <input type="file" name="profile_pic" id="profile_pic" class="form-control input-file" onkeypress="error_remove()" accept="image/*">
                              <button class="btn btn-outline-success">{{ __('vendor.browse_btn') }}</button>
                              </span>
                           </div>
                           @if ($errors->has('profile_pic'))
                           <p class="alert alert-danger" style="margin-top:66px;">{{ $errors->first('profile_pic') }}</p>
                           @endif 
                        </div>
                        @if($admindata->profile_pic!="")
                        <div class=" form-group">
                           <label class=" control-label"></label>
                           <img src="{{asset('/media/users').'/'.$admindata->profile_pic}}" width="70px" height="70px">
                        </div>
                        @else
                        <div class="form-group">
                           <label class="control-label"></label>
                           <img src="{{asset('/media/no-image.png')}}" width="70px" height="70px">
                        </div>
                        @endif
                        <!-- /.box-body -->
                        <div class="box-footer">
                           {!! Form::submit(trans('vendor.update_btn'),['class' => 'btn btn-primary']) !!}
                           <a href="{{URL::to('/vendor')}}" class="btn btn-warning">{{ __('vendor.cancel_btn') }}</a>
                        </div>
                        {!! Form::close() !!}
                     </div>
                
               </div>
            </div>
         </div>
         <!-- /.tab-pane -->
         <div class="tab-pane" id="tab_2">
            <div class="row">
               <div class="col-md-6">
                  <form action="#" method="put" id="passwordForm">
                     @csrf
                     @method('put')
                     <div class="form-group">
                        <label for="current_password">{{ __('vendor.current_password') }}</label>
                        <input type="password" name="current_password" id="current_password" class="form-control" placeholder="{{ __('vendor.current_password') }}" required>
                        @if ($errors->has('current_password'))
                        <p class=" alert-danger">{{ $errors->first('current_password') }}</p>
                        @endif
                     </div>
                     <div class="form-group">
                        <label for="new_password">{{ __('vendor.new_password') }}</label>
                        <input type="password" name="new_password" id="new_password" class="form-control" placeholder="{{ __('vendor.new_password') }}" required>
                        @if ($errors->has('new_password'))
                        <p class=" alert-danger">{{ $errors->first('new_password') }}</p>
                        @endif
                     </div>
                     <div class="form-group">
                        <label for="password_confirmation">{{ __('vendor.confirm_password') }}</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="{{ __('vendor.confirm_password') }}" required>
                        @if ($errors->has('password_confirmation'))
                        <p class=" alert-danger">{{ $errors->first('password_confirmation') }}</p>
                        @endif
                     </div>
                     <div class="form-group">
                        <button id="passwordFormBtn" class="btn btn-primary" type="submit">{{ __('vendor.update_btn') }}</button>
                     </div>
                  </form>
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

@push('script')
    <script>
        
        // ===========submit password form===================
        $(document).on('click','#passwordFormBtn', function(event){
        event.preventDefault();
        $.ajax({
            type: "GET",
            enctype: 'multipart/form-data',
            url: '{{ URL::to('vendor/update-password') }}',
            data: $('#passwordForm').serialize(),
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) { 
               toastr[data.toster_class](data.msg);
            },
            error: function (e) {
               toastr[e.responseJSON.toster_class](e.responseJSON.msg);
               console.log(e);
            }
            });
        });

        //========== Search form submit start ============
      //   $(document).on('click','#passwordFormBtn', function(event){
      //    updatePasswordSubmit();
      //   });

        //--------reset search form---------
        function resetForm() {
            document.getElementById('searchForm').reset();
            search();
        }
    </script>
@endpush