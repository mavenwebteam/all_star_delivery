<div class="table-responsive">
<table class="table table-bordered table-hover">
    <thead>
    <tr>
    <th>SR.NO.</th>
    <th>SKU</th>
	 <th>Store name</th>
	  <th>Outlet name</th>
    <th>Product Name</th>
	<th>Category Name</th>
	<th>Brand Name</th>
	<th>Minimum Quantity<br>(For out of stock)</th>
	<th>Quantity</th>
	<th>Stock Status</th>
    <!--<th>Vendor</th>-->
   
    <th>Status</th>
    <th>Created</th>
    <th style="text-align:center;">Action</th>
    </tr>
    </thead>
    <tbody>
    <?php $i = 1;?>
      @foreach($productdata as $data)
    <tr>
      <td>{{$i}}</td>
      <td>{{$data->sku}}</td>
       <td>{{$data->storename}}</td>
       <td>{{$data->outname}}</td>
      
      <td>{{$data->name}}</td>
	   <td>{{$data->catname}}</td>
	    <td>{{$data->braname}}</td>
	   <td>{{$data->minimum_quantity}}</td>
	   <td>{{!empty($data->total_qty) ? $data->total_qty :'0'}}</td>
	  <!-- <td>@if($data->total_qty > $data->minimum_quantity) In Stock  @else Out Of Stock @endif</td>-->
	    <td>@if($data->stock == 0) Out Of Stock @else In Stock @endif</td>
      <!--td>{{$data->vendoremail}}</td>-->
     
     
                      <td>@if($data->status==0)  <span class="label label-danger">Deactive</span> @else 
				<span class="label label-success">Active</span>
			@endif</td>
      <td>{{$data->created_at}}</td>
      <td class="res-dropdown" style="" align="center">
      <a data-toggle="tooltip" data-placement="top" title="" href="javascript:" class="btn btn-primary" data-original-title="Edit Product" onclick="edit_record('{{base64_encode($data->id)}}')" ><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a></a>
	    <a data-toggle="tooltip" data-placement="top" title="" href="javascript:" class="btn btn-warning" data-original-title="View Product" onclick="view_record('{{base64_encode($data->id)}}')"><i class="fa fa-eye" aria-hidden="true"></i></a></a>
      <?php if($data->status=="0"){?>
    <a data-toggle="tooltip" data-placement="top" title="" href="javascript:" class="btn btn-success" data-original-title="Active Product" onclick="statusChange('{{base64_encode($data->id)}}')"><i class="fa fa-check" aria-hidden="true"></i></a>
  <?php }else{?>
  <a data-toggle="tooltip" data-placement="top" title="" href="javascript:" class="btn btn-danger" data-original-title="Deactive Product" onclick="statusChange('{{base64_encode($data->id)}}')"><i class="fa fa-close" aria-hidden="true"></i></a>
<?php }?>
<a data-toggle="tooltip" data-placement="top" title="" href="javascript:" class="btn btn-danger" data-original-title="Remove Product" onclick="remove_record('{{base64_encode($data->id)}}')"><i class="fa fa-trash" aria-hidden="true"></i></a>
      <!-- <a data-toggle="tooltip" data-placement="top" title="" href="javascript:" class="btn btn-danger" data-original-title="Remove Category" onclick="remove_record('{{base64_encode($data->id)}}')"><i class="fa fa-trash" aria-hidden="true"></i></a></a> -->
    </td>
    </tr>  
    <?php $i++;?>
    @endforeach
    @if($i<2)
    <tr>
    <td colspan="11">No Product Data</td>
    </tr>
    @endif    
    </tbody> 
    </table> 
	</div>
	{!! $productdata->links() !!} 
	<style>
	span.pagecounts {
    display: inline-block;
    width: 100%;
			}
	</style>
	<span class="pagecounts">
	Records {{ $productdata->firstItem() }} - {{ $productdata->lastItem() }} of {{ $productdata->total() }} (for page {{ $productdata->currentPage() }} )
	</span>