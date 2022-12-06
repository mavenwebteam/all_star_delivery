<script type="text/javascript">
    $(document).ready(function () 
    {  $.LoadingOverlay("hide");
        $( '#addContentForm' ).on( 'submit', function(e)  
        {   $.LoadingOverlay('show');
            e.preventDefault();
               $.ajaxSetup({
                  headers: {
                      'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                  }
              });
          
            //var title = $('#title').val();			// var message = $('#message').val();			  //var type = $('#type').val();
           
          
            $.ajax({			dataType: 'json',            type: "POST",            data:  new FormData(this),            contentType: false,            cache: false,            processData:false,
            url: '{{ URL::to('/admin/add-notification-post') }}',
        }).done(function( data ) 
        {  error_remove (); if(data.success==false)
            {
                $.each(data.errors, function(key, value){
                    $('#'+key).closest('.form-group').addClass('has-error');
					$('<div class="jquery-validate-error help-block animated fadeInDown">'+value+'</div>').insertAfter($('#'+key));
                });

          }else{
            search();$.LoadingOverlay('hide');
            $("#msg-data").html(data.message);
            $("#myModal").modal('hide');
            
	            return false;
          }
            $.LoadingOverlay('hide');
                         
        });

    });
});
</script>

    {!! Form::open(array('url' => '/admin/add-notification-post', 'method' => 'post','name'=>'addContentForm','files'=>true,'novalidate' => 'novalidate','id' => 'addContentForm')) !!}
    <div class="col-sm-12 p-r-30">
        <div class="panel form-horizontal panel-transparent">
            <div class="panel-body">
                <div class="row">
                <div class="alert alert-danger" style="display:none"></div>
                
                <div class="col-12 col-sm-12 col-md-12">
                            <div class="row form-group">
                                <label class="col-lg-2 col-sm-6 col-xs-12 control-label">Notification Title<span class="red-star">*</span></label>
                                <div class="col-lg-10 col-sm-6 col-xs-12">
                                
                                {!! Form::text('title',null, ['class' => 'form-control','placeholder' => 'Title','required'=>'required','id'=>'title','onkeypress' => 'error_remove()' ]) !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-12 col-md-12">
                            <div class="row form-group">
                                <label class="col-lg-2 col-sm-6 col-xs-12 control-label">Notification Message<span class="red-star">*</span></label>
                                <div class="col-lg-10 col-sm-6 col-xs-12">
                                   								 {!! Form::textarea('message',null, ['class' => 'form-control','placeholder' => 'Message','required'=>'required','id'=>'message','onkeypress' => 'error_remove()','cols'=>'10' ]) !!}
                                </div>
                            </div>
                        </div>						 <div class="col-12 col-sm-12 col-md-12">                            <div class="row form-group">                                <label class="col-lg-2 col-sm-6 col-xs-12 control-label">Recipients <span class="red-star">*</span></label>                                <div class="col-lg-10 col-sm-6 col-xs-12">                                    <input type="radio" name="type" id=""  value="0"> All Users 									  <input type="radio" name="type" id="type" value="2">All Delivery Boys                                 </div>                            </div>                        </div>
                    
                   
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
   