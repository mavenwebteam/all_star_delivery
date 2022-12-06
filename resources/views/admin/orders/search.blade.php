<div class="table-responsive">
<table class="table table-bordered table-hover">
    <thead>
    <tr>
    <th>SR.NO.</th>
    <th>Order Id</th>
    <th>Customer Name</th>
    <th>Product </th>
    <th>Category</th>
	<!--<th>Brand</th>-->
	 <th>Trancation Id</th>
	 <th>Quantity</th>
    <th> Total Amount ($)</th>
	<th>Delivery Charges ($)</th>
    <th>Net Amount ($)</th>
    <th>Payment Mode</th>
	<th>Is Cancelled</th>
	<th>Delivery Status</th>
    <th>Payment Status</th>
    <th>Created</th>
    <th style="text-align:center;">Action</th>
    </tr>
    </thead>
    <tbody>
    <?php $i = 1;?>
      @foreach($orderdata as $data)
    <tr>
      <td>{{$i}}</td>
     
      <td>{{$data->id}}</td>
	   <td> <a  href="javascript:"onclick="view_user_record('{{base64_encode($data->user_id)}}')" >{{$data->username}} </a></td>
	  <td>
	
	  <ul>
	  <?php $helper=new App\Helpers;
			$productdata = $helper->SelectProductItem($data->id); ?>
		@foreach($productdata as $product)
	    <li>{{$product->productname}}({{$product->quantity}}) @if($product->is_cancelled == 1) (Cancelled)@endif </li>
		 @endforeach
	  </ul>
	  </td>
	  <td>{{$data->catname}}</td>
	 <!-- <td>{{$data->brandname}}</td>-->
	  <td>{{$data->transaction_id}}</td>
	  <td>{{$data->sumquantity}}</td>
      <td>{{$data->net_amount}}</td>
      <td>{{$data->total_shipping_amount}}</td>
      <td>{{$data->total_amount}}</td>
      <td>@if($data->payment_mode == 1) COD @elseif($data->payment_mode == 2) Card @else Wallet @endif</td>
	  <td>@if($data->is_cancelled == 1) Yes @else No @endif</td>
	   <td>
	   @if($data->is_cancelled == 0)
	   @if($data->order_delivery_status == 0) sent to store @elseif($data->order_delivery_status == 1) accepted by store @elseif($data->order_delivery_status == 2) preparing order @elseif($data->order_delivery_status == 3) picked up and flying to you @elseif($data->order_delivery_status == 4) arrived @else deliverd @endif
       @else
		    Cancel order
		@endif   </td>
      <td>@if($data->status == 1) Complete @elseif($data->status == 2) Complete @else Failed @endif</td>
      <td>{{$data->created_at}}</td>
      <td class="res-dropdown" style="" align="center">
      <a data-toggle="tooltip" data-placement="top" title="" href="javascript:" class="btn btn-warning" data-original-title="View Order Detail" onclick="view_record('{{base64_encode($data->id)}}')" ><i class="fa fa-eye" aria-hidden="true"></i></a></a>
      

    </td>
    </tr>  
    <?php $i++;?>
    @endforeach
    @if($i<2)
    <tr>
    <td colspan="17" >No Orders Data</td>
    </tr>
    @endif    
    </tbody> 
    </table> 
	
	</div>{!! $orderdata->links() !!} <style>
	span.pagecounts {
    display: inline-block;
    width: 100%;
			}
	</style>
	<span class="pagecounts">
	Records {{ $orderdata->firstItem() }} - {{ $orderdata->lastItem() }} of {{ $orderdata->total() }} (for page {{ $orderdata->currentPage() }} )</span>