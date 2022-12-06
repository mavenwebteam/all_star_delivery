<div class="table-responsive">
  <table class="table table-bordered table-hover">
     <thead>
        <tr>
           <th>Image</th>
           <th>First Name</th>
           <th>Last Name</th>
           <th>Email</th>
           <th>Mobile No</th>
           <th class="text-center">Status</th>
           <th>Created</th>
           <th style="text-align:center;">Action</th>
        </tr>
     </thead>
     <tbody>
         @if(count($userdata) > 0)
            @foreach($userdata as $data)
               <tr>
                  <td>
                     @if($data->profile_pic!="")
                     <img src="{{asset('/media/users').'/'.$data->profile_pic}}" width="55px" height="55px">
                     @else
                     <img src="{{asset('/media/no-image.png')}}" width="55px" height="55px"> 
                     @endif
                  </td>
                  <td>{{$data->first_name}} </td>
                  <td>{{$data->last_name}}</td>
                  <td>{{$data->email}}</td>
                  <td>{{$data->country_code}}-{{$data->mobile}}</td>
                  <td class="text-center">
                     @if($data->status==0)  <span class="label label-danger">Deactive</span> @else 
                     <span class="label label-success">Active</span>
                     @endif
                  </td>
                  <td>{{$data->created_at}}</td>
                  <td class="res-dropdown" style="" align="center">
                     @if(in_array('subAdmin.edit-user.edit', $permissionData))
                     <a data-toggle="tooltip" data-placement="top" title="" href="javascript:" class="btn btn-primary" data-original-title="Edit" onclick="edit_record('{{base64_encode($data->id)}}')" ><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                     @endif
                     @if(in_array('subAdmin.view-user.show', $permissionData))
                     <a data-toggle="tooltip" data-placement="top" title="" href="javascript:" class="btn btn-warning" data-original-title="View" onclick="view_record('{{base64_encode($data->id)}}')"><i class="fa fa-eye" aria-hidden="true"></i></a>
                     @endif
                     @if(in_array('subAdmin.user.status', $permissionData))
                     <?php if($data->status=="0"){?>
                     <a data-toggle="tooltip" data-placement="top" title="" href="javascript:" class="btn btn-success" data-original-title="Active" onclick="statusChange('{{base64_encode($data->id)}}')"><i class="fa fa-check" aria-hidden="true"></i></a>
                     <?php }else{?>
                     <a data-toggle="tooltip" data-placement="top" title="" href="javascript:" class="btn btn-danger" data-original-title="Deactive" onclick="statusChange('{{base64_encode($data->id)}}')"><i class="fa fa-close" aria-hidden="true"></i></a>
                     <?php }?>
                     @endif
                     @if(in_array('subAdmin.user.destroy', $permissionData))
                     <a data-toggle="tooltip" data-placement="top" title="" href="javascript:" class="btn btn-danger" data-original-title="Remove" onclick="remove_record('{{base64_encode($data->id)}}')"><i class="fa fa-trash" aria-hidden="true"></i></a>
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
{!! $userdata->links() !!}
<style>	span.pagecounts {    display: inline-block;    width: 100%;			}	</style>
<span class="pagecounts"> Records {{ $userdata->firstItem() }} - {{ $userdata->lastItem() }} of {{ $userdata->total() }} (for page {{ $userdata->currentPage() }} ) </span>