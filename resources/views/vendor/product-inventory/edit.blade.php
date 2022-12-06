
<script type="text/javascript">

    $(document).ready(function () 
    { $.LoadingOverlay("hide");
        $.LoadingOverlay('hide');
        $('#product_inventory_product_id').select2({
            placeholder: 'Select a product',
            dropdownParent: $("#myModal")
        });
        $( '#editInventoryForm' ).on( 'submit', function(e) 
        {   $.LoadingOverlay('show');			var checklogin1 = checklogin();		if(checklogin1  == true){	
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
            url: '{{ URL::to('/vendor/edit-product-inventory-post') }}',
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
            {
				 $.LoadingOverlay('hide');
				showMsg(data.message, "success");}
            $("#myModal").modal('hide');
            
	            return false;
          }
            
             $.LoadingOverlay('hide');             
        });
		}else{			location.reload();				$.LoadingOverlay("hide");		}  
    });
});




</script>

   
    {!! Form::open(array('url' => '/vendor/add-product-inventory-post', 'method' => 'post','name'=>'editInventoryForm','files'=>true,'novalidate' => 'novalidate','id' => 'editInventoryForm')) !!}
    <div class="col-sm-12 p-r-30">
        <div class="panel form-horizontal panel-transparent">
            <div class="panel-body">
                <div class="row">
                <div class="alert alert-danger" style="display:none"></div>
                   
					<!--<div class="col-12 col-sm-12 col-md-6">
					<div class="row form-group">
					
							<label class="col-lg-4 col-sm-6 col-xs-12 control-label">Select Store<span class="red-star">*</span></label>
					
							<div class="col-lg-8 col-sm-6 col-xs-12">
									<?php $helper = new App\Helpers; ?>
									{!! Form::select('store_id',$helper->GetStoreList(),$productinventoriesdata->store_id, ['class' => 'form-control','required'=>'required','id'=>'store_id','onChange' => 'error_remove()' ]) !!}
					
							</div>
					</div>
				</div>-->
				 {!! Form::hidden('id',base64_encode($productinventoriesdata->id),['id'=>'id']) !!}
				 <div class="col-12 col-sm-12 col-md-6">
                        <div class="row form-group">
                            <label class="col-lg-2 col-sm-6 col-xs-12 control-label">Product Name<span class="red-star">*</span></label>
                            <div class="col-lg-10 col-sm-6 col-xs-12">
                           
							{!! Form::select('product_id',$product_box,$productinventoriesdata->product_id, ['class' => 'form-control','required'=>'required','id'=>'store_id','onChange' => 'error_remove()' ]) !!}
                            </div>
                            
                        </div>
                    </div>
					
					
                    <div class="col-12 col-sm-12 col-md-6">
                        <div class="row form-group">
                            <label class="col-lg-4 col-sm-6 col-xs-12 control-label">Quantity<span class="red-star">*</span></label>
                            <div class="col-lg-8 col-sm-6 col-xs-12">
                            {!! Form::text('quantity',$productinventoriesdata->qty, ['class' => 'form-control','placeholder' => 'Quantity','required'=>'required','id'=>'quantity','onkeypress' => 'error_remove()','onkeypress' => "return isNumberKey(event)" ]) !!}
                            </div>
                            
                        </div>
                    </div>
                <!-- <div class="col-12 col-sm-12 col-md-6">
                        <div class="row form-group">
                            <label class="col-lg-4 col-sm-6 col-xs-12 control-label">Select Vendor<span class="red-star">*</span></label>
                            <div class="col-lg-8 col-sm-6 col-xs-12">
                            <?php $helper=new App\Helpers;?>

                            {!! Form::select('vendor_id',$helper->GetVendorList(),$productinventoriesdata->vendor_id, ['class' => 'form-control','required'=>'required','id'=>'vendor_id','onChange' => 'error_remove()' ]) !!}
                            </div>
                            
                        </div>
                    </div> -->
                   <!--<div class="col-12 col-sm-12 col-md-6">
                        <div class="row form-group">
                            <label class="col-lg-4 col-sm-6 col-xs-12 control-label">Price<span class="red-star">*</span></label>
                            <div class="col-lg-8 col-sm-6 col-xs-12">
                           

                            {!! Form::text('price',$productinventoriesdata->price, ['class' => 'form-control','placeholder' => 'Product Price','required'=>'required','id'=>'price','onkeypress' => 'error_remove()' ]) !!}
                            </div>
                            
                        </div>
                    </div>-->
					</div>
					<!--<div class="row">
					
                    <div class="col-12 col-sm-12 col-md-6">
                        <div class="row form-group">
                            <label class="col-lg-4 col-sm-6 col-xs-12 control-label">Discount Price<span class="red-star">*</span></label>
                            <div class="col-lg-8 col-sm-6 col-xs-12">
                            {!! Form::text('discount_price',$productinventoriesdata->discount_price, ['class' => 'form-control','placeholder' => 'Discount Price','required'=>'required','id'=>'discount_price','onkeypress' => 'error_remove()' ]) !!}
                            </div>
                            
                        </div>
                    </div>
					<div class="col-12 col-sm-12 col-md-6">
                        <div class="row form-group">
                            <label class="col-lg-4 col-sm-6 col-xs-12 control-label">Offer Starts At</label>

                            <div class="col-lg-8 col-sm-6 col-xs-12">
								<div class="input-group date">
												<div class="input-group-addon">
													<i class="fa fa-calendar"></i>
												</div>
													  {!! Form::text('offer_start_at',$productinventoriesdata->offer_start_at, ['class' => 'form-control','placeholder' => 'Offer Starts At','required'=>'required','id'=>'offer_start_at','onkeypress' => 'error_remove()','readonly'=>'true' ]) !!}

								</div>
                            </div>
                        </div>
                    </div>
					</div>
					<div class="row">
					<div class="col-12 col-sm-12 col-md-6">
                        <div class="row form-group">
                            <label class="col-lg-4 col-sm-6 col-xs-12 control-label">Offer End At</label>

                            <div class="col-lg-8 col-sm-6 col-xs-12">
								<div class="input-group date">
												<div class="input-group-addon">
													<i class="fa fa-calendar"></i>
												</div>
													  {!! Form::text('offer_start_on',$productinventoriesdata->offer_start_on, ['class' => 'form-control','placeholder' => 'Offer Starts At','required'=>'required','id'=>'offer_start_on','onkeypress' => 'error_remove()','readonly'=>'true' ]) !!}

								</div>
                            </div>
                        </div>
                    </div>
					
                    <div class="col-12 col-sm-12 col-md-6">
                        <div class="row form-group">
                            <label class="col-lg-4 col-sm-6 col-xs-12 control-label">Quantity<span class="red-star">*</span></label>
                            <div class="col-lg-8 col-sm-6 col-xs-12">
                            {!! Form::text('quantity',$productinventoriesdata->qty, ['class' => 'form-control','placeholder' => 'Quantity','required'=>'required','id'=>'quantity','onkeypress' => 'error_remove()' ]) !!}
                            </div>
                            
                        </div>
                    </div>
					</div>
					<div class="row">
					<!-- <div class="col-12 col-sm-12 col-md-6">
                        <div class="row form-group">
                            <label class="col-lg-4 col-sm-6 col-xs-12 control-label">Color</label>
                            <div class="col-lg-8 col-sm-6 col-xs-12">
                            {!! Form::text('color',$productinventoriesdata->color, ['class' => 'form-control','placeholder' => 'Color','required'=>'required','id'=>'color','onkeypress' => 'error_remove()' ]) !!}
                            </div>
                            
                        </div>
                    </div>-->
                   <!-- <div class="col-12 col-sm-12 col-md-6">
                        <div class="row form-group">
                            <label class="col-lg-4 col-sm-6 col-xs-12 control-label">Size</label>
                            <div class="col-lg-8 col-sm-6 col-xs-12">
                            {!! Form::text('size',$productinventoriesdata->size, ['class' => 'form-control','placeholder' => 'Size','required'=>'required','id'=>'size','onkeypress' => 'error_remove()' ]) !!}
                            </div>
                            
                        </div>
                    </div>-->
                    <!--<div class="col-12 col-sm-12 col-md-6">
                        <div class="row form-group">
                            <label class="col-lg-4 col-sm-6 col-xs-12 control-label">Volume </label>
                            <div class="col-lg-8 col-sm-6 col-xs-12">
                            {!! Form::text('volume',$productinventoriesdata->volume, ['class' => 'form-control','placeholder' => 'Volume ','required'=>'required','id'=>'volume','onkeypress' => 'error_remove()' ]) !!}
                            </div>
                            
                        </div>
                    </div>s
                    <div class="col-12 col-sm-12 col-md-6">
                        <div class="row form-group">
                            <label class="col-lg-4 col-sm-6 col-xs-12 control-label">Weight<span class="red-star">*</span></label>
                            <div class="col-lg-8 col-sm-6 col-xs-12">
                            {!! Form::text('weight',$productinventoriesdata->weight, ['class' => 'form-control','placeholder' => 'Weight','required'=>'required','id'=>'weight','onkeypress' => 'error_remove()' ]) !!}
                            </div>
                            
                        </div>
                    </div>
					<div class="col-12 col-sm-12 col-md-6">
                        <div class="row form-group">
                            <label class="col-lg-4 col-sm-6 col-xs-12 control-label">Weight Unit<span class="red-star">*</span></label>

                            <div class="col-lg-8 col-sm-6 col-xs-12">
							 {!! Form::select('weight_unit',$weight_box ,$productinventoriesdata->weight_unit, ['class' => 'form-control','onkeypress' => 'error_remove()','id'=>'weight_unit']) !!}

                            </div>
                        </div>
                    </div>
                   </div>
				   <div class="row">
					
					
                    <!--<div class="col-12 col-sm-12 col-md-6">
                        <div class="row form-group">
                            <label class="col-lg-4 col-sm-6 col-xs-12 control-label">Size</label>
                            <div class="col-lg-8 col-sm-6 col-xs-12">
                            
							
							  {!! Form::select('size',$helper->GetSize(),$productinventoriesdata->size, ['class' => 'form-control','required'=>'required','id'=>'brand_id','onChange' => 'error_remove()']) !!}

                            </div>
                            
                        </div>
                    </div>-->
					
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
<script>
  $(function () {
    //Date picker
    $('#offer_start_at').datepicker({
      autoclose: true
    })
    $('#offer_start_on').datepicker({
		minDate:  $("#offer_start_at").val(),
       autoclose: true
    })
    
  })
  
  
  
  $("#offer_start_at").change(function () {
	  $("#offer_start_at").val();
	  
	  });
</script>