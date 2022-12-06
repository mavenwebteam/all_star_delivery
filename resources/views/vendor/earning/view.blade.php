<script type="text/javascript">
    $(document).ready(function () 
    {  $.LoadingOverlay("hide");
    });
</script>

<table class="table table-bordered table-hover">
    <tbody>

        <tr> <th>Order Id</th><td>{{$orderdata->id}}</td></tr>
		<tr> <th>Customer Name</th><td>{{$orderdata->username}}</td></tr>
        <tr> <th>Products</th><td> <ul>
			<?php $helper=new App\Helpers;
			if(!empty($helper->SelectProductItem($orderdata->id))){ 
					$productdata = $helper->SelectProductItem($orderdata->id); ?>
				@foreach($productdata as $product)
				<li>{{$product->productname}}({{$product->quantity}}) </li>
				@endforeach
			<?php } ?>
			</ul></td>
		</tr>
        <tr> <th>Categories</th><td> {{$orderdata->catname}}</td></tr>
        <tr> <th>Brand</th><td> {{$orderdata->brandname}}</td></tr>
		
		<tr> <th>Transaction Id</th><td>{{$orderdata->transaction_id}}</td></tr>
		
		<tr> <th>Total Amount ($)</th><td>{{$orderdata->total_amount}}</td></tr>
		<tr> <th>Shipping Charge ($)</th><td>{{$orderdata->total_shipping_amount}}</td></tr>
		<tr> <th>VAT Tax ($)</th><td>{{$orderdata->vat_tax ? $orderdata->vat_tax : '0.00'}}</td></tr>
		
		<tr> <th>Wallet Amount ($)</th><td>{{$orderdata->wallet_amount ? $orderdata->wallet_amount : '0.00'}}</td></tr>
		<tr> <th>Net Amount ($)</th><td>{{$orderdata->net_amount}}</td></tr>
		<tr> <th>Return Amount ($)</th><td>{{$orderdata->cancel_amount ? $orderdata->cancel_amount : '0.00'}}</td></tr>
		<tr> <th>Total Quantity</th><td>{{$orderdata->sumquantity}}</td></tr>
		<!--<tr> <th>Return Quantity</th><td></td></tr>-->
		<tr> <th>Delivery Address</th><td>{{$orderdata->order_address}}
		 </td></tr>
		<!--<tr> <th>Deliver To</th><td><?php //echo  date('Y-m-d', strtotime($productdata->created_at)); ?></td></tr>
		<tr> <th>Deliver Contact Number</th><td><?php //echo  date('Y-m-d', strtotime($productdata->created_at)); ?></td></tr>-->
		<tr> <th>Payment Mode</th><td>@if($orderdata->payment_mode == 1) {{'COD'}} @elseif($orderdata->payment_mode == 2){{'Card'}} @else {{"Wallet"}} @endif</td></tr>
		<tr> <th>Is Cancelled</th><td>@if($orderdata->is_cancelled == 1) {{'Yes'}}  @else {{"No"}} @endif</td></tr>
		<tr> <th>Delivery Boy Name</th><td>{{$orderdata->dbname}}</td></tr>
		<tr> <th>Delivery Time</th><td>{{$orderdata->delivery_time}}</td></tr>
		
		<tr> <th>Delivery Type</th><td>{{$orderdata->delivery_type}}</td></tr>
		<tr> <th>Order Delivery Status</th><td>@if($orderdata->order_delivery_status == 0) sent to store @elseif($orderdata->order_delivery_status == 1) accepted by store @elseif($orderdata->order_delivery_status == 2) preparing order @elseif($orderdata->order_delivery_status == 3) picked up and flying to you @elseif($orderdata->order_delivery_status == 4) arrived @else deliverd @endif</td></tr>
		<tr> <th>Payment Status</th><td>@if($orderdata->status == 1) Complete @elseif($orderdata->status == 2) Complete @else Failed @endif</td></tr>
		<tr> <th>Created At</th><td><?php echo  date('Y-m-d', strtotime($orderdata->created_at)); ?></td></tr>
        <tr><td colspan="2"><a href="{{ URL::to('/vendor/print_order') }}/{{$orderdata->id}}" target="_blank" class="btn btn-success margin_left10 print_orderq" data_order="<?php echo $orderdata->id; ?>">Print</a></td></tr>
    </tbody>

               
    </table>
	
	<script>
	
	  $('.print_order').click(function(){
            var order_id = $(this).attr('data_order');
            if(order_id == ''){
                alert('Something went wrong. Please try again.');
            }else{
                //LoaderShow();
				 $.ajaxSetup({

                  headers: {

                      'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')

                  }

              });
                $.ajax({
                  
				   url: '{{ URL::to('/vendor/print_order') }}',
                  data : {order_id:order_id},
                  type : 'POST',
                  dataType : 'json',
                  success: function(result){
                    LoaderHide();
                    console.log(result);
                    if(result.class == 'added'){
                        var win = window.open(result.path, '_blank');
                        if (win) {
                            win.focus();
                        }
                    }
                    else {
                      alert('Some Error occured. Please try again.');
                    }
                  }

                });
            }
        });
	
	</script>