@extends('layouts.adminmaster')
@section('title')
All Star Delivery | Add Store Manager
@stop

@section('content') 
<style>
   .border-first {
   display: inline-block;
   width: 100%;
   }
</style>
<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo Config::get("Site.google_api_key");?>&libraries=places&language=en" type="text/javascript"></script> 
<script type="text/javascript">
   $(document).ready(function ()
   {  
      $.LoadingOverlay('hide');
      $( '#addUserForm' ).on( 'submit', function(e) 
      {   
         $.LoadingOverlay('show');
              e.preventDefault();
                 $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });
                //for(var instanceName in CKEDITOR.instances)
           //CKEDITOR.instances[instanceName].updateElement();
              $.ajax({
                  dataType: 'json',
                 type: "POST",
              data:  new FormData(this),
              contentType: false,
              cache: false,
              processData:false,
              url: '{{ URL::to('/admin/add-store-post') }}',
          }).done(function( data ) 
          {  
            error_remove (); 
            if(data.success==false)
            {
               $.each(data.errors, function(key, value){
                  if(key == "category_id")
                  {
                     $('#'+key).parent().closest('.form-group').addClass('has-error');
               
                  $('<div class="jquery-validate-error help-block animated fadeInDown">'+value+'</div>').insertAfter($('#category_id1'));
                  }
                  if(key != "category_id")
                  {
                  $('#'+key).closest('.form-group').addClass('has-error');
               
                  $('<div class="jquery-validate-error help-block animated fadeInDown">'+value+'</div>').insertAfter($('#'+key));
                  }
               });
            }else{
               search();
               if(data.class == 'success')
               { 	
                  $.LoadingOverlay('hide'); showMsg(data.message, "success");
               }
                  window.location = '{{ URL::to('/admin/store') }}';
                  return false;
            }
            $.LoadingOverlay('hide');
       });
      });

      // --------local vendors start-------
   
         $('#vendors').select2({
            minimumInputLength: 0,
            ajax: {
                  url: '{{ route("admin.vendor.list") }}',
                  dataType: 'json',
                  delay: 250,
                  pagination: {
                        more: true
                     },
                  data: function (params) {
                     console.log(params);
                     var query = {
                     search: params.term || '',
                     page: params.page || 1
                     }
                     // Query parameters will be ?search=[term]&page=[page]
                     return query;
                  },
                  processResults: function (data) {
                     console.log(data);
                    return {
                        'results': data.results
                      , 'pagination': {
                            'more': data.pagination.more
                        }
                    };
                }
                
                 
            }
         });
    
     
      // --------local vendors end-------


   });

   function search() {
   
       $.ajax({
           type: 'POST',
           url: '{{ URL::to('/admin/store') }}',
           data: $('#mySearchForm').serialize(),
           beforeSend: function(){
               $.LoadingOverlay("show");
           },
           success: function(msg){
             
              
               $('#replace-div').html(msg);
               $('.loading-top').fadeOut();
               $('html,body').animate({scrollTop:$('.page-user').offset().top-0},1400);
               $.LoadingOverlay("hide");
               return false;
           }
       });
   }
   function isNumberKey(evt){
   var charCode = (evt.which) ? evt.which : event.keyCode
   if (charCode > 31 && (charCode < 48 || charCode > 57))
       return false;
   return true;
   }
   
   $(document).ready(function () { 
   		$( "#countary_id" ).change(function () {
   			var countary_id = $(this).val();
   				$.ajax({
   					url: "{{url('admin/getstate') }}" + '/'+countary_id,
   					success: function(data) {
   						$('#state_id').prop('disabled', false);
   						$('#state_id').html('');
   						$('#state_id').html(data);		
   					}
   				});
   		});
        $( "select[name='state_id']" ).change(function () { //alert();
   			var state_id = $(this).val();
   				$.ajax({
   					url: "{{url('admin/getcity') }}" + '/'+state_id,
   					success: function(data) {
   						$('#city_id').prop('disabled', false);
   						$('#city_id').html('');
   						$('#city_id').html(data);		
   					}
   				});
           });
   	});
</script>
<section class="content-header">
   <h1>Add Store</h1>
   <ol class="breadcrumb">
      <li><a href="{{url('/admin')}}"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="{{url('admin/store')}}">Store</a></li>
      <li class="active">Store add</li>
   </ol>
