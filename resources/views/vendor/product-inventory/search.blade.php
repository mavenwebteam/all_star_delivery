<table class="table table-bordered table-hover">
  <thead>
  <tr>
  <th>SR.NO.</th>
  <th>SKU</th>
  <th>Product Name</th>
 <th>Outlet Name</th>
  <th>Quantity</th>
  <!--<th>Price</th>-->
  <th>Created</th>
  <th style="text-align:center;">Action</th>
  
  </tr>
  </thead>
  <tbody>
  <?php $i = 1;?>
    @foreach($productinventoriesdata as $data)
  <tr>
    <td>{{$i}}</td>
    <td>{{$data->sku}}</td>
    <td>{{$data->name}}</td>
    <td>{{$data->vendorname}}</td>
    <td>{{$data->qty}}</td>
    
    <td>{{$data->created_at}}</td>
    <td class="res-dropdown" style="" align="center">        <?php 	 $date1 = date('Y-m-d', strtotime($data->created_at));	 $date2 =  date("Y-m-d");	if($date1 == $date2){	?> 
    <a data-toggle="tooltip" data-placement="top" title="" href="javascript:" class="btn btn-primary" data-original-title="Edit Store" onclick="edit_record('{{base64_encode($data->id)}}')" ><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a></a>	<?php } ?>			<a data-toggle="tooltip" data-placement="top" title="" href="javascript:" class="btn btn-warning" data-original-title="View Inventory" onclick="view_record('{{base64_encode($data->id)}}')"><i class="fa fa-eye" aria-hidden="true"></i></a></a>
    <!-- <a data-toggle="tooltip" data-placement="top" title="" href="javascript:" class="btn btn-danger" data-original-title="Remove Category" onclick="remove_record('{{base64_encode($data->id)}}')"><i class="fa fa-trash" aria-hidden="true"></i></a></a> -->
  
  </td>
  </tr>  
  <?php $i++;?>
  @endforeach

  @if($i<2)
  <tr>
  <td>No Store Data</td>
  </tr>
  @endif    
  </tbody> 
                
    </table>{!! $productinventoriesdata->links() !!}  	Records {{ $productinventoriesdata->firstItem() }} - {{ $productinventoriesdata->lastItem() }} of {{ $productinventoriesdata->total() }} (for page {{ $productinventoriesdata->currentPage() }} )