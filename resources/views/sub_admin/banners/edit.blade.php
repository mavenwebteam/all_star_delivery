<script type="text/javascript">

    $(document).ready(function () 
    {   
        $.LoadingOverlay("hide");
        $( '#addUserForm' ).on( 'submit', function(e) 
        {   
            $.LoadingOverlay("show");		
            var checklogin1 = checklogin();		
            if(checklogin1  == true)
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
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    url: '{{ route("subAdmin.banners.update",$banner->id) }}',
                }).done(function( data ) 
                {  
                    error_remove ();
                    if(data.success==false)
                    {
                        if(data.msg)
                        {
                            $.LoadingOverlay("hide");  
                            showMsg(data.msg, "danger");
                        }else{
                            $.each(data.errors, function(key, value) {
                                if(key=='product_images')
                                {
                                    $('.all_img_outer').closest('.form-group').addClass('has-error');
                                    $('<div class="jquery-validate-error help-block animated fadeInDown">' + value + '</div>').insertAfter($('.all_img_outer'));
                                }else{
                                    $('#' + key).parent().addClass('form-group has-error');
                                    $('<div class="jquery-validate-error help-block animated fadeInDown">' + value + '</div>').insertAfter($('#' + key));
                                }
                            });
                        }
                        
                  

                    }else{
                        searchItem();
                        if(data.class == 'success')
                        {     
                            $.LoadingOverlay("hide");  
                            showMsg(data.message, "success");
                        }
                                    
                        $("#myModal").modal('hide');
                        
                            return false;
                    }
                    $.LoadingOverlay("hide");     
                });
            }
            else
            {
                location.reload();
                $.LoadingOverlay("hide");		
            }  
        });
    });

     /*
      || ------get stolre list of selected business category----------
      */ 
    $('#business_category_id').on('change', function()
    {
        const businessCatId = $(this).val();      
        $.ajax({
          dataType: 'json',
          type: "POST",
          // data: {businessCatId:businessCatId},
          contentType: false,
          cache: false,
          processData: false,
          url: '{{ url("admin/banners/list/store-list") }}'+'/'+businessCatId,
        }).done(function(data)
        {
            if (data.success == false)
            {
              showMsg('Store Not found for this category', "danger");    
            }
            else 
            {
                if(data.class == 'success')
                {  
                    $('#store_id').empty();
                  $.LoadingOverlay("hide");
                  $('#store_id').empty();
                  $('#store_id').append('<option value="">-Choose Store</option>');
                  $.each(data.stores,function(index,store_name){
                    $('#store_id').append('<option value="'+index+'">'+store_name+'</option>');
                  }); 
                }
            }
            $.LoadingOverlay("hide");
        });
    });
</script>

    {!! Form::open(array('url' => 'admin/drivers'.$banner->id, 'method' => 'post','name'=>'addUserForm','files' =>'true','novalidate' => 'novalidate','id' => 'addUserForm')) !!}
    @method('PUT')    
        <div class="col-sm-12 p-r-30">
            <div class="panel panel-transparent">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-xs-6">
                        <label for="business_category_id">Business Category<span class="red-star">*</span></label>
                        <select class="form-control" id="business_category_id" name="business_category_id">
                            <option value="">-Choose Business Category-</option>
                            @foreach ($businessCategory as $category)
                            <option value="{{ $category->id }}" @if($category->id == $banner->business_category_id) selected @endif>
                                {{ $category->name_en.'('.$category->name_burmese.')' }}
                            </option>  
                            @endforeach
                        </select>  
                        </div>
                        <div class="col-xs-6">
                        <label for="store_id">Store<span class="red-star">*</span></label>
                        <select class="form-control" id="store_id" name="store_id">
                            <option value="">-Choose Store-</option>
                            @foreach ($stores as $store)
                                <option value="{{ $store->id }}" @if($store->id == $banner->store_id) selected @endif>{{ $store->name }}</option>  
                            @endforeach
                        </select>  
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-xs-6">
                        <label for="banner">Banner<span class="red-star">*</span></label>
                        {!! Form::file('banner',['class' => 'form-control','placeholder' => '','accept'=>'image/*','required'=>'required','id'=>'banner','onkeypress' => 'error_remove()']) !!}
                        </div>
                        @if($banner->banner && file_exists(public_path().'/media/banners/thumb/'.$banner->banner))
                            <div class="col-xs-2" style="padding-top: 16px;">
                                <img src="{{ asset('media/banners/thumb/'.$banner->banner) }}" alt="" height="50" width="50"/>
                            </div>
                        @endif
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
        </div>
    {!! Form::close() !!}