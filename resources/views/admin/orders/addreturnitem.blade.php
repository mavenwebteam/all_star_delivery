<script type="text/javascript">

    $(document).ready(function () 

    {   $.LoadingOverlay('hide');

        $( '#addUserForm' ).on( 'submit', function(e) 
        {			var checklogin1 = checklogin();		if(checklogin1  == true){	
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

            url: '{{ URL::to('/admin/add-delivery-boy-post') }}',

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

}else{			location.reload();				$.LoadingOverlay("hide");		}  

    });
	
	
	
	$('.order_idbutton' ).on( 'click', function(e) 
        {    
		   var order_id = $("#order_id").val();
		  var checklogin1 = checklogin();		if(checklogin1  == true){	
            e.preventDefault();

               $.ajaxSetup({

                  headers: {

                      'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')

                  }

              });

           //alert(order_id);
           if(order_id != ""){
            $.ajax({
            //dataType: 'json',
            type: "POST",
            data: {"order_id":order_id},
            url: '{{ URL::to('/admin/add_return_item_data') }}',

        }).done(function(data) 

        {  error_remove (); if(data.success==false)

            {   //alert(data.message);
			        $('#order_id').closest('.form-group').addClass('has-error');

					$('<div class="jquery-validate-error help-block animated fadeInDown">'+data.message+'</div>').insertAfter($('#order_id'));

          }else{

            
			$('#get_order_data').html('');
    		$('#get_order_data').html(data);	

          }

            

                         

        });

		   } else{
			   alert("Please enter order id first.");
		   }
		}else{			location.reload();				$.LoadingOverlay("hide");		}  
    });

});

</script>




    <div class="col-sm-12 p-r-30">

        <div class="panel form-horizontal panel-transparent">

            <div class="panel-body">

                <div class="row">

                <div class="alert alert-danger" style="display:none"></div>
              
                
					

                    <div class="col-12 col-sm-12 col-md-12">

                        <div class="row form-group">

                            <label class="col-lg-4 col-sm-6 col-xs-12 control-label">Order Id<span class="red-star">*</span></label>

                            <div class="col-lg-6 col-sm-6 col-xs-12">

                            {!! Form::text('order_id',null, ['class' => 'form-control','placeholder' => 'Order id','required'=>'required','id'=>'order_id','onkeypress' => 'error_remove()' ]) !!}

                            </div>

                            <div class="col-lg-2 col-sm-6 col-xs-12">
								{!! Form::button('Get Order item',['class' => 'btn btn-primary btn-flat subbtn order_idbutton', 'type' => 'button']) !!}</div>

                        </div>

                    </div>

                 

                </div>
				 <div class="row" id="get_order_data">
				 </div>

            </div>

        </div>

    </div>

    <div class="row form-btn text-center">

      <!--  <div class="col-sm-12 p-r-30">

        <div class="col-md-12"> 

        {!! Form::submit('Save',['class' => 'btn btn-primary btn-flat subbtn', 'type' => 'submit']) !!}

        {!! Form::submit('Cancel',['class' => 'btn btn-flat subbtn','data-dismiss'=>'modal']) !!}

        </div>

    </div>-->

    

