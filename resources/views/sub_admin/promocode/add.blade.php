
<script type="text/javascript">   
 
  $(document).ready(function()
    {
      $.ajaxSetup({
        headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')  }
      });
      $.LoadingOverlay("hide");
      $('#promocodeForm').on('submit', function(e)
      {
        $.LoadingOverlay("show");
        var checklogin1 = checklogin();
        if(checklogin1  == true)
        {	
            e.preventDefault();
            for (var instanceName in CKEDITOR.instances)
            CKEDITOR.instances[instanceName].updateElement();
            
              $.ajax({
              dataType: 'json',
              type: "POST",
              data: new FormData(this),
              contentType: false,
              cache: false,
              processData: false,
              url: '{{ route('subAdmin.promocode.store') }}'
            }).done(function(data)
              {
                console.log(data);
                error_remove();
                if (data.success == false)
                {
                  if(data.message)
                  {
                    toastr["danger"](e.responseJSON.message);
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
                  searchItem(); 
                  $.LoadingOverlay("hide");  
                  toastr["success"](data.message);
                  $("#myModal").modal('hide');
                  return false;
                }
                $.LoadingOverlay("hide");
              });
        }else{
            location.reload();
            $.LoadingOverlay("hide");
        }  
      });

    
    });
</script>

{!! Form::open(array('route' => 'admin.promocode.store', 'method' => 'post','name'=>'promocodeForm','files'=>true,'novalidate' => 'novalidate','id' => 'promocodeForm')) !!}

<div class="col-sm-12 p-r-30">
   <div class="panel panel-transparent">
      <div class="panel-body">
        <div class="row">
          <div class="col-xs-6">
            <label for="business_category_id">Business Category<span class="red-star">*</span></label>
            <select class="form-control" id="business_category_id" name="business_category_id">
              <option value="">-Choose Business Category-</option>
              @foreach ($businessCategories as $category)
                <option value="{{ $category->id }}">{{ $category->name_en.'('.$category->name_burmese.')' }}</option>  
              @endforeach
            </select>  
          </div>
          <div class="col-xs-6">
            <label for="title">Title<span class="red-star">*</span></label>
            <input name="title" type="text" id="title" class="form-control" placeholder="Title">
          </div>
        </div>
        <br>
        <div class="row">
          <div class="col-xs-6">
            <label for="image">Image<span class="red-star">*</span></label>
            {!! Form::file('image',['class' => 'form-control','placeholder' => '','accept'=>'image/*','required'=>'required','id'=>'image','onkeypress' => 'error_remove()']) !!}
          </div>
          <div class="col-xs-6">
            <label for="promocode">Promocode<span class="red-star">*</span></label>
            <input name="code" type="text" id="code" class="form-control" placeholder="Promocode">
          </div>
        </div>
        <br/>
        <div class="row">
          <div class="col-xs-6">
            <label for="start_date">Start Date<span class="red-star">*</span></label>
            <input name="start_date" type="text" id="start_date" class="datepicker rounded-0 form-control" placeholder="Star Date" autocomplete="off">
          </div>
          <div class="col-xs-6">
            <label for="end_date">End Date<span class="red-star">*</span></label>
            <input name="end_date" type="text" id="end_date" class="datepicker rounded-0 form-control" placeholder="End Date" autocomplete="off">
          </div>
        </div>
        <br/>
        <div class="row">
          <div class="col-xs-6">
            <label for="discount_present">Discount(%)<span class="red-star">*</span></label>
            <input name="discount_present" type="text" id="discount_present" class="form-control" placeholder="Discount Persent">
          </div>
          <div class="col-xs-6">
            <label for="cap_limit">Cap Limit<span class="red-star">*</span></label>
            <input name="cap_limit" type="text" id="cap_limit" class="form-control" placeholder="Cap limit amount">
            <small id="cap_limit" class="form-text text-muted">e.i. Amount of discount not exceed  150</small>
          </div>
        </div>
        <br/>
        <div class="row">
          <div class="col-xs-6">
            <label for="total_no_of_times_use">Total number of times will use<span class="red-star">*</span></label>
            <input name="total_no_of_times_use" type="text" id="total_no_of_times_use" class="form-control" placeholder="Number of times use for all user">
            <small id="total_no_of_times_use" class="form-text text-muted">Total number of times will use for all users</small>
          </div>
          <div class="col-xs-6">
            <label for="no_of_times_for_same_user">How many times use for same user<span class="red-star">*</span></label>
            <input name="no_of_times_for_same_user" type="text" id="no_of_times_for_same_user" class="form-control" placeholder="For same user">
            <small id="no_of_times_for_same_user" class="form-text text-muted">Total number of times will use for one users</small>
          </div>
        </div>
        <br/>
        <div class="row">
          <div class="col-xs-6">
            <label for="no_of_times_in_each_day">How many times use for same user in one day<span class="red-star">*</span></label>
            <input name="no_of_times_in_each_day" type="text" id="no_of_times_in_each_day" class="form-control" placeholder="for same user in each day">
            <small id="no_of_times_in_each_day" class="form-text text-muted">Single user use in each day</small>
          </div>
        </div>
        <br/>
        <div class="row">
          <div class="col-xs-12">
            <label for="description">Description</label>
            <textarea name="description" id="description" cols="30" rows="10"></textarea>
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

<script src="{{asset('/admin_assets/bower_components/ckeditor/ckeditor.js')}}"></script>
<script>
$(function() {
    CKEDITOR.replace('description')
    $('.textarea').wysihtml5()
});
</script>
<script>
  $(function () {
    $('.datepicker').datepicker({
      autoclose: true
    })
  });
</script>