<script type="text/javascript">
    $(document).ready(function () 
    {  $.LoadingOverlay("hide");
        $( '#editCityForm' ).on( 'submit', function(e) 
        {
            e.preventDefault();
               $.ajaxSetup({
                  headers: {
                      'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                  }
              });
           
            var name = $('#name').val();
            var id = $('#id').val();
            var country_id = $('#country_id').val();
            $.ajax({
                dataType: 'json',
            data: {name:name,id:id,country_id:country_id}, 
            type: "POST",
            url: '{{ URL::to('/admin/edit-city-post') }}',
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
            {showMsg(data.message, "success");}
            $("#myModal").modal('hide');
            
	            return false;
          }
            
                         
        });

    });
});
</script>

    {!! Form::open(array('url' => '/admin/edit-city-post', 'method' => 'post','name'=>'editCityForm','files'=>true,'novalidate' => 'novalidate','id' => 'editCityForm')) !!}
    <div class="col-sm-12 p-r-30">
        <div class="panel form-horizontal panel-transparent">
            <div class="panel-body">
                <div class="row">
                <div class="alert alert-danger" style="display:none"></div>
                <div class="col-12 col-sm-12 col-md-6">
                        <div class="row form-group">
                            <label class="col-lg-4 col-sm-6 col-xs-12 control-label">Country<span class="red-star">*</span></label>
                            <div class="col-lg-8 col-sm-6 col-xs-12">
                            {!! Form::hidden('id',base64_encode($city_data->id),['id'=>'id']) !!}
                            {!! Form::select('country_id',$country_box,$city_data->country_id, ['class' => 'form-control','required'=>'required','id'=>'country_id','onChange' => 'error_remove()' ]) !!}
                            </div>
                            
                        </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-6">
                        <div class="row form-group">
                            <label class="col-lg-4 col-sm-6 col-xs-12 control-label">City Name<span class="red-star">*</span></label>
                            <div class="col-lg-8 col-sm-6 col-xs-12">
                            {!! Form::text('name',$city_data->name, ['class' => 'form-control','placeholder' => 'Name','required'=>'required','id'=>'name','onkeypress' => 'error_remove()' ]) !!}
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
