
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
              url: '{{ route('subAdmin.drivers.store') }}',
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
                    
                } 
                else 
                {
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
        }else{
            location.reload();
            $.LoadingOverlay("hide");
        }  
      });
    });
  
  $("#motorbike").hide();

  $("#typeMotorbike").click(function(){
    $("#motorbike").show();
  });
  $("#typeBicycle").click(function(){
    $("#motorbike").hide();
  });

  function fileInputChange(aFiles) 
  {
      var newFiles = [];
      var files = [];
     //alert(aFiles);
      for (var index = 0; index < aFiles.length; index++) {
        var file = aFiles[index];
        var re = /(?:\.([^.]+))?$/;
        ext = re.exec(file.name)[1];
       
        if (ext == 'jpg' || ext == 'jpeg' || ext == 'png' || ext == 'PNG' || ext == 'JPG' || ext == 'JPEG') {
          newFiles.push(file);
          files.push(file);
        } else {

          alert("Please Upload jpg, jpeg, png formats only.");
          $('html, body').animate({
            scrollTop: 0
          }, 1000);
        }
      }

      newFiles.forEach(function(file) {
        var reader = new FileReader();
        reader.addEventListener("load", function() {
          var ImgElement = $('<div></div>')
            .addClass('choose_img').append($('<img />').attr("src", this.result)).append($('<span></span>').addClass("close").append($("<i></i>").addClass("fa fa-times")));
          $(".no_image").hide();
          $('.all_img_outer').append(ImgElement);

        }, false);
        reader.readAsDataURL(file);
      });
  }

  var productImg = $("#productImg");
  var product_images = $("#product_images");
  product_images.change(function() {
    fileInputChange(product_images[0].files);
  });

  productImg.click(function() {
    product_images.click();
  });

  $('body').on('click', '.close', function() {
    $(this).parent("div.choose_img").remove();
    if ($('.choose_img').length) {
      $(".no_image").hide();
    } else {
      $(".no_image").show();
    }
  });

  var obj = $(".all_img_outer");
  obj.on('dragenter', function(e)
    {
      e.stopPropagation();
      e.preventDefault();
      $(this).css('border', '2px solid #2d73a1');
    });

  obj.on('dragover', function(e)
    {
      e.stopPropagation();
      e.preventDefault();
    });

  obj.on('drop', function(e) {
    $(this).css('border', '2px dashed #2d73a1');
    e.preventDefault();
    var files = e.originalEvent.dataTransfer.files;
    $("#product_images").prop('files', files);
    fileInputChange(files);

  });

	function isNumberKey(evt){
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
  }
</script>

{!! Form::open(array('route' => 'admin.drivers.store', 'method' => 'post','name'=>'addUserForm','files'=>true,'novalidate' => 'novalidate','id' => 'addUserForm')) !!}

