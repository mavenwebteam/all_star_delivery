<script type="text/javascript">

    $(document).ready(function () 

    {  $.LoadingOverlay("hide");

    });

    </script>

<table class="table table-bordered table-hover">

<tbody><?php $helper=new App\Helpers;?>

        
		
        <tr> <th>Product Name</th><td>{{$productinventoriesdata->name}}</td></tr>
		<!--<tr> <th>Vendor Name</th><td>{{$productinventoriesdata->vendorname}}</td></tr>-->
		<tr> <th>SKU</th><td>{{$productinventoriesdata->sku}}</td></tr>
        <tr> <th>Quantity</th><td> {{$productinventoriesdata->qty}}</td></tr>
		<!--<tr> <th>Price</th><td>{{$productinventoriesdata->price}}</td></tr>
		<tr> <th>Offer Price</th><td>{{$productinventoriesdata->discount_price}}</td></tr>
		<tr> <th>Weight</th><td>{{$productinventoriesdata->weight}}</td></tr>
		<tr> <th>Weight Unit</th><td>{{$productinventoriesdata->weight_unit_name}}</td></tr>
		<tr> <th>Offer Starts At</th><td> <?php if(!empty($productinventoriesdata->offer_start_at)){ echo  date('Y-m-d', strtotime($productinventoriesdata->offer_start_at));  } ?> </td></tr>
		<tr> <th>Offer Expires On</th><td><?php if($productinventoriesdata->offer_start_at) { echo  date('Y-m-d', strtotime($productinventoriesdata->offer_start_on)); } ?>   </td></tr>-->
		<tr> <th>Created At</th><td><?php echo  date('Y-m-d', strtotime($productinventoriesdata->created_at)); ?></td></tr>
		<tr> <th>updated At</th><td><?php echo  date('Y-m-d', strtotime($productinventoriesdata->updated_at)); ?></td></tr>

    </tbody>

               

  </table>