</section>
<section class="" style="padding: 15px;">
   <div class="row">
   <div class="col-xs-12 page-user">
   <div class="box">
      <div class="box-body">
         {!! Form::open(array('url' => '/admin/add-store-post', 'method' => 'post','name'=>'editStoreForm','files'=>true,'novalidate' => 'novalidate','id' => 'addUserForm')) !!}
         <div class="col-sm-12 p-r-30">
            <div class="panel form-horizontal panel-transparent">
               <div class="panel-body">
                  <div class="row">
                     <div class="border-first" style=" border-style: solid;  border-width: 1px; min-height: 208px;
                        margin-bottom: 42px; padding:10px;">
                        <div class="col-12 col-sm-12 col-md-12">
                           <div class="row form-group">
                              <label class="col-lg-2 col-sm-6 col-xs-12 control-label">About Vendor<span class="red-star">*</span></label>
                              <div class="col-lg-10 col-sm-6 col-xs-12">
                                 {{ Form::select('user_id', [null => 'Please Select Vendor'] + $user_box, '', ['id' => 'user_id','class'=>'form-control'])}} 
                              </div>
                           </div>
                        </div>
                        <div class="col-12 col-sm-12 col-md-6">
                           <div class="row form-group">
                              <label class="col-lg-4 col-sm-6 col-xs-12 control-label">Store Name (en)<span class="red-star">*</span></label>
                              <div class="col-lg-8 col-sm-6 col-xs-12">
                                 {!! Form::text('store_name',null, ['class' => 'form-control','placeholder' => 'Store Name (en)','required'=>'required','id'=>'store_name','onkeypress' => 'error_remove()' ]) !!}
                              </div>
                           </div>
                        </div>
                        <div class="col-12 col-sm-12 col-md-6">
                           <div class="row form-group">
                              <label class="col-lg-4 col-sm-6 col-xs-12 control-label">Store Name (Burmese)<span class="red-star">*</span></label>
                              <div class="col-lg-8 col-sm-6 col-xs-12">
                                 {!! Form::text('store_name_burmese',null, ['class' => 'form-control','placeholder' => 'Store Name (Burmese)','required'=>'required','id'=>'store_name_burmese','onkeypress' => 'error_remove()' ]) !!}
                              </div>
                           </div>
                        </div>
                        <div class="col-12 col-sm-12 col-md-6">
                           <div class="row form-group">
                              <label class="col-lg-4 col-sm-6 col-xs-12 control-label">Store Logo<span class="red-star">*</span></label>
                              <div class="col-lg-8 col-sm-6 col-xs-12">
                                 {!! Form::file('logo', ['class' => 'form-control','id'=>'logo' ]) !!}
                              </div>
                           </div>
                        </div>
                        <div class="col-12 col-sm-12 col-md-6">
                           <div class="row form-group">
                              <label class="col-lg-4 col-sm-6 col-xs-12 control-label">Store Image<span class="red-star">*</span></label>
                              <div class="col-lg-8 col-sm-6 col-xs-12">
                                 {!! Form::file('image', ['class' => 'form-control','id'=>'image' ]) !!}
                              </div>
                           </div>
                        </div>
                        <div class="col-12 col-sm-12 col-md-6">
                           <div class="row form-group">
                              <label class="col-lg-4 col-sm-6 col-xs-12 control-label">Email<span class="red-star">*</span></label>
                              <div class="col-lg-8 col-sm-6 col-xs-12">
                                 {!! Form::text('email',null, ['class' => 'form-control','placeholder' => 'Email','required'=>'required','id'=>'email','onkeypress' => 'error_remove()' ]) !!}
                              </div>
                           </div>
                        </div>
                        <div class="col-12 col-sm-12 col-md-6">
                           <div class="row form-group">
                              <label class="col-lg-4 col-sm-6 col-xs-12 control-label">Mobile<span class="red-star">*</span></label>
                              <div class="col-lg-8 col-sm-6 col-xs-12">
                                 <div class="row">
                                    <div class="col-md-4">
                                    <select name="country_code" id="" class="form-control">
                                       @foreach($countryData as $country)
                                       <option value="{{ $country->phonecode }}" @if('+95' == $country->phonecode) selected @endif>{{ $country->name }}({{ $country->phonecode }}) </option>
                                       @endforeach
                                    </select>
   </div>
                                    <div class="col-lg-8 col-sm-8 col-xs-12">
                                       {!! Form::text('mobile',null, ['class' => 'form-control','placeholder' => 'Mobile ','required'=>'required','id'=>'mobile','onkeypress' => 'error_remove()' ]) !!}   
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="col-12 col-sm-12 col-md-6">
                           <div class="row form-group">
                              <label class="col-lg-4 col-sm-6 col-xs-12 control-label">Business Address<span class="red-star">*</span></label>
                              <div class="col-lg-8 col-sm-6 col-xs-12">
                                 {!! Form::text('address',null, ['class' => 'form-control address','placeholder' => 'Address ','required'=>'required','id'=>'address','onkeypress' => 'error_remove()' ]) !!}
                                 <input type="hidden" name="latitude" id="latitude"/>
                                 <input type="hidden" name="longitude" id="longitude"/>
                              </div>
                           </div>
                        </div>
                        <div class="col-12 col-sm-12 col-md-6">
                           <div class="row form-group">
                              <label class="col-lg-4 col-sm-6 col-xs-12 control-label">{{ __('vendor.city') }}<span class="red-star">*</span></label>
                              <div class="col-lg-8 col-sm-6 col-xs-12">
                                 {!! Form::text('city',null, ['class' => 'form-control city','placeholder' => 'City autofilled ','required'=>'required','id'=>'city','onkeypress' => 'error_remove()' ]) !!}
                                 @error('city')
                                    <p class="error">{{ $message }}</p>
                                 @enderror
                                 <small class="text-muted">City is auto filled as per business address make sure it's correct</small>
                              </div>
                           </div>
                        </div>


                        <div class="col-12 col-sm-12 col-md-6">
                           <div class="row form-group">
                              <label class="col-lg-4 col-sm-6 col-xs-12 control-label">Select Business Category<span class="red-star">*</span></label>
                              <div class="col-lg-8 col-sm-6 col-xs-12">
                                 {{ Form::select('business_category_id', [null => 'Please Select Business Category'] + $businessCategoryList, '', ['id' => 'business_category_id','class'=>'form-control'])}}  	
                              </div>
                           </div>
                        </div>
                        <div class="col-12 col-sm-12 col-md-6">
                           <div class="row form-group">
                              <label class="col-lg-4 col-sm-6 col-xs-12 control-label">Store Open Time<span class="red-star">*</span></label>
                              <div class="col-lg-8 col-sm-6 col-xs-12">
                                 {!! Form::text('store_open_time',null, ['class' => 'form-control timepicker','placeholder' => '08:30 AM','required'=>'required','id'=>'store_open_time','readonly'=>'true','onkeypress' => 'error_remove()']) !!}
                              </div>
                           </div>
                        </div>
                        <div class="col-12 col-sm-12 col-md-6">
                           <div class="row form-group">
                              <label class="col-lg-4 col-sm-6 col-xs-12 control-label">Store Close Time<span class="red-star">*</span></label>
                              <div class="col-lg-8 col-sm-6 col-xs-12">
                                 {!! Form::text('store_close_time',null, ['class' => 'form-control timepicker','placeholder' => '08:30 PM','required'=>'required','id'=>'store_close_time','readonly'=>'true','onkeypress' => 'error_remove()']) !!}
                              </div>
                           </div>
                        </div>
                        <div class="col-6 col-sm-12 col-md-6">
                           <div class="row form-group">
                              <label class="col-lg-4 col-sm-6 col-xs-12 control-label">Closing Day</label>
                              <div class="col-lg-8 col-sm-6 col-xs-12">
                                 <?php $helper=new App\Helpers;?>
                                 {!! Form::select('closing_day',$helper->SelectClosingDay(),null, ['class' => 'form-control','id'=>'closing_day','onkeypress' => 'error_remove()']) !!}
                              </div>
                           </div>
                        </div>
                        <div class="col-12 col-sm-12 col-md-6">
                           <div class="row form-group">
                              <label class="col-lg-4 col-sm-6 col-xs-12 control-label">Comission (%)<span class="red-star">*</span></label>
                              <div class="col-lg-8 col-sm-6 col-xs-12">
                                 {!! Form::text('comission',null, ['class' => 'form-control','placeholder' => 'Comission','required'=>'required','id'=>'comission','onkeypress' => 'error_remove()' ]) !!}
                              </div>
                           </div>
                        </div>
                        <!--<div class="col-12 col-sm-12 col-md-12">
                           <div class="row form-group">
                               <label class="col-lg-2 col-sm-6 col-xs-12 control-label">About Store<span class="red-star">*</span></label>
                               <div class="col-lg-10 col-sm-6 col-xs-12">
                           {!! Form::textarea('description',null, ['class' => 'form-control','placeholder' => 'About','required'=>'required','id'=>'description','rows'=>3,'onkeypress' => 'error_remove()' ]) !!}
                                 
                               </div>
                           </div>
                           </div>-->
                        <div class="col-12 col-sm-12 col-md-6">
                           <div class="row form-group">
                              <label class="col-lg-4 col-sm-6 col-xs-12 control-label">About Store (en)<span class="red-star">*</span></label>
                              <div class="col-lg-8 col-sm-6 col-xs-12">
                                 {!! Form::textarea('description',null, ['class' => 'form-control','placeholder' => 'About Store (en)','required'=>'required','id'=>'description','rows'=>3,'onkeypress' => 'error_remove()' ]) !!}
                              </div>
                           </div>
                        </div>
                        <div class="col-12 col-sm-12 col-md-6">
                           <div class="row form-group">
                              <label class="col-lg-4 col-sm-6 col-xs-12 control-label">About Store (Burmese)<span class="red-star">*</span></label>
                              <div class="col-lg-8 col-sm-6 col-xs-12">
                                 {!! Form::textarea('description_burmese',null, ['class' => 'form-control','placeholder' => 'About Store (Burmese)','required'=>'required','id'=>'description_burmese','rows'=>3,'onkeypress' => 'error_remove()' ]) !!}
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="row form-btn text-center">
               <div class="col-sm-12 p-r-30">
                  <div class="col-md-12">
                     {!! Form::submit('Save',['class' => 'btn btn-primary btn-flat subbtn', 'type' => 'submit']) !!}
                     <!--{!! Form::submit('Cancel',['class' => 'btn btn-flat subbtn','data-dismiss'=>'modal']) !!}-->
                     <a href="{{URL::to('/admin/store')}}">{!! Form::button('Cancel',['class' => 'btn btn-flat subbtn','data-dismiss'=>'modal']) !!}</a>
                  </div>
               </div>
               {!! Form::close() !!}
            </div>
         </div>
      </div>
   </div>
