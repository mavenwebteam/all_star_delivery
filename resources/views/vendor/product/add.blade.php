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
            for (var instanceName in CKEDITOR.instances)
            CKEDITOR.instances[instanceName].updateElement();
              $.ajax({
              dataType: 'json',
              type: "POST",
              data: new FormData(this),
              contentType: false,
              cache: false,
              processData: false,
              url: '{{ route('vendor.menu-manager.store') }}',
            }).done(function(data)
              {
                error_remove();
                if (data.success == false)
                {
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

{!! Form::open(array('route' => 'vendor.menu-manager.store', 'method' => 'post','name'=>'addUserForm','files'=>true,'novalidate' => 'novalidate','id' => 'addUserForm')) !!}

<div class="col-sm-12 p-r-30">
   <div class="panel panel-transparent">
      <div class="panel-body">
        <div class="row">
          <div class="col-xs-12">
            <label for="item_category">Item Category<span class="red-star">*</span></label>
            <select class="form-control select2-selection select2-selection--single" name="item_category_id" id="item_category_id">
              <option value="">-Choose Item Category-</option>
              @foreach ($itemCategory as $category)
              <option value="{{ $category->id }}">{{ $category->name_en.' ('.$category->name_burmese.')' }}</option>
              @endforeach
            </select>
          </div>
        </div>
        <br>
        <div class="row">
          <div class="col-xs-12 col-md-12">
            <label class="ref_id">Ref ID</label>
            {!! Form::text('ref_id',null, ['class' => 'form-control','placeholder' => 'Ref ID','required'=>'required','id'=>'ref_id','onkeypress' => 'error_remove()' ]) !!}
          </div>
        </div>
        <br>
        <div class="row">
          <div class="col-xs-6">
            <label class="name_en">Name(en)<span class="red-star">*</span></label>
            {!! Form::text('name_en',null, ['class' => 'form-control','placeholder' => 'Name(en)','required'=>'required','id'=>'name_en','onkeypress' => 'error_remove()' ]) !!}
          </div>
          <div class="col-xs-6">
            <label for="name_br">Name(br)<span class="red-star">*</span></label>
            {!! Form::text('name_br',null, ['class' => 'form-control','placeholder' => 'Name(br)','required'=>'required','id'=>'name_br','onkeypress' => 'error_remove()' ]) !!}
          </div>
        </div>
        <br>
        <div class="row">
          <div class="col-xs-6">
            <label for="price">Price<span class="red-star">*</span></label>
            {!! Form::text('price',null, ['class' => 'form-control','placeholder' => 'Price','required'=>'required','id'=>'price','onkeypress' => 'error_remove()', 'onkeypress' => "return isNumberKey(event)"]) !!}
          </div>
          <div class="col-xs-6">
            <label for="discount_present">Discount(%)(if any)</label>
            {!! Form::text('discount_present',null, ['class' => 'form-control','placeholder' => 'Discount present','required'=>'required','id'=>'discount_present','onkeypress' => 'error_remove()', 'onkeypress' => "return isNumberKey(event)"]) !!}
          </div>
        </div>
        {{-- for grocery only --}}
       
        {{-- @if($businessCategory == '1')  --}}
        <br>
        <div class="row">
          <div class="col-xs-6">
            <label for="total_qty">Total Qty<span class="red-star">*</span></label>
            {!! Form::text('total_qty',null, ['class' => 'form-control','placeholder' => 'Total Quantity','required'=>'required','id'=>'total_qty','onkeypress' => 'error_remove()','onkeypress' => "return isNumberKey(event)"]) !!}
          </div>
          <div class="col-xs-6">
            <label for="available_qty">Available Qty <span class="red-star">*</span></label>
               {!! Form::text('available_qty',null, ['class' => 'form-control','placeholder' => 'Available Quantity','required'=>'required','id'=>'available_qty','onkeypress' => "return isNumberKey(event)"]) !!}
          </div>
        </div>
        {{-- @else
          <br>
        <div class="row">
          <div class="col-xs-6">
            {!! Form::hidden('total_qty',1, ['class' => 'form-control','placeholder' => 'Total Quantity','required'=>'required','id'=>'total_qty','onkeypress' => 'error_remove()','onkeypress' => "return isNumberKey(event)"]) !!}
          </div>
          <div class="col-xs-6">
               {!! Form::hidden('available_qty',1, ['class' => 'form-control','placeholder' => 'Available Quantity','required'=>'required','id'=>'available_qty','onkeypress' => "return isNumberKey(event)"]) !!}
          </div>
        </div>
        @endif --}}
        <br>
        <div class="row">
        <div class="col-xs-6">
          <label for="size">Size<span class="red-star">*</span></label>
          <input type="text" name="size" class="form-control" id="size" placeholder="Enter size in numeric format" />
        </div>
        <div class="col-xs-6">
          <label for="unit_id">Unit<span class="red-star">*</span></label>
          <select class="form-control select2-selection select2-selection--single" name="unit_id" id="unit_id">
            <option value="">-Choose Item Unit-</option>
            @foreach ($units as $unit)
            <option value="{{ $unit->id }}">{{ $unit->name.' ('.$unit->code.')' }}</option>
            @endforeach
          </select>
        </div>
        </div>
        <br>
        <div class="row">
          <div class="col-xs-12">
            <label for="description">Description(en)<span class="red-star">*</span></label>
            <textarea id="description_en" name="description_en" rows="10" cols="80"></textarea>
          </div>
        </div>
        <br>
        <div class="row">
          <div class="col-xs-12">
            <label for="description_br">Description(br)<span class="red-star">*</span></label>
            <textarea id="description_br" name="description_br" rows="10" cols="80"></textarea>
          </div>
        </div>
        {{-- image --}}
        <br>
        <div class="col-12 col-sm-12 col-md-12">
          <div class="row form-group">
             <div class="col-12 col-sm-12 col-md-12 ">
                <span style="color:red">Note*: Please Upload jpg, jpeg, png formats only. Dimensions : 512 X 512</span>
                <div class="all_img_outer">
                   <div class="no_image">
                      <a href="javascript:"><img id="productImg" src="{{asset('media/image_add.png')}}"></a>
                      <input type="file" name="product_images[]" id="product_images" style="display: none;" multiple>
                      <span>Add Images</span>
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
      <div class="col-md-12">
          {!! Form::submit('Save',['class' => 'btn btn-primary btn-flat subbtn', 'type' => 'submit']) !!}
          {!! Form::submit('Cancel',['class' => 'btn btn-flat subbtn','data-dismiss'=>'modal']) !!}
      </div>
    </div>
    <script src="{{asset('/admin_assets/bower_components/ckeditor/ckeditor.js')}}"></script>
    <script>
      $(function() {
      
        // Replace the <textarea id="editor1"> with a CKEditor
      
        // instance, using default configuration.
      
        CKEDITOR.replace('description_en')
        CKEDITOR.replace('description_br')
      
        //bootstrap WYSIHTML5 - text editor
      
        $('.textarea').wysihtml5()
      
      });
    </script>
  </div>
{!! Form::close() !!}



  