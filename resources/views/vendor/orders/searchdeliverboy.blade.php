<table class="table table-bordered table-hover">
    <thead>
    <tr>
    <th>SR.NO.</th>
    <th>Order Id</th>
    <th>Customer Name</th>
    <th>Product </th>
  
	 <th>Trancation Id</th>
	 <th>Quantity</th>
    <th>Total Amount</th>
	<th>Delivery Boys</th>

	<th>Delivery Status</th>
   
    <th>Created</th>
    <!--<th>Action</th>-->
    </tr>
    </thead>
    <tbody>
    <?php $i = 1;?>
      @foreach($orderdata as $data)
    <tr>
      <td>{{$i}}</td>
      <td>{{$data->username}}</td>
      <td>{{$data->id}}</td>
	  <td>{{$data->productname}}</td>
	 
	  <td>{{$data->transaction_id}}</td>
	  <td>{{$data->quantity}}</td>
      <td>{{$data->total_amount}}</td>
	  <td> @if(!empty($data->delivery_boy_id))  {{$data->deliveryboyname}}  @else 
	    {!! Form::select('delivery_boy_id',$deliveryboy_box ,null, ['class' => 'form-control dboy', 'data_order_id'=>$data->id]) !!}
	   
	   @endif</td>
	  
	  
	  <td>@if($data->order_delivery_status==0) sent to restaurant @elseif($data->order_delivery_status==1) accepted by restaurant @elseif($data->order_delivery_status==2) preparing order @elseif($data->order_delivery_status==3) picked up and flying to you@elseif($data->order_delivery_status==4) arrived @else deliverd @endif</td>
    
      <td>{{$data->created_at}}</td>
     <!-- <td class="res-dropdown" style="" align="center">
      <a data-toggle="tooltip" data-placement="top" title="" href="javascript:" class="btn btn-warning" data-original-title="View Order Detail" onclick="view_record('{{base64_encode($data->id)}}')" ><i class="fa fa-eye" aria-hidden="true"></i></a></a>
      

    </td>-->
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