</section>
<script type="text/javascript">
   google.maps.event.addDomListener(window, 'click', function () {
   	var addressBox = document.getElementsByClassName('address');
   	
   	
   	for(var i=0; i< addressBox.length; i++){
   		var places = new google.maps.places.Autocomplete(addressBox[i]);
   		google.maps.event.addListener(places, 'place_changed', function () {
   			var place = places.getPlace();
   			console.log(place);
   			var address = place.formatted_address;
   			
   			var latitude = place.geometry.location.lat();
   			
   			var longitude = place.geometry.location.lng();  
   			$('#latitude').val(latitude); 
   			$('#longitude').val(longitude);
            getCity(place.address_components); 
   		});
   	}
   	
   });

   //  ----------get city name from map api----------
   function getCity(place) {
      for (var i=0; i<place.length; i++)
      {
            if(place[i].types[0] == "locality") {
               city = place[i];
               $("#city").val(city.long_name);
               // alert(city.long_name)
            }  
      }
   }
   
</script>
<script src="{{ asset('admin_assets/js/bootstrap-datetimepicker.min.js') }}"></script>
<link rel="stylesheet" href="{{ asset('admin_assets/css/bootstrap-datetimepicker.min.css') }}">
<script type="text/javascript">
   $('#store_open_time').timepicker({
   		showMeridian: false     
   	});
   $('#store_close_time').timepicker({
   		showMeridian: false     
   	});	
        
</script>
@stop

@push('script')
    <!-- Select2 -->
   <script src="{{ asset('admin_assets/bower_components/select2/dist/js/select2.full.min.js') }}"></script>
   <script>
       $('.select2').select2();
   </script>
@endpush

@push('style')
<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('admin_assets/bower_components/select2/dist/css/select2.min.css') }}">
@endpush 