<div class="table-responsive">
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
     
      <td>{{$data->id}}</td>
	   <td>{{$data->username}}</td>
	  <td>{{$data->productname}}</td>
	 
	  <td>{{$data->transaction_id}}</td>
	  <td>{{$data->total_quantity  }}</td>
      <td>{{$data->total_amount}}</td>
	  <td> @if(!empty($data->delivery_boy_id))  {{$data->deliveryboyname}}  @else 
	    {!! Form::select('delivery_boy_id',$deliveryboy_box ,null, ['class' => 'form-control dboy', 'data_order_id'=>$data->id]) !!}
	   
	   @endif</td>
	  
	  
	  <td>@if($data->order_delivery_status==0) sent to store @elseif($data->order_delivery_status==1) accepted by store @elseif($data->order_delivery_status==2) preparing order @elseif($data->order_delivery_status==3) picked up and flying to you @elseif($data->order_delivery_status==4) arrived @else deliverd @endif</td>
    
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
    </table></div>{!! $orderdata->links() !!}  <style>
	span.pagecounts {
    display: inline-block;
    width: 100%;
			}
	</style>
	<span class="pagecounts"> Records {{ $orderdata->firstItem() }} - {{ $orderdata->lastItem() }} of {{ $orderdata->total() }} (for page {{ $orderdata->currentPage() }} ) </span>
	<script>
	 $(".dboy").on('change', function(){ 
		var db_id = $(this).val();
        var order_id = $(this).attr('data_order_id');
        if(db_id == ''){
            alert('Please select delivery boy first.');
        }else{
            var r = confirm("Are you sure you want to assign this delivery boy to this order?");
   if (r == true) {			
      $.ajaxSetup({
                  headers: {
                      'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                  }
              });
      $.ajax({
            dataType: 'json',
            data: { db_id:db_id,order_id:order_id}, 
            type: "POST",
            url: '{{ URL::to('/sub-admin/assign_deliveryboys') }}',
        }).done(function( data ) 
        {   
          search();
          if(data.class == 'success')
            {showMsg(data.message, "success");}
          if(data.class == 'error')
            {showMsg(data.message, "error");}
          
        });
		
		}else{
                return false;
            } 
        }
        
    });
	</script>