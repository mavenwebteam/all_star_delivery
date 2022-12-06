<script type="text/javascript">
    $(document).ready(function () 
    {   $.LoadingOverlay('hide');
        $( '#addProductInventory' ).on( 'submit', function(e) 
        {
            e.preventDefault();
               $.ajaxSetup({
                  headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}

              });
            $.ajax({
               dataType: 'json',
               type: "POST",
                data:  new FormData(this),
                contentType: false,
                cache: false,
                 processData:false,
                  url: '{{ URL::to('/sub-admin/import-product-post') }}',

        }).done(function( data ) 
        {  error_remove (); 
        if(data.success==false)
        {
              $.each(data.errors, function(key, value){
              $('#'+key).closest('.form-group').addClass('has-error');
					      $('<div class="jquery-validate-error help-block animated fadeInDown">'+value+'</div>').insertAfter($('#'+key));

                });

          }else{
            search();
            if(data.class == 'success')
            {showMsg(data.message, "success");}
		if(data.class == 'error')
            {showMsg(data.message, "error");}
            $("#myModal").modal('hide');
	            return false;
          }                 
        });

    });

});

</script>



    {!! Form::open(array('url' => '/sub-admin/import-product-post', 'method' => 'post','name'=>'addProductInventory','files'=>true,'novalidate' => 'novalidate','id' => 'addProductInventory')) !!}

    <a href="{{asset('/media/sample_product.xlsx')}}">Download Sample Template File</a>
    <div class="col-sm-12 p-r-30">
        <div class="panel form-horizontal panel-transparent">
            <div class="panel-body">
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-12">
                        <div class="row form-group">
                            <label class="col-lg-12 col-sm-12 col-xs-12">Select File<span class="red-star">*</span></label>
                            <div class="col-lg-12 col-sm-12 col-xs-12" id="import_file">
                                <div class="row">
                                     <div class="row">
                                    <div class="col-md-6" >
										 {!! Form::file('import_file', ['class' => 'form-control','id'=>'import_file' ]) !!}
                                    </div>
                                </div>
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
        {!! Form::submit('Import',['class' => 'btn btn-primary btn-flat subbtn', 'type' => 'submit']) !!}
        {!! Form::submit('Cancel',['class' => 'btn btn-flat subbtn','data-dismiss'=>'modal']) !!}
        </div>
    </div>
    {!! Form::close() !!}
