@extends('layouts.adminmaster')
@section('title')
All Star Delivery | Edit Store Manager
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
    { $.LoadingOverlay("hide");
        $( '#editStoreForm' ).on( 'submit', function(e) 
        {    $.LoadingOverlay("show");
            e.preventDefault();
               $.ajaxSetup({
                  headers: {
                      'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                  }
              });
           
             // for(var instanceName in CKEDITOR.instances)
           // CKEDITOR.instances[instanceName].updateElement();
            $.ajax({
                dataType: 'json',
                data:  new FormData(this),
                contentType: false,
                cache: false,
                processData:false,
                type: "POST",
                url: '{{ URL::to('/admin/edit-store-post') }}',
        }).done(function( data ) 
        {  error_remove (); if(data.success==false)
            {
                $.each(data.errors, function(key, value){
                    $('#'+key).closest('.form-group').addClass('has-error');
					$('<div class="jquery-validate-error help-block animated fadeInDown">'+value+'</div>').insertAfter($('#'+key));
                });

          }else{
            search();
            //alert(data.message);
            if(data.class == 'success')
            { $.LoadingOverlay('hide'); showMsg(data.message, "success");}
            $("#myModal").modal('hide');
            window.location = '{{ URL::to('/admin/store') }}';
	            return false;
          }
            $.LoadingOverlay("hide");
                         
        });

    });
});


