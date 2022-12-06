<script type="text/javascript">

    $(document).ready(function () 

    {   $.LoadingOverlay('hide');

        $( '#addUserForm' ).on( 'submit', function(e) 

        {

            e.preventDefault();

               $.ajaxSetup({

                  headers: {

                      'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')

                  }

              });

           

            $.ajax({
             dataType: 'json',
            type: "POST",

            data:  new FormData(this),

            contentType: false,

            cache: false,

            processData:false,

            url: '{{ URL::to('/vendor/add-delivery-slot-post') }}',

        }).done(function( data ) 

        {  error_remove (); if(data.success==false)

            {

                $.each(data.errors, function(key, value){

                    $('#'+key).closest('.form-group').addClass('has-error');

					$('<div class="jquery-validate-error help-block animated fadeInDown">'+value+'</div>').insertAfter($('#'+key));

                });



          }else{

            search();

            if(data.class == 'success')

            {showMsg(data.message, "success");}

            $("#myModal").modal('hide');

            

	            return false;

          }

            

                         

        });



    });

});
function isNumberKey(evt){
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}
$(document).ready(function () { 
    		$( "#store_id" ).change(function () {
    			var store_id = $(this).val();
    				$.ajax({
    					url: "{{url('vendor/getoutlet') }}" + '/'+store_id,
    					success: function(data) {
    						$('#outlet_id').prop('disabled', false);
    						$('#outlet_id').html('');
    						$('#outlet_id').html(data);		
    					}
    				});
    		});
        
    	});

</script>

<script src="{{ asset('admin_assets/js/bootstrap-datetimepicker.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('admin_assets/css/bootstrap-datetimepicker.min.css') }}">

 <script type="text/javascript">
             // $('#delivery_start_time').timepicker();
			 //$('#delivery_end_time').timepicker();
			 $('#delivery_start_time').timepicker({
					showMeridian: false     
				});
			$('#delivery_end_time').timepicker({
					showMeridian: false     
				});	
        </script>

    {!! Form::open(array('url' => '/vendor/add-delivery-slot-post', 'method' => 'post','name'=>'addUserForm','files'=>true,'novalidate' => 'novalidate','id' => 'addUserForm')) !!}

    <div class="col-sm-12 p-r-30">

        <div class="panel form-horizontal panel-transparent">

            <div class="panel-body">

                <div class="row">

                <div class="alert alert-danger" style="display:none"></div>
               
				<div class="col-12 col-sm-12 col-md-6">
					<div class="row form-group">
							<label class="col-lg-4 col-sm-6 col-xs-12 control-label">Select Outlet<span class="red-star">*</span></label>
							<div class="col-lg-8 col-sm-6 col-xs-12">
									<?php $helper = new App\Helpers; ?>
									{!! Form::select('outlet_id',$outlet_box,null, ['class' => 'form-control','required'=>'required','id'=>'outlet_id','onChange' => 'error_remove()' ]) !!}
							</div>
					</div>
				</div>
				
              <!--  <div class="col-12 col-sm-12 col-md-6">

                        <div class="row form-group">

                            <label class="col-lg-4 col-sm-6 col-xs-12 control-label">Type<span class="red-star">*</span></label>

                            <div class="col-lg-8 col-sm-6 col-xs-12">

                            <?php $helper=new App\Helpers;?>

                            {!! Form::select('type',$helper->SelectUserType(),null, ['class' => 'form-control','required'=>'required','id'=>'type','onkeypress' => 'error_remove()']) !!}

                            </div>

                            

                        </div>

                    </div>-->
					<!-- -->
                  
				   <div class="col-12 col-sm-12 col-md-6">
                        <div class="row form-group">
                            <label class="col-lg-4 col-sm-6 col-xs-12 control-label"> Delivery Start Time <span class="red-star">*</span></label>
                            <div class="col-lg-8 col-sm-6 col-xs-12">
                            {!! Form::text('delivery_start_time',null, ['class' => 'form-control timepicker','placeholder' => 'Delivery Start Time','required'=>'required','id'=>'delivery_start_time','readonly'=>'true']) !!}
                 
						 </div>
                        </div>
                    </div>
				   
				   
				    <div class="col-12 col-sm-12 col-md-6">
                        <div class="row form-group">
                            <label class="col-lg-4 col-sm-6 col-xs-12 control-label">Delivery End Time <span class="red-star">*</span></label>
                            <div class="col-lg-8 col-sm-6 col-xs-12">
                            {!! Form::text('delivery_end_time',null, ['class' => 'form-control','placeholder' => 'Delivery End Time','required'=>'required','id'=>'delivery_end_time','readonly'=>'true']) !!}
                 
						 </div>
                        </div>
                    </div>
				   
				  
				   
				  <!-- <div class="col-12 col-sm-12 col-md-6">
                        <div class="row form-group">
                            <label class="col-lg-4 col-sm-6 col-xs-12 control-label">Delivery Slots <span class="red-star">*</span></label>
                            <div class="col-lg-8 col-sm-6 col-xs-12">
                             {!! Form::text('delivery_slots',null, ['class' => 'form-control','placeholder' => 'Delivery Slots','required'=>'required','id'=>'delivery_slots']) !!}
                 
						 </div>
                        </div>
                    </div>-->
				    
                   
					   <div class="col-12 col-sm-12 col-md-6">
                        <div class="row form-group">
                            <label class="col-lg-4 col-sm-6 col-xs-12 control-label">Delivery Slots Duration <span class="red-star">*</span></label>
                            <div class="col-lg-8 col-sm-6 col-xs-12">
                              {!! Form::text('delivery_slot_duration',null, ['class' => 'form-control','placeholder' => 'Delivery Slots Duration','required'=>'required','onkeypress' => "return isNumberKey(event)",'id'=>'delivery_slot_duration']) !!}
                  
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

                    

					

                </div>

            </div>

        </div>

    </div>

    <div class="row form-btn text-center">

        <div class="col-sm-12 p-r-30">

        <div class="col-md-12"> 

        {!! Form::submit('Save',['class' => 'btn btn-primary btn-flat subbtn', 'type' => 'submit']) !!}

        {!! Form::submit('Cancel',['class' => 'btn btn-flat subbtn','data-dismiss'=>'modal']) !!}

        </div>

    </div>

    {!! Form::close() !!}

