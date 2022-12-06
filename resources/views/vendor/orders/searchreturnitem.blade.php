<table class="table table-bordered table-hover">
    <thead>
    <tr>
    <th>SR.NO.</th>
    <th>Order Id</th>
    <th>Customer Name</th>
    <th>Product </th>
  
	 <th>Trancation Id</th>
	 <th>Return Quantity </th>
    <th>Return Amount ($)</th>
	
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
	 
    
      <td>{{$data->created_at}}</td>
     <!-- <td class="res-dropdown" style="" align="center">
      <a data-toggle="tooltip" data-placement="top" title="" href="javascript:" class="btn btn-warning" data-original-title="View Order Detail" onclick="view_record('{{base64_encode($data->id)}}')" ><i class="fa fa-eye" aria-hidden="true"></i></a></a>
      

    </td>-->
    </tr>  
    <?php $i++;?>
    @endforeach
    @if($i<2)
    <tr>
    <td>No Return Item Data</td>
    </tr>
    @endif    
    </tbody> 
    </table>{!! $orderdata->links() !!}  