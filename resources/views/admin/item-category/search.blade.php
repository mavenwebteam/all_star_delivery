<table class="table table-bordered table-hover">
  <thead>
     <tr>
        <th>Image</th>
        <th>Name (en)</th>
        <th>Name (Burmese)</th>
        <th>Business Category Name</th>
        <th class="text-center">Status</th>
        <th>Created</th>
        <th style="text-align:center;">Action</th>
     </tr>
  </thead>
  <tbody>
    @if(count($catedata)>0)
      @foreach($catedata as $data)
        <tr>
            <td>@if($data->image!="") <img src="{{asset('media/item_category/'.$data->image)}}" style="width:50px;"> @else   <img src="{{asset('/media/no-image.png')}}" width="70px" height="70px"> @endif</td>
            <td>{{$data->name_en}}</td>
            <td>{{$data->name_burmese	}}</td>
            <td>
              <?php $helper=new App\Helpers;?>
              {{$helper->GetBusinessCategory($data->category_id)}}
            </td>
            <td class="text-center">
              @if($data->status==0)  <span class="label label-danger">Deactive</span> @else 
              <span class="label label-success">Active</span>
              @endif
            </td>
            <td>{{$data->created_at}}</td>
            <td class="res-dropdown" style="" align="center">
              <a data-toggle="tooltip" data-placement="top" title="" href="javascript:" class="btn btn-primary" data-original-title="Edit Category" onclick="edit_record('{{base64_encode($data->id)}}')" ><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a></a>
              <a data-toggle="tooltip" data-placement="top" title="" href="javascript:" class="btn btn-warning" data-original-title="View Category" onclick="view_record('{{base64_encode($data->id)}}')"><i class="fa fa-eye" aria-hidden="true"></i></a>
              <?php if($data->status=="0"){?>
              <a data-toggle="tooltip" data-placement="top" title="" href="javascript:" class="btn btn-success" data-original-title="Active Category" onclick="statusChange('{{base64_encode($data->id)}}')"><i class="fa fa-check" aria-hidden="true"></i></a>
              <?php }else{?>
              <a data-toggle="tooltip" data-placement="top" title="" href="javascript:" class="btn btn-danger" data-original-title="Deactive Category" onclick="statusChange('{{base64_encode($data->id)}}')"><i class="fa fa-close" aria-hidden="true"></i></a>
              <?php }?>
            </td>
        </tr>
      @endforeach
    @else
     <tr>
        <td colspan="8">Data not found</td>
     </tr>
    @endif    
  </tbody>
</table>
</table>{!! $catedata->links() !!} 
<style>	span.pagecounts {    display: inline-block;    width: 100%;			}	</style>
<span class="pagecounts">Records {{ $catedata->firstItem() }} - {{ $catedata->lastItem() }} of {{ $catedata->total() }} (for page {{ $catedata->currentPage() }} ) </span>