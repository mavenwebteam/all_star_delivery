<script type="text/javascript">
  function GetSubCategory(id)
  {
    $.ajaxSetup({
      headers: { 'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') }
    });
    $.ajax({
      data: { id: id},
      type: "POST",
      url: '{{ URL::to('/sub-admin/get-ajax-subcategory') }}',
    }).done(function(data)
      {
        search();
        document.getElementById("sub_cat_id").innerHTML = data;
      });
  }
function GetCategory(id)
  {
		var checklogin1 = checklogin();
		if(checklogin1  == true){	
 
    $.ajaxSetup({
      headers: { 'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') }
    });
    $.ajax({
      data: { id: id},
      type: "POST",
      url: '{{ URL::to('/sub-admin/get-ajax-category') }}',
    }).done(function(data)
      {
        search();
        document.getElementById("cat_id").innerHTML = data;
      });
	 
		}else{
			location.reload();
				$.LoadingOverlay("hide");
		}   
  }
	
  $(document).ready(function()
    {
      $.LoadingOverlay("hide");
      $('#addUserForm').on('submit', function(e)
        {   
		$.LoadingOverlay('show');
			var checklogin1 = checklogin();
		if(checklogin1  == true){	
          e.preventDefault();
          $.ajaxSetup({
            headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')  }
          });
          for (var instanceName in CKEDITOR.instances)
          CKEDITOR.instances[instanceName].updateElement();
            $.ajax({
            dataType: 'json',
            type: "POST",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            url: '{{ URL::to('/sub-admin/add-product-post') }}',
          }).done(function(data)
            {
              error_remove();
              if (data.success == false)
              {
                  $.each(data.errors, function(key, value) {

                    if(key=='product_images')
                    {
                      $('.all_img_outer').closest('.form-group').addClass('has-error');
                    $('<div class="jquery-validate-error help-block animated fadeInDown">' + value + '</div>').insertAfter($('.all_img_outer'));
                    }else{
						$('#' + key).closest('.form-group').addClass('has-error');
                    $('<div class="jquery-validate-error help-block animated fadeInDown">' + value + '</div>').insertAfter($('#' + key));
					}
                    
                  });
              } 
              else 
              {
                    search();
                    if (data.class == 'success')
                    { $.LoadingOverlay('hide');
                      showMsg(data.message, "success");
                    }
                    $("#myModal").modal('hide');
                    return false;
              }
			  $.LoadingOverlay('hide');
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
  $(document).ready(function () { 
    		$( "#store_id" ).change(function () {
    			var store_id = $(this).val();
    				$.ajax({
    					url: "{{url('sub-admin/getoutlet') }}" + '/'+store_id,
    					success: function(data) {
    						$('#outlet_id').prop('disabled', false);
    						$('#outlet_id').html('');
    						$('#outlet_id').html(data);		
    					}
    				});
    		});
			
			$( ".store_id" ).change(function () { 
    			var store_id = $(this).val();
    				$.ajax({
    					url: "{{url('sub-admin/getcategory') }}" + '/'+store_id,
    					success: function(data) {
    						$('#cat_id').prop('disabled', false);
    						$('#cat_id').html('');
    						$('#cat_id').html(data);		
    					}
    				});
    		});
			$( "#cat_id" ).change(function () { 
    			var cat_id = $(this).val(); 
    				$.ajax({
    					url: "{{url('sub-admin/getbrand') }}" + '/'+cat_id,
    					success: function(data) {
    						$('#brand_id').prop('disabled', false);
    						$('#brand_id').html('');
    						$('#brand_id').html(data);		
    					}
    				});
    		});
        
    	});
		function isNumberKey(evt){
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}
</script>



{!! Form::open(array('url' => '/sub-admin/add-product-post', 'method' => 'post','name'=>'addUserForm','files'=>true,'novalidate' => 'novalidate','id' => 'addUserForm')) !!}

<div class="col-sm-12 p-r-30">

  <div class="panel form-horizontal panel-transparent">

    <div class="panel-body">

      <div class="row">

								<div class="alert alert-danger" style="display:none"></div>
								<div class="col-12 col-sm-12 col-md-6">

<div class="row form-group">

		<label class="col-lg-4 col-sm-6 col-xs-12 control-label">Select Store<span class="red-star">*</span></label>

		<div class="col-lg-8 col-sm-6 col-xs-12">

				<?php $helper = new App\Helpers; ?>

				{!! Form::select('store_id',$helper->GetStoreList(),null, ['class' => 'form-control store_id','required'=>'required','id'=>'store_id','onChange' => 'error_remove()' ]) !!}

		</div>



</div>

</div>
<div class="col-12 col-sm-12 col-md-6">
                        <div class="row form-group">
                            <label class="col-lg-4 col-sm-6 col-xs-12 control-label">Select Outlet<span class="red-star">*</span></label>
                            <div class="col-lg-8 col-sm-6 col-xs-12">
                          
                          
							
												{{ Form::select(
						 'outlet_id',
						 [null => 'Please Select Outlet'],
						 '',
						 ['id' => 'outlet_id','class'=>'form-control','disabled'=>'true']
						) 
					}}
							
							
                            </div>
                            
                        </div>
                    </div>
</div>
  <div class="row">
        <div class="col-12 col-sm-12 col-md-6">

          <div class="row form-group">

            <label class="col-lg-4 col-sm-6 col-xs-12 control-label">Product SKU<span class="red-star">*</span></label>

            <div class="col-lg-8 col-sm-6 col-xs-12">

              {!! Form::text('sku',null, ['class' => 'form-control','placeholder' => 'SKU','required'=>'required','id'=>'sku','onkeypress' => 'error_remove()' ]) !!}

            </div>



          </div>

        </div>

        <div class="col-12 col-sm-12 col-md-6">

          <div class="row form-group">

            <label class="col-lg-4 col-sm-6 col-xs-12 control-label">Product Name<span class="red-star">*</span></label>

            <div class="col-lg-8 col-sm-6 col-xs-12">

              {!! Form::text('name',null, ['class' => 'form-control','placeholder' => 'Product Name','required'=>'required','id'=>'name','onkeypress' => 'error_remove()' ]) !!}

            </div>



          </div>

        </div>
</div>
        <!-- <div class="col-12 col-sm-12 col-md-6">

          <div class="row form-group">

            <label class="col-lg-4 col-sm-6 col-xs-12 control-label">Select Vendor<span class="red-star">*</span></label>

            <div class="col-lg-8 col-sm-6 col-xs-12">

              <?php $helper = new App\Helpers; ?>

              {!! Form::select('vendor_id',$helper->GetVendorList(),null, ['class' => 'form-control','required'=>'required','id'=>'vendor_id','onChange' => 'error_remove()' ]) !!}

            </div>



          </div>

        </div> -->

         <div class="row">

       
 <div class="col-12 col-sm-12 col-md-6">

          <div class="row form-group">

            <label class="col-lg-4 col-sm-6 col-xs-12 control-label">Product Category<span class="red-star">*</span></label>
            <div class="col-lg-8 col-sm-6 col-xs-12">
              <?php $helper = new App\Helpers; ?>
              <select id="cat_id" name="cat_id" class="form-control" disabled>
                <option value="">Select Category</option>
              </select>
            </div>
          </div>
        </div>
		
		 <div class="col-12 col-sm-12 col-md-6">

          <div class="row form-group">

            <label class="col-lg-4 col-sm-6 col-xs-12 control-label">Select Brands<span class="red-star">*</span></label>

            <div class="col-lg-8 col-sm-6 col-xs-12">

              <?php $helper = new App\Helpers; ?>
              <select id="brand_id" name="brand_id" class="form-control" disabled>
                <option value="">Select Brand</option>
              </select>
            </div>



          </div>

        </div>
		
</div>  
  <div class="row">
  <div class="col-12 col-sm-12 col-md-6">

                        <div class="row form-group">

                            <label class="col-lg-4 col-sm-6 col-xs-12 control-label">Price<span class="red-star">*</span></label>

                            <div class="col-lg-8 col-sm-6 col-xs-12">

                            {!! Form::text('price',null, ['class' => 'form-control','placeholder' => 'Product Price','required'=>'required','id'=>'price','onkeypress' => 'error_remove()' ]) !!}

                            </div>

                            

                        </div>

                    </div>
   
					<div class="col-12 col-sm-12 col-md-6">

                        <div class="row form-group">

                            <label class="col-lg-4 col-sm-6 col-xs-12 control-label">Discount (%)</label>

                            <div class="col-lg-8 col-sm-6 col-xs-12">

                            {!! Form::text('discount_per',null, ['class' => 'form-control','placeholder' => 'Discount (%)','required'=>'required','id'=>'discount_per','onkeypress' => 'error_remove()' ]) !!}

                            </div>

                            

                        </div>

                    </div>
					
  </div>


<div class="row">
       <!-- <div class="col-12 col-sm-12 col-md-6">

          <div class="row form-group">

            <label class="col-lg-4 col-sm-6 col-xs-12 control-label">Product SubCategory</label>

            <div class="col-lg-8 col-sm-6 col-xs-12">

              <?php $helper = new App\Helpers; ?>

              <select id="sub_cat_id" name="sub_cat_id" class="form-control">

                <option value="">Select SubCategory</option>

              </select>

            </div>



          </div>

        </div>-->
		 <div class="col-12 col-sm-12 col-md-6">

                        <div class="row form-group">

                            <label class="col-lg-4 col-sm-6 col-xs-12 control-label">Weight<span class="red-star">*</span></label>

                            <div class="col-lg-8 col-sm-6 col-xs-12">

                            {!! Form::text('weight',null, ['class' => 'form-control','placeholder' => 'Weight','required'=>'required','id'=>'weight','onkeypress' => 'error_remove()','onkeypress' => "return isNumberKey(event)" ]) !!}

                            </div>

                            

                        </div>

                    </div>
 <div class="col-12 col-sm-12 col-md-6">
                        <div class="row form-group">
                            <label class="col-lg-4 col-sm-6 col-xs-12 control-label">Weight Unit<span class="red-star">*</span></label>

                            <div class="col-lg-8 col-sm-6 col-xs-12">
							 {!! Form::select('weight_unit',$weight_box ,null, ['class' => 'form-control','onkeypress' => 'error_remove()','id'=>'weight_unit']) !!}

                            </div>
                        </div>
                    </div>
					
                  </div>
                  <div class="col-12 col-sm-12 col-md-6">

            <div class="row form-group">

              <label class="col-lg-4 col-sm-6 col-xs-12 control-label">Volume</label>

              <div class="col-lg-8 col-sm-6 col-xs-12">
                {!! Form::text('volume',null, ['class' => 'form-control','placeholder' => 'Volume','required'=>'required','id'=>'volume','onkeypress' => 'error_remove()','onkeypress' => "return isNumberKey(event)"  ]) !!}
              </div>
            </div>

            </div>
				  <div class="col-12 col-sm-12 col-md-6">

          <div class="row form-group">

            <label class="col-lg-4 col-sm-6 col-xs-12 control-label">Minimum Quantity(For out of stock)</label>

            <div class="col-lg-8 col-sm-6 col-xs-12">
              {!! Form::text('minimum_quantity',null, ['class' => 'form-control','placeholder' => 'Minimum Quantity','required'=>'required','id'=>'minimum_quantity','onkeypress' => 'error_remove()','onkeypress' => "return isNumberKey(event)"  ]) !!}
            </div>
          </div>

        </div>
 <div class="col-12 col-sm-12 col-md-6">
                        <div class="row form-group">
                            <label class="col-lg-4 col-sm-6 col-xs-12 control-label">Select Product Vat<span class="red-star">*</span></label>
                            <div class="col-lg-8 col-sm-6 col-xs-12">
                           <?php $helper=new App\Helpers;?>
            {!! Form::select('product_vat',$helper->GetProductVat(),null, ['class' => 'form-control','id'=>'product_vat','onkeypress' => 'error_remove()']) !!}
							
                            </div>
                            
                        </div>
                    </div>
		  <div class="row">
        <div class="col-12 col-sm-12 col-md-12">

          <div class="row form-group">

            <label class="col-lg-2 col-sm-6 col-xs-12 control-label">Description<span class="red-star">*</span></label>

            <div class="col-lg-10 col-sm-6 col-xs-12">

              <textarea id="description" name="description" rows="10" cols="80"></textarea>

            </div>

          </div>

        </div>
</div>
 <div class="row">
        <div class="col-12 col-sm-12 col-md-12">

          <div class="row form-group">

            <div class="col-12 col-sm-12 col-md-12 ">
<span style="color:red">Note*: Please Upload jpg, jpeg, png formats only. Dimensions : 512 * 512</span>
              <div class="all_img_outer">

                <div class="no_image">

                  <a href="javascript:"><img id="productImg" src="{{asset('media/image_add.png')}}"></a>

                  <input type="file" name="product_images[]" id="product_images" style="display: none;" multiple>

                  <span>Add Product Images</span>

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

      CKEDITOR.replace('description')

      //bootstrap WYSIHTML5 - text editor

      $('.textarea').wysihtml5()

    })
  </script>

  {!! Form::close() !!}