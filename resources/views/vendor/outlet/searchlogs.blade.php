<table class="table table-bordered table-hover">
    <thead>
    <tr>
    <th>SR.NO.</th>
    <th>Order Id</th>
  
	 <th>Trancation Id</th>
	 <th>Quantity</th>
    <th>Net Amount</th>
	<th>Delivery Charges</th>
    <th>Total Amount</th>
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
	  
	  <td>{{$data->transaction_id}}</td>
	  <td>{{$data->quantity}}</td>
      <td>{{$data->total_amount}}</td>
      <td>{{$data->total_shipping_amount}}</td>
      <td>{{$data->net_amount}}</td>
      <td>@if($data->payment_mode == 1) COD @elseif($data->payment_mode == 2) Card @else Online @endif</td>
	  <td>@if($data->is_cancelled == 1) Yes @else No @endif</td>
	   <td>@if($data->order_delivery_status == 0) sent to restaurant @elseif($data->order_delivery_status == 1) accepted by restaurant @elseif($data->order_delivery_status == 2) preparing order @elseif($data->order_delivery_status == 3) picked up and flying to you @elseif($data->order_delivery_status == 4) arrived @else deliverd @endif</td>
      <td>@if($data->status == 1) Complete @elseif($data->status == 2) Pending @else Failed @endif</td>
      <td>{{$data->created_at}}</td>
      <td class="res-dropdown" style="" align="center">
      <a data-toggle="tooltip" data-placement="top" title="" href="javascript:" class="btn btn-warning" data-original-title="View Order Detail" onclick="view_record('{{base64_encode($data->id)}}')" ><i class="fa fa-eye" aria-hidden="true"></i></a></a>
      

    </td>
    </tr>  
    <?php $i++;?>
    @endforeach
    @if($i<2)
    <tr>
    <td>No Orders Data</td>
    </tr>
    @endif    
    </tbody> 
    </table>{!! $orderdata->links() !!}  