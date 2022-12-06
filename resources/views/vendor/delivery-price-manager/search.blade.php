<table class="table table-bordered table-hover">
<thead>
<tr>
<th>SR.NO.</th>
<th>Type</th>
<th>Minimum Order Amount</th>
<th>Maximum Store Distance</th>
<th>Estimated Time Option (km/min)</th>
<th>Free Delivery Amount</th>
<th>Created</th>
<th style="text-align:center;">Action</th>
</tr>
</thead>
<tbody>
<?php $i = 1;?>
  @foreach($delivery_price_data as $data)
<tr>
  <td>{{$i}}</td>
  <td>{{$data->type}}</td>
  <td>{{$data->minimum_order_option}}</td>
  <td>{{$data->maximum_distance_option}}</td>
  <td>{{$data->estimated_time_option}}</td>
  <td>{{$data->free_delivery_option}}</td>

  <td>{{$data->created_at}}</td>
  <td class="res-dropdown" style="" align="center">
  <a data-toggle="tooltip" data-placement="top" title="" href="javascript:" class="btn btn-primary" data-original-title="Edit Delivery Price" onclick="edit_record('{{base64_encode($data->id)}}')" ><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a></a>
  </td>
</tr>  
<?php $i++;?>
@endforeach
@if($i<2)
<tr>
<td>No Cash Limit Data</td>
</tr>
@endif    
</tbody> 
</table>
