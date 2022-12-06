
<style>.btn{margin-top:5px}</style>
<div class="table-responsive">
  <table class="table table-bordered table-hover">
    <thead>
       <tr>
          <th>Banner</th>
          <th>Business Category</th>
          <th>Store</th>
          <th class="text-center">Status</th>
          <th>Created</th>
          <th style="text-align:center;">Action</th>
       </tr>
    </thead>
    <tbody>
       @if(count($data) > 0)
       @foreach($data as $value)
           <tr>
              <td>
                  @if($value->banner &&  file_exists(public_path('/media/banners/thumb/'.$value->banner)))
                  <span class="image_inner" style="margin:0px 5px 5px 0px;">
                        <img src="{{asset('media/banners/thumb/'.$value->banner)}}"  class='imgClass' width='55px',height='55px'>
                  </span>
                  @else
                        <img src="{{ asset('img/dummy.jpg') }}"  class='imgClass' width='55px',height='55px'>
                  @endif
              </td>
              <td>@if($value->businessCategory) {{ $value->businessCategory->name_en }} @endif</td>
              <td>@if($value->store) {{ $value->store->name }} @endif</td>
              <td class="text-center">
                 @if($value->status==0)  <span class="label label-danger">Deactive</span>@else 
                 <span class="label label-success">Active</span>
                 @endif
              </td>
              <td>{{$value->created_at}}</td>
              <td class="res-dropdown" style="" align="center">
               @if(in_array('subAdmin.banners.edit', $permissionData))
                  <a data-toggle="tooltip" data-placement="top" title="" href="javascript:" class="btn btn-primary" data-original-title="Edit" onclick="edit_record('{{base64_encode($value->id)}}')" ><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
               @endif
               @if(in_array('subAdmin.banners.status', $permissionData))
                 <?php if($value->status=="0"){?>
                 <a data-toggle="tooltip" data-placement="top" title="" href="javascript:" class="btn btn-success" data-original-title="Active" onclick="statusChange('{{base64_encode($value->id)}}')"><i class="fa fa-check" aria-hidden="true"></i></a>
                 <?php }else{?>
                 <a data-toggle="tooltip" data-placement="top" title="" href="javascript:" class="btn btn-danger" data-original-title="Deactive" onclick="statusChange('{{base64_encode($value->id)}}')"><i class="fa fa-close" aria-hidden="true"></i></a>
                 <?php }?>
               @endif
               @if(in_array('subAdmin.banners.destroy', $permissionData))
                 <a data-toggle="tooltip" data-placement="top" title="" href="javascript:" class="btn btn-danger" data-original-title="Remove" onclick="remove_record('{{base64_encode($value->id)}}')"><i class="fa fa-trash" aria-hidden="true"></i></a>
               @endif
              </td>
           </tr>
        @endforeach
        @else
           <tr>
              <td colspan="8" style="text-align:center;">No Data Found</td>
           </tr>
        @endif    
    </tbody>
 </table>
</div>

	{!! $data->links() !!} 

	Records {{ $data->firstItem() }} - {{ $data->lastItem() }} of {{ $data->total() }} (for page {{ $data->currentPage() }} )