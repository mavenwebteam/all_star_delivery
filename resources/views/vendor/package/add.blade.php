<script type="text/javascript">

function isNumberKey(evt){

    var charCode = (evt.which) ? evt.which : event.keyCode

    if (charCode > 31 && (charCode < 48 || charCode > 57))

        return false;

    return true;

}
$('.decimal').keypress(function (e) {
        var character = String.fromCharCode(e.keyCode)
        var newValue = this.value + character;
        if (isNaN(newValue) || parseFloat(newValue) * 100 % 1 > 0) {
            e.preventDefault();
            return false;
        }
    });
 

    $(document).ready(function () 

    {  $.LoadingOverlay("hide");

        $( '#addCashLimit' ).on( 'submit', function(e) 

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

            url: '{{ URL::to('/admin/add-package-post') }}',

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

</script>



    {!! Form::open(array('url' => '/admin/add-package-post', 'method' => 'post','name'=>'addCashLimit','files'=>true,'novalidate' => 'novalidate','id' => 'addCashLimit')) !!}

    <div class="col-sm-12 p-r-30">

        <div class="panel form-horizontal panel-transparent">

            <div class="panel-body">

                <div class="row">

                <div class="alert alert-danger" style="display:none"></div>

                <div class="col-12 col-sm-12 col-md-6">

                        <div class="row form-group">

                            <label class="col-lg-4 col-sm-6 col-xs-12 control-label">Vendor <span class="red-star">*</span></label>

                            <div class="col-lg-8 col-sm-6 col-xs-12">

                            <?php $helper=new App\Helpers;?>

              {!! Form::select('vendor_id',$helper->GetVendorList(),null, ['class' => 'form-control','id'=>'vendor_id' ]) !!}                            </div>

                            

                        </div>

                    </div>
					 <div class="col-12 col-sm-12 col-md-6">

                    <div class="row form-group">

                        <label class="col-lg-4 col-sm-6 col-xs-12 control-label">Name<span class="red-star">*</span></label>

                        <div class="col-lg-8 col-sm-6 col-xs-12">

                        {!! Form::text('package_name',null, ['class' => 'form-control','placeholder' => 'Name','required'=>'required','id'=>'package_name']) !!}

                        </div>

                        

                    </div>

                    </div>
                <div class="col-12 col-sm-12 col-md-6">

                    <div class="row form-group">

                        <label class="col-lg-4 col-sm-6 col-xs-12 control-label">Duration (in days)<span class="red-star">*</span></label>

                        <div class="col-lg-8 col-sm-6 col-xs-12">

                        {!! Form::text('days',null, ['class' => 'form-control','placeholder' => 'Days','required'=>'required','id'=>'days','onkeypress' => "return isNumberKey(event)" ]) !!}

                        </div>

                        

                    </div>

                    </div>
                    <div class="col-12 col-sm-12 col-md-6">

                        <div class="row form-group">

                            <label class="col-lg-4 col-sm-6 col-xs-12 control-label">Amount<span class="red-star">*</span></label>

                            <div class="col-lg-8 col-sm-6 col-xs-12">

                            {!! Form::text('amount',null, ['class' => 'form-control decimal','placeholder' => 'Amount','required'=>'required','id'=>'amount']) !!}

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

