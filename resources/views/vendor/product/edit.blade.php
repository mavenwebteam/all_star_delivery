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
                for(var instanceName in CKEDITOR.instances)
                CKEDITOR.instances[instanceName].updateElement();
            

                $.ajax({
                    dataType: 'json',
                    type: "POST",
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    url: '{{ route("vendor.menu-manager.update",$productData->id) }}',
                }).done(function( data ) 
                {  
                    error_remove ();
                    if(data.success==false)
                    {
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
                  

                    }else{
                        searchItem();
                        if(data.class == 'success')
                        {     
                            $.LoadingOverlay("hide");  
                            showMsg(data.message, "success");}
                                    
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
        // -------------delete product image --------------
        $(".product_img_delete").on("click", function(){
            var checklogin1 = checklogin();		
            if(checklogin1  == true)
            {	
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
                        url: '{{ URL::to("/vendor/ajax-image-delete") }}',
                        data: {"action": "delete", "id" : delete_id},
                        beforeSend: function(){
                            $.LoadingOverlay("show");
                        },
                        dataType: 'json',
                        context : $this,
                        success: function(response){
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
                }		
            }else
            {
                location.reload();
                $.LoadingOverlay("hide");		
            }  
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
</script>

    {!! Form::open(array('url' => 'vendor/menu-manager/'.$productData->id, 'method' => 'post','name'=>'addUserForm','files' =>'true','novalidate' => 'novalidate','id' => 'addUserForm')) !!}
    @method('PUT')    
    <div class="col-sm-12 p-r-30">
            <div class="panel panel-transparent">
            <div class="panel-body">
                {{-- old image --}}
                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <div class="row form-group">
                        <div class="col-lg-12 col-sm-12 col-xs-12">
                            <div class="image_outer" style="height:175px;">
                                <?php 
                                if(isset($product_image_data) && !empty($product_image_data)){
                                    $count = count($product_image_data);
                                    foreach($product_image_data as $images){ 
                                       
                                         $image= $images->image;
                                          //echo public_path().'/media/products'.'/'.$image ;
                                        if($image &&  file_exists(public_path().'/media/products'.'/'.$image )) { ?>
                                            <span class="image_inner" style="margin:0px 5px 5px 0px;">
                                                <?php if($count > 1){ ?>
                                                    <i class="fa fa-times product_img_delete" data-id="<?php echo $images->id; ?>"></i>
                                                <?php } ?>
                                                <img src="{{asset('media/products/'.$image)}}"  class='imgClass' width='100',height='50'>
                                            </span>
                                       <?php } 
                                    }    
                                } ?>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- ./old image --}}



                <div class="row">
                <div class="col-xs-12">
                    <label for="item_category">{{ __('vendor.item_category') }}<span class="red-star">*</span></label>
                    <select class="form-control select2-selection select2-selection--single" name="item_category_id" id="item_category_id">
                    <option value="">- {{ __('vendor.choose_item_category') }} -</option>
                    @foreach ($itemCategory as $category)
                    <option value="{{ $category->id }}"  @if($productData->item_category_id == $category->id) selected @endif>
                        {{ $category->name_en.' ('.$category->name_burmese.')' }}
                    </option>
                    @endforeach
                    </select>
                </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-xs-12 col-md-12">
                      <label class="ref_id">Ref ID</label>
                      {!! Form::text('ref_id',$productData->ref_id, ['class' => 'form-control','placeholder' => 'Ref ID','required'=>'required','id'=>'ref_id','onkeypress' => 'error_remove()' ]) !!}
                    </div>
                  </div>
                  <br>
                <div class="row">
                <div class="col-xs-6">
                    <label class="name_en">{{ __('vendor.name') }}(en)<span class="red-star">*</span></label>
                    {!! Form::text('name_en',$productData->name_en, ['class' => 'form-control','placeholder' => trans('vendor.name'),'required'=>'required','id'=>'name_en','onkeypress' => 'error_remove()' ]) !!}
                </div>
                <div class="col-xs-6">
                    <label for="name_br">{{ __('vendor.name') }}(br)<span class="red-star">*</span></label>
                    {!! Form::text('name_br',$productData->name_br, ['class' => 'form-control','placeholder' => trans('vendor.name'), 'required'=>'required','id'=>'name_br','onkeypress' => 'error_remove()' ]) !!}
                </div>
                </div>
                <br>
                <div class="row">
                <div class="col-xs-6">
                    <label for="price">{{ trans('vendor.price') }}<span class="red-star">*</span></label>
                    {!! Form::text('price',$productData->price, ['class' => 'form-control','placeholder' => trans('vendor.price'), 'required'=>'required','id'=>'price','onkeypress' => 'error_remove()']) !!}
                </div>
                <div class="col-xs-6">
                    <label for="discount_present">{{ trans('vendor.discount') }}(%)( {{ trans('vendor.if_any') }} )</label>
                    {!! Form::text('discount_present',$productData->discount_present, ['class' => 'form-control','placeholder' => trans('vendor.discount_percent'),'required'=>'required','id'=>'discount_present','onkeypress' => 'error_remove()']) !!}
                </div>
                </div>
                {{-- for grocery only --}}
                
                {{-- @if($businessCategory == '1')  --}}
                <br>
                <div class="row">
                <div class="col-xs-6">
                    <label for="total_qty">{{ trans('vendor.total_qty') }}<span class="red-star">*</span></label>
                    {!! Form::text('total_qty',$productData->total_qty, ['class' => 'form-control','placeholder' => trans('vendor.total_qty'),'required'=>'required','id'=>'total_qty','onkeypress' => 'error_remove()','onkeypress' => "return isNumberKey(event)"]) !!}
                </div>
                <div class="col-xs-6">
                    <label for="available_qty">{{ trans('vendor.available_qty') }}<span class="red-star">*</span></label>
                        {!! Form::text('available_qty',$productData->available_qty, ['class' => 'form-control','placeholder' => trans('vendor.available_qty'),'required'=>'required','id'=>'available_qty','onkeypress' => "return isNumberKey(event)"]) !!}
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
                <label for="size">{{ trans('vendor.size') }}<span class="red-star">*</span></label>
                <input type="text" value="{{$productData->size}}" name="size" class="form-control" id="size" placeholder="Enter size in numeric format" />
                </div>
                <div class="col-xs-6">
                <label for="unit_id">{{ trans('vendor.unit') }}<span class="red-star">*</span></label>
                <select class="form-control select2-selection select2-selection--single" name="unit_id" id="unit_id">
                    <option value="">-{{ trans('vendor.choose_item_unit') }}-</option>
                    @foreach ($units as $unit)
                    <option value="{{ $unit->id }}"  @if($productData->unit_id == $unit->id) selected @endif>{{ $unit->name.' ('.$unit->code.')' }}</option>
                    @endforeach
                </select>
                </div>
                </div>
                <br>
                <div class="row">
                <div class="col-xs-12">
                    <label for="description">{{ trans('vendor.description') }}(en)<span class="red-star">*</span></label>
                    <textarea id="description_en" name="description_en" rows="10" cols="80">{!! $productData->description_en !!}</textarea>
                </div>
                </div>
                <br>
                <div class="row">
                <div class="col-xs-12">
                    <label for="description_br">{{ trans('vendor.description') }}(br)<span class="red-star">*</span></label>
                    <textarea id="description_br" name="description_br" rows="10" cols="80">{!!  $productData->description_br !!}</textarea>
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
                                    <input type="file" name="product_images[]" id="productImgInput" style="display: none;" multiple accept="image/*">
                                    <span>{{ trans('vendor.add_product_images') }}</span>
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
                {!! Form::submit(trans('vendor.save_btn'),['class' => 'btn btn-primary btn-flat subbtn', 'type' => 'submit']) !!}
                {!! Form::submit(trans('vendor.cancel_btn'),['class' => 'btn btn-flat subbtn','data-dismiss'=>'modal']) !!}
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