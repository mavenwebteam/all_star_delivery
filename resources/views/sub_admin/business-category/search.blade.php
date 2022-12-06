<table class="table table-bordered table-hover">
  <thead>
     <tr>
        <th>Name (en)</th>
        <th>Name (Burmese)</th>
        <th>Image</th>
        <th class="text-center">Status</th>
        <th>Created</th>
        <th style="text-align:center;">Action</th>
     </tr>
  </thead>
  <tbody>
    @if(count($business_category_data) > 0)
      @foreach($business_category_data as $data)
        <tr>
            <td>{{$data->name_en}}</td>
            <td>{{$data->name_burmese}}</td>
            <td> 
              @if($data->image!="")
                <img src="{{asset('/media/business_category').'/'.$data->image}}" width="70px" height="70px">
              @else
                <img src="{{asset('/media/no-image.png')}}" width="70px" height="70px">
              @endif				  				  				  
            </td>
            <td class="text-center">
              @if($data->status==0)  
                <span class="label label-danger">Deactive</span>
              @else <span class="label label-success">Active</span>			@endif
            </td>
            <td>{{$data->created_at}}</td>
            <td class="res-dropdown" style="" align="center">
              @if(in_array('subAdmin.business-category.edit', $permissionData))
                <a data-toggle="tooltip" data-placement="top" title="" href="javascript:" class="btn btn-primary" data-original-title="Edit Business Category" onclick="edit_record('{{base64_encode($data->id)}}')" ><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
              @endif
              @if(in_array('subAdmin.view-business-category.show', $permissionData))
              <a data-toggle="tooltip" data-placement="top" title="" href="javascript:" class="btn btn-warning" data-original-title="View Business Category" onclick="view_record('{{base64_encode($data->id)}}')"><i class="fa fa-eye" aria-hidden="true"></i></a>
              @endif
            </td>
        </tr>
      @endforeach
    @else
     <tr>
        <td colspan="8">No Data Found</td>
     </tr>
    @endif    
  </tbody>
</table>
{!! $business_category_data->links() !!} 
<style>	span.pagecounts {    display: inline-block;    width: 100%;			}	</style>
<span class="pagecounts">  Records {{ $business_category_data->firstItem() }} - {{ $business_category_data->lastItem() }} of {{ $business_category_data->total() }} (for page {{ $business_category_data->currentPage() }} ) </span>