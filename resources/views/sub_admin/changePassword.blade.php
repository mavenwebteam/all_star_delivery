@extends('layouts.sub_admin_master')
@section('title')
{{env('APP_NAME')}} | Setting 
@stop
@section('content') 
<style>
   .alert-danger1{color:#dd4b39;}
   .alert{padding:0px !important;}
</style>
<section class="content">
   <div class="row">
      <div class="col-md-12 dashboard-head">
         <h2>Setting</h2>
         <ul class="breadcrumb">
            <li><a href="{{URL::to('/vendor')}}">{{ __('vendor.home') }}</a></li>
            <li><span>Change Password</span></li>
         </ul>
      </div>
   </div>
<div class="row">
   <div class="col-md-12">
     <!-- Custom Tabs -->
     <div class="nav-tabs-custom">
       
       <div class="tab-content">
         <!-- /.tab-pane -->
         <div>
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
        var formData = $('#passwordForm').serialize();
        $.ajax({
            type: "GET",
            enctype: 'multipart/form-data',
            url: '{{ URL::to('sub-admin/setting/change-password-post') }}',
            data: formData,
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