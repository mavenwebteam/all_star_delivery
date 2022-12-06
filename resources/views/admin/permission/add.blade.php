
<script type="text/javascript">   
 
  $(document).ready(function()
    {
      $.ajaxSetup({
        headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')  }
      });
      $.LoadingOverlay("hide");
      $('#addUserForm').on('submit', function(e)
      {
        $.LoadingOverlay("show");
        var checklogin1 = checklogin();
        if(checklogin1  == true)
        {	
            e.preventDefault();
            
              $.ajax({
              dataType: 'json',
              type: "POST",
              data: new FormData(this),
              contentType: false,
              cache: false,
              processData: false,
              url: '{{ route('admin.banners.store') }}',
              error: function (e) {
                $.LoadingOverlay("hide");  
                $("#myModal").modal('hide');
                toastr[e.responseJSON.toster_class](e.responseJSON.msg);
              }
            }).done(function(data)
              {
                error_remove();
                if (data.success == false)
                {
                  if(data.msg)
                  {
                    showMsg(data.msg, "danger");
                  }else{
                    // ------show errors--------
                    $.each(data.errors, function(key, value) {
                      $('#' + key).parent().addClass('form-group has-error');
                      $('<div class="jquery-validate-error help-block animated fadeInDown">' + value + '</div>').insertAfter($('#' + key));
                    });
                  }
                    
                } 
                else 
                {
                  // searchItem();
                    if(data.class == 'success')
                    {    
                      $.LoadingOverlay("hide");  
                      showMsg(data.message, "success");
                    }
                    $("#myModal").modal('hide');
                    window.location.reload();
                    return false;
                }
                $.LoadingOverlay("hide");
              });
        }else{
            location.reload();
            $.LoadingOverlay("hide");
        }  
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
              searchItem();
                if(data.class == 'success')
                {    
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
    });
</script>

{!! Form::open(array('route' => 'admin.banners.store', 'method' => 'post','name'=>'addUserForm','files'=>true,'novalidate' => 'novalidate','id' => 'addUserForm')) !!}

<div class="col-sm-12 p-r-30">
   <div class="panel panel-transparent">
      <div class="panel-body">
        <div class="row">
          <div class="col-xs-6">
            <label for="business_category_id">Business Category<span class="red-star">*</span></label>
            <select class="form-control" id="business_category_id" name="business_category_id">
              <option value="">-Choose Business Category-</option>
              @foreach ($businessCategory as $category)
                <option value="{{ $category->id }}">{{ $category->name_en.'('.$category->name_burmese.')' }}</option>  
              @endforeach
            </select>  
          </div>
          <div class="col-xs-6">
            <label for="store_id">Store<span class="red-star">*</span></label>
            <select class="form-control" id="store_id" name="store_id">
              <option value="">-Choose Store-</option>
            </select>  
          </div>
        </div>
        <br>
        <div class="row">
          <div class="col-xs-6">
            <label for="banner">Banner<span class="red-star">*</span></label>
            {!! Form::file('banner',['class' => 'form-control','placeholder' => '','accept'=>'image/*','required'=>'required','id'=>'banner','onkeypress' => 'error_remove()']) !!}
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
   
  </div>
{!! Form::close() !!}


  