<div class="col-sm-12 p-r-30">
   <div class="panel panel-transparent">
      <div class="panel-body">
        <div class="row">
          <div class="col-xs-6">
            <label class="first_name">First Name<span class="red-star">*</span></label>
            {!! Form::text('first_name',null, ['class' => 'form-control','placeholder' => 'First Name','required'=>'required','id'=>'first_name','onkeypress' => 'error_remove()' ]) !!}
          </div>
          <div class="col-xs-6">
            <label for="last_name">Last Name<span class="red-star">*</span></label>
            {!! Form::text('last_name',null, ['class' => 'form-control','placeholder' => 'Last Name','required'=>'required','id'=>'last_name','onkeypress' => 'error_remove()' ]) !!}
          </div>
        </div>
        <br>
        <div class="row">
          <div class="col-xs-6">
            <label for="email">Email<span class="red-star">*</span></label>
            {!! Form::text('email',null, ['class' => 'form-control','placeholder' => 'email','required'=>'required','id'=>'email','onkeypress' => 'error_remove()']) !!}
          </div>
          <div class="col-xs-6">
            <label for="mobile">Mobile</label>
            {!! Form::text('mobile',null, ['class' => 'form-control','placeholder' => 'Mobile','required'=>'required','id'=>'mobile','onkeypress' => 'error_remove()', 'onkeypress' => "return isNumberKey(event)"]) !!}
          </div>
        </div> 
        <br>
        <div class="row">
          <div class="col-xs-6">
            <label for="password">Password<span class="red-star">*</span></label>
            {!! Form::text('password',null, ['class' => 'form-control','placeholder' => 'password','required'=>'required','id'=>'password','onkeypress' => 'error_remove()']) !!}
          </div>
          <div class="col-xs-6">
            <label for="profile_pic">Profile Picture</label>
            {!! Form::file('profile_pic',['class' => 'form-control','placeholder' => '','accept'=>'image/*','required'=>'required','id'=>'profile_pic','onkeypress' => 'error_remove()']) !!}
          </div>
        </div>  
        <br>
        <h3>Vehicle Detail</h3>
        <div class="row">
          <div class="col-xs-12">
            <label for="vehicle_type">Vehicle Type<span class="red-star">*</span></label>
            <div class="form-group">
              <label>
                <input type="radio" value="Bicycle" name="vehicle_type" class="flat-red" id="typeBicycle" checked>
                Bicycle
              </label>
              <label>
                <input type="radio" value="Motorbike" name="vehicle_type" class="flat-red" id="typeMotorbike">
                Motorbike
              </label>
            </div>
          </div>
        </div>
        <div id="motorbike">  
          <br>
          <div class="row">
            <div class="col-xs-6">
              <label for="model">Vehicle Model</label>
              {!! Form::text('model',null, ['class' => 'form-control','placeholder' => 'model','required'=>'required','id'=>'model','onkeypress' => 'error_remove()']) !!}
            </div>
            <div class="col-xs-6">
              <label for="brand_name">Brand Name</label>
              {!! Form::text('brand_name',null, ['class' => 'form-control','placeholder' => 'Brand Name','required'=>'required','id'=>'brand_name','onkeypress' => 'error_remove()']) !!}
            </div>
          </div> 
          <br>
          <div class="row">
            <div class="col-xs-6">
              <label for="year">Year</label>
              {!! Form::text('year',null, ['class' => 'form-control','placeholder' => 'Year','required'=>'required','id'=>'year','onkeypress' => 'error_remove()', 'onkeypress' => "return isNumberKey(event)"]) !!}
            </div>
            <div class="col-xs-6">
              <label for="vehicle_num">Vehicle Number</label>
              {!! Form::text('vehicle_num',null, ['class' => 'form-control','placeholder' => 'Vehicle Number','required'=>'required','id'=>'vehicle_num','onkeypress' => 'error_remove()']) !!}
            </div>
          </div> 
          <br>
          <div class="row">
            <div class="col-xs-6">
              <label for="vehicle_num_img">Vehicle Number Image</label>
              {!! Form::file('vehicle_num_img', ['class' => 'form-control','placeholder' => '','required'=>'required','id'=>'vehicle_num_img','accept'=>'image/*','onkeypress' => 'error_remove()']) !!}
            </div>
            <div class="col-xs-6">
              <label for="licence_num">Licence Number</label>
              {!! Form::text('licence_num',null, ['class' => 'form-control','placeholder' => 'Licence Number','required'=>'required','id'=>'licence_num','onkeypress' => 'error_remove()']) !!}
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-xs-6">
              <label for="licence_img">Licence Image</label>
              {!! Form::file('licence_img',['class' => 'form-control','placeholder' => 'licence_img','required'=>'required','id'=>'licence_img','accept'=>'image/*','onkeypress' => 'error_remove()', 'accept'=>'image/*', 'onkeypress' => "return isNumberKey(event)"]) !!}
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
   
  </div>
{!! Form::close() !!}


  