$(document).ready(function () { 
    		$( "#country_id" ).change(function () {
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

</script>

<section class="content-header">
    <h1>Store</h1>
    <ol class="breadcrumb">
       <li><a href="{{url('/admin')}}"><i class="fa fa-dashboard"></i> Home</a></li>
       <li><a href="{{url('admin/store')}}">Store</a></li>
       <li class="active">Store edit</li>
    </ol>
 </section>
 <section class="" style="padding: 15px;">
    <div class="row">
    <div class="col-xs-12 page-user">
    <div class="box">
       <div class="box-body">
          {!! Form::open(array('url' => '/admin/edit-store-post', 'method' => 'post','name'=>'editStoreForm','files'=>true,'novalidate' => 'novalidate','id' => 'editStoreForm')) !!}
          {!! Form::hidden('id',base64_encode($storedata->id),['id'=>'id']) !!}
          {!! Form::hidden('store_id',$storedata->id,['id'=>'store_id']) !!}
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
                                  {{ Form::select('user_id', [null => 'Please Select Vendor'] + $user_box, $storedata->user_id, ['id' => 'user_id','class'=>'form-control'])}} 
                               </div>
                            </div>
                         </div>
                         <div class="col-12 col-sm-12 col-md-6">
                            <div class="row form-group">
                               <label class="col-lg-4 col-sm-6 col-xs-12 control-label">Store Name (en)<span class="red-star">*</span></label>
                               <div class="col-lg-8 col-sm-6 col-xs-12">
                                  {!! Form::text('store_name',$storedata->name, ['class' => 'form-control','placeholder' => 'Store Name (en)','required'=>'required','id'=>'store_name','onkeypress' => 'error_remove()' ]) !!}
                               </div>
                            </div>
                         </div>
                         <div class="col-12 col-sm-12 col-md-6">
                            <div class="row form-group">
                               <label class="col-lg-4 col-sm-6 col-xs-12 control-label">Store Name (Burmese)<span class="red-star">*</span></label>
                               <div class="col-lg-8 col-sm-6 col-xs-12">
                                  {!! Form::text('store_name_burmese',$storedata->name_burmese, ['class' => 'form-control','placeholder' => 'Store Name (Burmese)','required'=>'required','id'=>'store_name_burmese','onkeypress' => 'error_remove()' ]) !!}
                               </div>
                            </div>
                         </div>
                         <div class="col-12 col-sm-12 col-md-6">
                            <div class="row form-group">
                               <label class="col-lg-4 col-sm-6 col-xs-12 control-label">Store Image<span class="red-star">*</span></label>
                               <div class="col-lg-8 col-sm-6 col-xs-12">
                                  {!! Form::file('image', ['class' => 'form-control','id'=>'image' ]) !!}
                                  @if(!empty(object_get($storedata,'image', NULL)))
                                   <img src="{{asset('media/store/'.$storedata->image)}}" style="width:50px; height:50px;"> @else No Image @endif
                               </div>
                            </div>
                         </div>
                         <div class="col-12 col-sm-12 col-md-6">
                            <div class="row form-group">
                               <label class="col-lg-4 col-sm-6 col-xs-12 control-label">Store Logo<span class="red-star">*</span></label>
                               <div class="col-lg-8 col-sm-6 col-xs-12">
                                  {!! Form::file('logo', ['class' => 'form-control','id'=>'logo' ]) !!}
                                   @if(!empty(object_get($storedata, 'store_logo', NULL))) 
                                   <img src="{{asset('media/store/'.$storedata->store_logo)}}" style="width:50px; height:50px;"> @else No Image @endif
                               </div>
                            </div>
                         </div>
                         <div class="col-12 col-sm-12 col-md-6">
                            <div class="row form-group">
                               <label class="col-lg-4 col-sm-6 col-xs-12 control-label">Email<span class="red-star">*</span></label>
                               <div class="col-lg-8 col-sm-6 col-xs-12">
                                  {!! Form::text('email',$storedata->email, ['class' => 'form-control','placeholder' => 'Email','required'=>'required','id'=>'email','onkeypress' => 'error_remove()' ]) !!}
                               </div>
                            </div>
                         </div>
                         <div class="col-12 col-sm-12 col-md-6">
                            <div class="row form-group">
                               <label class="col-lg-4 col-sm-6 col-xs-12 control-label">Mobile<span class="red-star">*</span></label>
                               <div class="col-lg-8 col-sm-6 col-xs-12">
                                  <div class="row">
                                     <div class="col-lg-5 col-sm-6 col-xs-12">
                                        {!! Form::select('country_code',$countrycode_box,$storedata->country_code, ['class' => 'form-control','required'=>'required','id'=>'country_code','onkeypress' => 'error_remove()']) !!}
                                     </div>
                                     <div class="col-lg-7 col-sm-6 col-xs-12">
                                        {!! Form::text('mobile',$storedata->mobile, ['class' => 'form-control','placeholder' => 'Mobile ','required'=>'required','id'=>'mobile','onkeypress' => 'error_remove()' ]) !!}   
                                     </div>
                                  </div>
                               </div>
                            </div>
                         </div>
                         <div class="col-12 col-sm-12 col-md-6">
                            <div class="row form-group">
                               <label class="col-lg-4 col-sm-6 col-xs-12 control-label">Business Address<span class="red-star">*</span></label>
                               <div class="col-lg-8 col-sm-6 col-xs-12">
                                  {!! Form::text('address',$storedata->address, ['class' => 'form-control address','placeholder' => 'Address ','required'=>'required','id'=>'address','onkeypress' => 'error_remove()' ]) !!}
                                  <input type="hidden" name="latitude" value="<?php echo $storedata->lat;?>" id="latitude"/>
                                  <input type="hidden" name="longitude" value="<?php echo $storedata->lng;?>" id="longitude"/>
                               </div>
                            </div>
                         </div>

                        <div class="col-12 col-sm-12 col-md-6">
                           <div class="row form-group">
                              <label class="col-lg-4 col-sm-6 col-xs-12 control-label">{{ __('vendor.city') }}<span class="red-star">*</span></label>
                              <div class="col-lg-8 col-sm-6 col-xs-12">
                                 {!! Form::text('city',$storedata ? $storedata->city : null, ['class' => 'form-control city','placeholder' => 'City autofilled ','required'=>'required','id'=>'city','onkeypress' => 'error_remove()' ]) !!}
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
                                  {{ Form::select('business_category_id', [null => 'Please Select Business Category'] + $businessCategoryList, $storedata->business_category_id, ['id' => 'business_category_id','class'=>'form-control'])}}  	
                               </div>
                            </div>
                         </div>
                         <div class="col-12 col-sm-12 col-md-6">
                            <div class="row form-group">
                               <label class="col-lg-4 col-sm-6 col-xs-12 control-label">Store Open Time<span class="red-star">*</span></label>
                               <div class="col-lg-8 col-sm-6 col-xs-12">
                                  {!! Form::text('store_open_time',$storedata->open_at, ['class' => 'form-control timepicker','placeholder' => '08:30 AM','required'=>'required','id'=>'store_open_time','readonly'=>'true','onkeypress' => 'error_remove()']) !!}
                               </div>
                            </div>
                         </div>
                         <div class="col-12 col-sm-12 col-md-6">
                            <div class="row form-group">
                               <label class="col-lg-4 col-sm-6 col-xs-12 control-label">Store Close Time<span class="red-star">*</span></label>
                               <div class="col-lg-8 col-sm-6 col-xs-12">
                                  {!! Form::text('store_close_time',$storedata->close_at, ['class' => 'form-control timepicker','placeholder' => '08:30 PM','required'=>'required','id'=>'store_close_time','readonly'=>'true','onkeypress' => 'error_remove()']) !!}
                               </div>
                            </div>
                         </div>
                         <div class="col-6 col-sm-12 col-md-6">
                            <div class="row form-group">
                               <label class="col-lg-4 col-sm-6 col-xs-12 control-label">Closing Day</label>
                               <div class="col-lg-8 col-sm-6 col-xs-12">
                                  <?php $helper=new App\Helpers;?>
                                  {!! Form::select('closing_day',$helper->SelectClosingDay(),$storedata->closing_day, ['class' => 'form-control','id'=>'closing_day','onkeypress' => 'error_remove()']) !!}
                               </div>
                            </div>
                         </div>
                         <div class="col-12 col-sm-12 col-md-6">
                            <div class="row form-group">
                               <label class="col-lg-4 col-sm-6 col-xs-12 control-label">Comission (%)<span class="red-star">*</span></label>
                               <div class="col-lg-8 col-sm-6 col-xs-12">
                                  {!! Form::text('comission',$storedata->comission, ['class' => 'form-control','placeholder' => 'Comission','required'=>'required','id'=>'comission','onkeypress' => 'error_remove()' ]) !!}
                               </div>
                            </div>
                         </div>
                         <div class="col-12 col-sm-12 col-md-6">
                            <div class="row form-group">
                               <label class="col-lg-4 col-sm-6 col-xs-12 control-label">About Store (en)<span class="red-star">*</span></label>
                               <div class="col-lg-8 col-sm-6 col-xs-12">
                                  {!! Form::textarea('description',$storedata->description, ['class' => 'form-control','placeholder' => 'About Store (en)','required'=>'required','id'=>'description','rows'=>3,'onkeypress' => 'error_remove()' ]) !!}
                               </div>
                            </div>
                         </div>
                         <div class="col-12 col-sm-12 col-md-6">
                            <div class="row form-group">
                               <label class="col-lg-4 col-sm-6 col-xs-12 control-label">About Store (Burmese)<span class="red-star">*</span></label>
                               <div class="col-lg-8 col-sm-6 col-xs-12">
                                  {!! Form::textarea('description_burmese',$storedata->description_burmese, ['class' => 'form-control','placeholder' => 'About Store (Burmese)','required'=>'required','id'=>'description_burmese','rows'=>3,'onkeypress' => 'error_remove()' ]) !!}
                               </div>
                            </div>
                         </div>
                         <div class="col-12 col-sm-12 col-md-6">
                            <div class="row form-group">
                               <label class="col-lg-4 col-sm-6 col-xs-12 control-label">Select Status<span class="red-star">*</span></label>
                               <div class="col-lg-8 col-sm-6 col-xs-12">
                                  {!! Form::select('status',[""=>"Select Status","0"=>'Deactive',"1"=>"Active"],$storedata->status, ['class' => 'form-control','required'=>'required','id'=>'status','onkeypress' => 'error_remove()' ]) !!}  
                               </div>
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
                   <a href="{{URL::to('/admin/store')}}">{!! Form::button('Cancel',['class' => 'btn btn-flat subbtn','data-dismiss'=>'modal']) !!}</a>
                </div>
             </div>
             {!! Form::close() !!}
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
           // $('#delivery_start_time').timepicker();
			 //$('#delivery_end_time').timepicker();
			$('#store_open_time').timepicker({
					showMeridian: false     
				});
			$('#store_close_time').timepicker({
					showMeridian: false     
				});	
        </script>
@stop 