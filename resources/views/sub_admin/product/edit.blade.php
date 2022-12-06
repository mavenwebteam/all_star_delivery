<script type="text/javascript">


function GetSubCategory(id) 
    {			
      $.ajaxSetup({
                  headers: {
                      'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                  }
              });
      $.ajax({
            
            data: { id:id}, 
            type: "POST",
            url: '{{ URL::to('/sub-admin/get-ajax-subcategory') }}',
        }).done(function(data) 
        {   
          search();
          ///alert(data);
          document.getElementById("sub_cat_id").innerHTML=data;
          
        });
        
    }
    $(document).ready(function () 
    { $.LoadingOverlay("hide");
        $( '#addUserForm' ).on( 'submit', function(e) 
        {    $.LoadingOverlay('show');			var checklogin1 = checklogin();		if(checklogin1  == true){	
            e.preventDefault();
               $.ajaxSetup({
                  headers: {
                      'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                  }
              });
              for(var instanceName in CKEDITOR.instances)
            CKEDITOR.instances[instanceName].updateElement();
           

            $.ajax({
           dataType: 'json',

            type: "POST",
            data:  new FormData(this),
            contentType: false,
            cache: false,
            processData:false,
            url: '{{ URL::to('/sub-admin/edit-product-post') }}',
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
            {  $.LoadingOverlay('hide');  showMsg(data.message, "success");}
                        
            $("#myModal").modal('hide');
            
	            return false;
          }
            $.LoadingOverlay('hide');						
                         
        });
		}else{			location.reload();				$.LoadingOverlay("hide");		}  
    });

    $(".product_img_delete").on("click", function(){			var checklogin1 = checklogin();			if(checklogin1  == true){	
            var $this = $(this);
            var delete_id = $(this).data("id");
            if(delete_id) {
                $.ajaxSetup({
                  headers: {
                      'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                  }
              });
                $.ajax({
                    type: 'POST',
                    url: '{{ URL::to('/sub-admin/ajax-image-delete') }}',
                    data: {"action": "delete", "id" : delete_id},
                    beforeSend: function(){
                        $.LoadingOverlay("show");
                    },
                    dataType: 'json',
                    context : $this,
                    success: function(response){
                    
                       // $('#product_product_subcategory_id').html(html);
                        $.LoadingOverlay("hide");
                        if(response.class == 'success'){
                            $this.parent('.image_inner').remove();
                            showMsg(response.message, "success");
                        }
                        else{
                            showMsg(response.message, "danger");
                        }
                        return false;
                    }
                });
            }								}else{			location.reload();			$.LoadingOverlay("hide");		}  
        });
});


function fileInputChange(aFiles) {

var newFiles = []; var files = [];
for (var index = 0; index < aFiles.length; index++) {
    var file = aFiles[index];
    var re = /(?:\.([^.]+))?$/;
    ext = re.exec(file.name)[1];

    if (ext == 'jpg' || ext == 'jpeg' || ext == 'png' || ext == 'PNG'|| ext == 'JPG'|| ext == 'JPEG') {

        newFiles.push(file);
        files.push(file);
    } else {
        alert("Please Upload jpg, jpeg, png formats only.");
        $('html, body').animate({scrollTop: 0}, 1000);
    }
}

newFiles.forEach(function (file) {
    var reader = new FileReader();

    reader.addEventListener("load", function () {

        var ImgElement = $('<div></div>')
            .addClass('choose_img').append($('<img />').attr("src", this.result)).append($('<span></span>').addClass("close").append($("<i></i>").addClass("fa fa-times")));
        $(".no_image").hide();
        $('.all_img_outer').append(ImgElement);

    }, false);

    reader.readAsDataURL(file);
});
}



var productImg =  $("#productImg");
var productImgInput = $("#productImgInput");

productImgInput.change(function () {

fileInputChange(productImgInput[0].files);
});

productImg.click(function () {
productImgInput.click();
});

$('body').on('click', '.close', function () {

$(this).parent("div.choose_img").remove();
if($('.choose_img').length) {
    $(".no_image").hide();
} else {
    $(".no_image").show();
}
});

var obj = $(".all_img_outer");
obj.on('dragenter', function (e)
{
e.stopPropagation();
e.preventDefault();
$(this).css('border', '2px solid #2d73a1');
});

obj.on('dragover', function (e)
{
e.stopPropagation();
e.preventDefault();
});

obj.on('drop', function (e) {
$(this).css('border', '2px dashed #2d73a1');
e.preventDefault();
var files = e.originalEvent.dataTransfer.files;
$("#productImgInput").prop('files', files);
fileInputChange(files);
});
function isNumberKey(evt){
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}

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
</script>

    {!! Form::open(array('url' => '/sub-admin/add-product-post', 'method' => 'post','name'=>'addUserForm','files'=>true,'novalidate' => 'novalidate','id' => 'addUserForm')) !!}
    <div class="col-sm-12 p-r-30">
        <div class="panel form-horizontal panel-transparent">
            <div class="panel-body">
                <div class="row">
                <div class="alert alert-danger" style="display:none"></div>
                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <div class="row form-group">
                        <div class="col-lg-12 col-sm-12 col-xs-12">
                            <div class="image_outer">
                                <?php 
                                if(isset($product_image_data) && !empty($product_image_data)){
                                    $count = count($product_image_data);
                                    foreach($product_image_data as $images){ 
                                       
                                         $image= $images->image;
                                          //echo public_path().'/media/products'.'/'.$image ;
                                        if($image &&  file_exists(public_path().'/media/products'.'/thumb/'.$image )) { ?>
                                            <span class="image_inner" style="margin:0px 5px 5px 0px;">
                                                <?php if($count > 1){ ?>
                                                    <i class="fa fa-times product_img_delete" data-id="<?php echo $images->id; ?>"></i>
                                                <?php } ?>
                                                <img src="{{asset('media/products/thumb/'.$image)}}"  class='imgClass' width='100',height='100'>
                                            </span>
                                       <?php } 
                                    }    
                                } ?>
                            </div>
                        </div>
                    </div>
                </div>
				</div>
				 <div class="row">
                <div class="col-12 col-sm-12 col-md-6">
                        <div class="row form-group">
                            <label class="col-lg-4 col-sm-6 col-xs-12 control-label">Select Store<span class="red-star">*</span></label>
                            <div class="col-lg-8 col-sm-6 col-xs-12">
                            <?php $helper=new App\Helpers;?>
                            {!! Form::select('store_id',$helper->GetStoreList(),$productdata->store_id, ['class' => 'form-control store_id','required'=>'required','id'=>'store_id','onChange' => 'error_remove()' ]) !!}
                            </div>
                            
                        </div>
                    </div>
					<div class="col-12 col-sm-12 col-md-6">
                        <div class="row form-group">
                            <label class="col-lg-4 col-sm-6 col-xs-12 control-label">Select Outlet</label>
                            <div class="col-lg-8 col-sm-6 col-xs-12">
                          
                          
					
					{{ Form::select(
						 'outlet_id',
						 [null => 'Please Select Outlet']+ $outletList ,
						 isset($productdata->outlet_id) ? $productdata->outlet_id :'',
						 ['id' => 'outlet_id','class'=>'form-control']
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
                            {!! Form::hidden('id',base64_encode($productdata->id),['id'=>'id']) !!}

                            {!! Form::text('sku',$productdata->sku, ['class' => 'form-control','placeholder' => 'SKU','required'=>'required','id'=>'sku','onkeypress' => 'error_remove()' ]) !!}
                            </div>
                            
                        </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-6">
                        <div class="row form-group">
                            <label class="col-lg-4 col-sm-6 col-xs-12 control-label">Product Name<span class="red-star">*</span></label>
                            <div class="col-lg-8 col-sm-6 col-xs-12">
                            {!! Form::text('name',$productdata->name, ['class' => 'form-control','placeholder' => 'Product Name','required'=>'required','id'=>'name','onkeypress' => 'error_remove()' ]) !!}
                            </div>
                            
                        </div>
                    </div>
					</div>
                <!-- <div class="col-12 col-sm-12 col-md-6">
                        <div class="row form-group">
                            <label class="col-lg-4 col-sm-6 col-xs-12 control-label">Select Vendor<span class="red-star">*</span></label>
                            <div class="col-lg-8 col-sm-6 col-xs-12">
                            <?php $helper=new App\Helpers;?>
                            {!! Form::select('vendor_id',$helper->GetVendorList(),$productdata->vendor_id, ['class' => 'form-control','required'=>'required','id'=>'vendor_id','onChange' => 'error_remove()' ]) !!}
                            </div>
                            
                        </div>
                    </div> -->
                  <div class="row">
                 
					  
					</div>
					<div class="row">
					<div class="col-12 col-sm-12 col-md-6">
                        <div class="row form-group">
                            <label class="col-lg-4 col-sm-6 col-xs-12 control-label">Price<span class="red-star">*</span></label>
                            <div class="col-lg-8 col-sm-6 col-xs-12">
                          
                            {!! Form::text('price',$productdata->price, ['class' => 'form-control','placeholder' => 'Product Price','required'=>'required','id'=>'price','onkeypress' => 'error_remove()' ]) !!}
                            </div>
                            
                        </div>
                    </div>
					<div class="col-12 col-sm-12 col-md-6">
                        <div class="row form-group">
                            <label class="col-lg-4 col-sm-6 col-xs-12 control-label">Discount (%)</label>
                            <div class="col-lg-8 col-sm-6 col-xs-12">
                            {!! Form::text('discount_per',$productdata->discount_per, ['class' => 'form-control','placeholder' => 'Discount (%)','required'=>'required','id'=>'discount_per','onkeypress' => 'error_remove()' ]) !!}
                            </div>
                            
                        </div>
                    </div>
					</div>
					 <div class="row">
                    <!--<div class="col-12 col-sm-12 col-md-6">
                        <div class="row form-group">
                            <label class="col-lg-4 col-sm-6 col-xs-12 control-label">Product SubCategory</label>
                            <div class="col-lg-8 col-sm-6 col-xs-12">
                            <?php $helper=new App\Helpers;?>
                            {!! Form::select('sub_cat_id',$subcatedata_select_box,$productdata->sub_cat_id, ['class' => 'form-control','required'=>'required','id'=>'sub_cat_id','onchange' => '' ]) !!}

                            </div>
                            
                        </div>
                    </div>-->
					 <div class="col-12 col-sm-12 col-md-6">
                        <div class="row form-group">
                            <label class="col-lg-4 col-sm-6 col-xs-12 control-label">Product Category<span class="red-star">*</span></label>
                            <div class="col-lg-8 col-sm-6 col-xs-12">
                            <?php $helper=new App\Helpers;?>
                            {!! Form::select('cat_id',[null => 'Please Select Category']+$categoryList,$productdata->cat_id, ['class' => 'form-control','required'=>'required','id'=>'cat_id' ]) !!}
                            </div>
                            
                        </div>
                    </div>
					 <div class="col-12 col-sm-12 col-md-6">
                        <div class="row form-group">
                            <label class="col-lg-4 col-sm-6 col-xs-12 control-label">Select Brands<span class="red-star">*</span></label>
                            <div class="col-lg-8 col-sm-6 col-xs-12">
                            <?php $helper=new App\Helpers;?>
                            {!! Form::select('brand_id',[null => 'Please Select Brand']+$bList,$productdata->brand_id, ['class' => 'form-control','required'=>'required','id'=>'brand_id','onChange' => 'error_remove()']) !!}
                            </div>
                            
                        </div>
                    </div>
					
                   </div>
				    <div class="row">
					 <div class="col-12 col-sm-12 col-md-6">
                        <div class="row form-group">
                            <label class="col-lg-4 col-sm-6 col-xs-12 control-label">Weight<span class="red-star">*</span></label>
                            <div class="col-lg-8 col-sm-6 col-xs-12">
                            {!! Form::text('weight',$productdata->weight, ['class' => 'form-control','placeholder' => 'Weight','required'=>'required','id'=>'weight','onkeypress' => 'error_remove()','onkeypress' => "return isNumberKey(event)"  ]) !!}
                            </div>
                            
                        </div>
                    </div>
					<div class="col-12 col-sm-12 col-md-6">
                        <div class="row form-group">
                            <label class="col-lg-4 col-sm-6 col-xs-12 control-label">Weight Unit<span class="red-star">*</span></label>

                            <div class="col-lg-8 col-sm-6 col-xs-12">
							 {!! Form::select('weight_unit',$weight_box ,$productdata->weight_unit, ['class' => 'form-control','onkeypress' => 'error_remove()','id'=>'weight_unit']) !!}

                            </div>
                        </div>
                    </div>
					 
					</div>
                    <div class="col-12 col-sm-12 col-md-6">

                    <div class="row form-group">

                    <label class="col-lg-4 col-sm-6 col-xs-12 control-label">Volume</label>

                    <div class="col-lg-8 col-sm-6 col-xs-12">
                        {!! Form::text('volume',$productdata->volume, ['class' => 'form-control','placeholder' => 'Volume','required'=>'required','id'=>'volume','onkeypress' => 'error_remove()','onkeypress' => "return isNumberKey(event)"  ]) !!}
                    </div>
                    </div>

                    </div>
					 <div class="col-12 col-sm-12 col-md-6">
                        <div class="row form-group">
                            <label class="col-lg-4 col-sm-6 col-xs-12 control-label">Minimum Quantity(For out of stock)</label>
                            <div class="col-lg-8 col-sm-6 col-xs-12">
                            {!! Form::hidden('id',base64_encode($productdata->id),['id'=>'id']) !!}

                            {!! Form::text('minimum_quantity',$productdata->minimum_quantity, ['class' => 'form-control','placeholder' => 'Minimum Quantity','required'=>'required','id'=>'minimum_quantity','onkeypress' => 'error_remove()','onkeypress' => "return isNumberKey(event)"  ]) !!}
                            </div>
                            
                        </div>
                    </div>
					<div class="col-12 col-sm-12 col-md-6">
                        <div class="row form-group">
                            <label class="col-lg-4 col-sm-6 col-xs-12 control-label">Select Product Vat<span class="red-star">*</span></label>
                            <div class="col-lg-8 col-sm-6 col-xs-12">
                           <?php $helper=new App\Helpers;?>
            {!! Form::select('product_vat',$helper->GetProductVat(),$productdata->product_vat, ['class' => 'form-control','id'=>'product_vat','onkeypress' => 'error_remove()']) !!}
							
                            </div>
                            
                        </div>
                    </div>
					 <div class="row">
                    <div class="col-12 col-sm-12 col-md-12">
                            <div class="row form-group">
                                <label class="col-lg-2 col-sm-6 col-xs-12 control-label">Description<span class="red-star">*</span></label>
                                <div class="col-lg-10 col-sm-6 col-xs-12">
                                    <textarea id="description" name="description" rows="10" cols="80">{{$productdata->description}}</textarea>
                                </div>
                            </div>
                        </div>
						</div>
						 <div class="row">
                        <div class="col-12 col-sm-12 col-md-12">
                        <div class="row form-group">
                            <div class="col-12 col-sm-12 col-md-12 ">
                                <div class="all_img_outer">
                                    <div class="no_image">
                                        <a href="javascript:"><img id="productImg" src="{{asset('media/image_add.png')}}"></a>
                                        <input type="file" name="product_images[]" id="productImgInput" style="display: none;" multiple>
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
        {!! Form::submit('Update',['class' => 'btn btn-primary btn-flat subbtn', 'type' => 'submit']) !!}
        {!! Form::submit('Cancel',['class' => 'btn btn-flat subbtn','data-dismiss'=>'modal']) !!}
        </div>
    </div>
    <script src="{{asset('/admin_assets/bower_components/ckeditor/ckeditor.js')}}"></script>

<script>
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
        
    	});
$(function () {
// Replace the <textarea id="editor1"> with a CKEditor
// instance, using default configuration.
CKEDITOR.replace('description')
//bootstrap WYSIHTML5 - text editor
$('.textarea').wysihtml5()
})
</script>
    {!! Form::close() !!}
