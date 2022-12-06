<script type="text/javascript">
    function isNumberKey(evt){
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}
$(document).ready(function () 

    {  $.LoadingOverlay("hide");
        $('#editdeliveryForm').on( 'submit', function(e) 
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
            url: '{{ URL::to('/admin/delivery-price-manager-add-post') }}',
        }).done(function(data ) 
        {  error_remove (); 
        if(data.success==false)
            {
                $.each(data.errors, function(key, value){
                    $('#'+key).closest('.form-group').addClass('has-error');
					$('<div class="jquery-validate-error help-block animated fadeInDown">'+value+'</div>').insertAfter($('#'+key));
                });

          }else{
            search();
            //alert(Deliverypricedata.message);
            if(data.class == 'success')
            {showMsg(data.message, "success");}
            $("#myModal").modal('hide');
            
	            return false;
          }
            
                         
        });

    });
});
</script>

    {!! Form::open(array('url' => '/admin/delivery-price-manager-add-post', 'method' => 'post','name'=>'editdeliveryForm','files'=>true,'novalidate' => 'novalidate','id' => 'editdeliveryForm')) !!}
    <div class="col-sm-12 p-r-30">
        <div class="panel form-horizontal panel-transparent">
            <div class="panel-body">
                <div class="row">
                <div class="alert alert-danger" style="display:none"></div>
                  <div class="col-12 col-sm-12 col-md-6">
                        <div class="row form-group">
                            <label class="col-lg-4 col-sm-6 col-xs-12 control-label">vehical Type<span class="red-star">*</span></label>
                            <div class="col-lg-8 col-sm-6 col-xs-12">
                           
                            {!! Form::text('type',null, ['class' => 'form-control','placeholder' => 'vehical type','required'=>'required','id'=>'type']) !!}
                            </div>
                            
                        </div>
                    </div>
                    
                    <div class="col-12 col-sm-12 col-md-6">
                        <div class="row form-group">
                            <label class="col-lg-4 col-sm-6 col-xs-12 control-label">Minimum Order Amount<span class="red-star">*</span></label>
                            <div class="col-lg-8 col-sm-6 col-xs-12">
                            
                            {!! Form::text('minimum_order_option',null, ['class' => 'form-control','placeholder' => 'Minimum Order Amount','required'=>'required','id'=>'minimum_order_option','onkeypress' => "return isNumberKey(event)" ]) !!}
                            </div>
                            
                        </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-6">
                        <div class="row form-group">
                            <label class="col-lg-4 col-sm-6 col-xs-12 control-label">Maximum Store Distance<span class="red-star">*</span></label>
                            <div class="col-lg-8 col-sm-6 col-xs-12">
                           
                            {!! Form::text('maximum_distance_option',null, ['class' => 'form-control','placeholder' => 'Maximum Store Distance','required'=>'required','id'=>'maximum_distance_option','onkeypress' => "return isNumberKey(event)" ]) !!}
                            </div>
                            
                        </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-6">
                        <div class="row form-group">
                            <label class="col-lg-4 col-sm-6 col-xs-12 control-label">Estimated Time Option (km/min)<span class="red-star">*</span></label>
                            <div class="col-lg-8 col-sm-6 col-xs-12">
                           
                            {!! Form::text('estimated_time_option',null, ['class' => 'form-control','placeholder' => 'Estimated Time Option','required'=>'required','id'=>'estimated_time_option','onkeypress' => "return isNumberKey(event)" ]) !!}
                            </div>
                            
                        </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-6">
                        <div class="row form-group">
                            <label class="col-lg-4 col-sm-6 col-xs-12 control-label">Free Delivery Amount<span class="red-star">*</span></label>
                            <div class="col-lg-8 col-sm-6 col-xs-12">
                           
                            {!! Form::text('free_delivery_option',null, ['class' => 'form-control','placeholder' => 'Free Delivery Amount','required'=>'required','id'=>'free_delivery_option','onkeypress' => "return isNumberKey(event)" ]) !!}
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
        {!! Form::submit('Update',['class' => 'btn btn-primary btn-flat subbtn', 'type' => 'submit']) !!}
        </div>
    </div>
    {!! Form::close() !!}
