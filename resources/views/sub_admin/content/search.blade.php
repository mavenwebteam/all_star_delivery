<table class="table table-bordered table-hover">
  <thead>
     <tr>
        <th>Title</th>
        <th class="text-center">Status</th>
        <th>Created</th>
        <th style="text-align:center;">Action</th>
     </tr>
  </thead>
  <tbody>
     <?php $i = 1;?>
     @foreach($content_data as $data)
     <tr>
        <td>{{$data->title}}</td>
        <td class="text-center">@if($data->status==0)  <span class="label label-danger">Deactive</span> @else 
           <span class="label label-success">Active</span>
           @endif
        </td>
        <td>{{$data->created_at}}</td>
        <td class="res-dropdown" style="" align="center">
           @if(in_array('subAdmin.edit-content.edit', $permissionData))
           <a data-toggle="tooltip" data-placement="top" title="" href="javascript:" class="btn btn-primary" data-original-title="Edit Content" onclick="edit_record('{{base64_encode($data->id)}}')" ><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
           @endif
           @if(in_array('subAdmin.content.status', $permissionData))
           <?php if($data->status=="0"){?>
           <a data-toggle="tooltip" data-placement="top" title="" href="javascript:" class="btn btn-success" data-original-title="Active Content" onclick="statusChange('{{base64_encode($data->id)}}')"><i class="fa fa-check" aria-hidden="true"></i></a>
           <?php }else{?>
           <a data-toggle="tooltip" data-placement="top" title="" href="javascript:" class="btn btn-danger" data-original-title="Deactive Content" onclick="statusChange('{{base64_encode($data->id)}}')"><i class="fa fa-close" aria-hidden="true"></i></a>
           <?php }?>                        
           @endif
        </td>
     </tr>
     <?php $i++;?>
     @endforeach
     @if($i<2)
     <tr>
        <td>No Content Data</td>
     </tr>
     @endif    
  </tbody>
</table>
{!! $content_data->links() !!}