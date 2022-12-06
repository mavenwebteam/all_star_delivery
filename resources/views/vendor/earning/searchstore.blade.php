<table class="table table-bordered table-hover">
    <thead>
    <tr>
    <th>SR.NO.</th>
    <th>Image</th>
    <th>Store Name</th>
    <th>Vendor</th>
    <th>Product Category</th>
    <th>Open/Close</th>
	<th>Rating</th>
    <th>Status</th>
    <th>Created</th>
    <th style="text-align:center;">Action</th>
    </tr>
    </thead>
    <tbody>
          <?php $i = 1;?>
            @foreach($storedata as $data)
          <tr>
            <td>{{$i}}</td>
            <td>@if($data->image!="") <img src="{{asset('media/store/'.$data->image)}}" style="width:50px;"> @else No Image @endif</td>
            <td>{{$data->name}}</td>
            <td>{{$data->vendoremail}}</td>
            <?php $helper=new App\Helpers;?>
            <td >{{$helper->GetProductCategoryNameByids(explode(',',$data->category_id))}}</td>
            <td>{{ucfirst($data->is_open)}}</td>
			 <td>{{$data->rating}}</td>
            <td>@if($data->status==0) Deactive @else Active @endif</td>
            <td>{{$data->created_at}}</td>
            <td class="res-dropdown" style="" align="center">
			@if(!empty($data->pdf_file))
			
            <a data-toggle="tooltip" data-placement="top" title="" target="_blank" href="{{asset('media/pdf/'.$data->pdf_file)}}" class="btn btn-primary" data-original-title="download Invoice" onclick="edit_record1('{{base64_encode($data->id)}}')" ><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a></a>
			@else
				file not found
			@endif	
			<!--
            <a data-toggle="tooltip" data-placement="top" title="" href="javascript:" class="btn btn-warning" data-original-title="View Store" onclick="view_record('{{base64_encode($data->id)}}')"><i class="fa fa-eye" aria-hidden="true"></i></a></a>
            <?php if($data->status=="0"){?><a data-toggle="tooltip" data-placement="top" title="" href="javascript:" class="btn btn-success" data-original-title="Active Store" onclick="statusChange('{{base64_encode($data->id)}}')"><i class="fa fa-check" aria-hidden="true"></i></a>
            <?php }else{?><a data-toggle="tooltip" data-placement="top" title="" href="javascript:" class="btn btn-danger" data-original-title="Deactive Store" onclick="statusChange('{{base64_encode($data->id)}}')"><i class="fa fa-close" aria-hidden="true"></i></a><?php }?>
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
</table>
{!! $storedata->links() !!}   Records {{ $storedata->firstItem() }} - {{ $storedata->lastItem() }} of {{ $storedata->total() }} (for page {{ $storedata->currentPage() }} ) 