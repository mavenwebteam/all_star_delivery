<script type="text/javascript">

    $(document).ready(function () 

    {  $.LoadingOverlay("hide");

    });

    </script>

<table class="table table-bordered table-hover">

<tbody><?php $helper=new App\Helpers;?>

        
		<tr> <th>Image</th><td> <div class="image_outer">
                                <?php 
                                if(isset($productimage) && !empty($productimage)){
                                    $count = count($productimage);
                                    foreach($productimage as $images){ 
                                       
                                         $image= $images->image;
                                          //echo public_path().'/media/products'.'/'.$image ;
                                        if($image &&  file_exists(public_path().'/media/products'.'/thumb/'.$image )) { ?>
                                            <span class="image_inner" style="margin:0px 5px 5px 0px;">
                                                <img src="{{asset('media/products/thumb/'.$image)}}"  class='imgClass' width='100',height='100'>
                                            </span>
                                       <?php } 
                                    }    
                                } ?>
                            </div></td>
		</tr>
        <tr> <th>Product Name</th><td>{{$productdata->name}}</td></tr>
		<tr> <th>SKU</th><td>{{$productdata->sku}}</td></tr>
        <tr> <th>Categories</th><td>{{$productdata->catname}}</td></tr>
        <tr> <th>Brand</th><td> {{$productdata->brandsname}}</td></tr>
        <tr> <th>Quantity</th><td> {{$productdata->total_qty}}</td></tr>
		<tr> <th>Minimum Quantity(For out of stock)</th><td>{{$productdata->minimum_quantity}}</td></tr>
		<tr> <th>Stock Status</th><td>@if($productdata->stock == 0) Out Of Stock @else In Stock @endif</td></tr>
		<tr> <th>Price</th><td>{{$productdata->price}}</td></tr>
		<tr> <th>Discount (%)</th><td>{{$productdata->discount_per}}</td></tr>
			<tr> <th>Product Vat</th><td>{{$productdata->product_vat}} %</td></tr>
		<tr> <th>Weight</th><td>{{$productdata->weight}}</td></tr>
		<tr> <th>Weight Unit</th><td>{{$productdata->wname}}</td></tr>
        <tr> <th>Volume</th><td>{{$productdata->volume}}</td></tr>

		<!--<tr> <th>Offer Starts At</th><td>N/A</td></tr>
		<tr> <th>Offer Expires On</th><td>N/A</td></tr>
		<tr> <th>Ratings</th><td>{{$productdata->rating}}</td></tr>-->
		<tr> <th>Product Description</th><td><?php echo $productdata->description; ?></td></tr>
		<tr> <th>Status</th><td>@if($productdata->status == 0) <span class="label label-danger">Deactive</span> @else 
				<span class="label label-success">Active</span> @endif </td></tr>
		<tr> <th>Created At</th><td><?php echo  date('Y-m-d', strtotime($productdata->created_at)); ?></td></tr>
		<tr> <th>Updated At</th><td><?php echo  date('Y-m-d', strtotime($productdata->updated_at)); ?></td></tr>

    </tbody>

               

  </table>

