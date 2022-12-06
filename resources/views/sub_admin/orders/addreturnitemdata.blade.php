<script type="text/javascript">

    $(document).ready(function () 

    {   $.LoadingOverlay('hide');

        $( '#addUserFormdata' ).on( 'submit', function(e) 
        {  
		
		    var quantity = $("#quantity").val();
			var return_quantity = $("#return_quantity").val();
		var checklogin1 = checklogin();		if(checklogin1  == true){	
            e.preventDefault();

               $.ajaxSetup({

                  headers: {

                      'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')

                  }

              });

           
		if(return_quantity <= quantity){	
            $.ajax({
             dataType: 'json',
            type: "POST",

            data:  new FormData(this),

            contentType: false,

            cache: false,

            processData:false,

            url: '{{ URL::to('/sub-admin/add_return_item') }}',

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

		}else{
			alert("Return quantity not greater then actual quantity");
		}
				}else{			location.reload();				$.LoadingOverlay("hide");		}  
    });
});

</script>



    {!! Form::open(array('url' => '/sub-admin/add_return_item', 'method' => 'post','name'=>'addUserForm','files'=>true,'novalidate' => 'novalidate','id' => 'addUserFormdata')) !!}

    <div class="col-sm-12 p-r-30">

        <div class="panel form-horizontal panel-transparent">

            <div class="panel-body">

                <div class="row">

                <div class="alert alert-danger" style="display:none"></div>
              
                   {!! Form::hidden('id',$orderitems->id, ['class' => 'form-control','placeholder' => 'id','required'=>'required', 'id'=>'id','readonly'=>'true','onkeypress' => 'error_remove()' ]) !!}
					

                    <div class="col-12 col-sm-12 col-md-12">

                        <div class="row form-group">

                            <label class="col-lg-4 col-sm-6 col-xs-12 control-label">Product name<span class="red-star">*</span></label>

                            <div class="col-lg-6 col-sm-6 col-xs-12">

                            {!! Form::text('name',$orderitems->productname, ['class' => 'form-control','placeholder' => 'Product name','required'=>'required', 'id'=>'name', 'readonly'=>'true','onkeypress' => 'error_remove()' ]) !!}

                            </div>

                        </div>

                    </div>
					 <div class="col-12 col-sm-12 col-md-12">

                        <div class="row form-group">

                            <label class="col-lg-4 col-sm-6 col-xs-12 control-label">Quantity<span class="red-star">*</span></label>

                            <div class="col-lg-6 col-sm-6 col-xs-12">

                            {!! Form::text('quantity',$orderitems->quantity, ['class' => 'form-control','placeholder' => 'Quantity','required'=>'required', 'id'=>'quantity','readonly'=>'true','onkeypress' => 'error_remove()' ]) !!}

                            </div>

                          

                        </div>

                    </div>
					<div class="col-12 col-sm-12 col-md-12">

                        <div class="row form-group">

                            <label class="col-lg-4 col-sm-6 col-xs-12 control-label">Return Quantity<span class="red-star">*</span></label>

                            <div class="col-lg-6 col-sm-6 col-xs-12">

                            {!! Form::text('return_quantity',$orderitems->quantity, ['class' => 'form-control','placeholder' => 'Return Quantity','required'=>'required', 'id'=>'return_quantity','onkeypress' => 'error_remove()' ]) !!}

                            </div>

                          

                        </div>

                    </div>

                 

                </div>
				 <div class="row" id="get_order_data">
				 </div>

            </div>

        </div>

    </div>

    <div class="row form-btn text-center">

       <div class="col-sm-12 p-r-30">

        <div class="col-md-12"> 

        {!! Form::submit('Update',['class' => 'btn btn-primary btn-flat subbtn', 'type' => 'submit']) !!}


        </div>

    </div>

    {!! Form::close() !!}

