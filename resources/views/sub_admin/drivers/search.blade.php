
<style>.btn{margin-top:5px}</style>
<div class="table-responsive">
  <table class="table table-bordered table-hover">
    <thead>
       <tr>
          <th>Driver Id</th>
          <th>Name</th>
          <th>Vehicle Type</th>
          <th>Contact Number</th>
          <th>Email</th>
          <th>Vehicle Number</th>
          <th class="text-center">Status</th>
          <th>Created</th>
          <th style="text-align:center;">Action</th>
       </tr>
    </thead>
    <tbody>
       @if(count($driverData) > 0)
       @foreach($driverData as $data)
           <tr>
              <td>{{$data->uu_id}} </td>
              <td width="100">{{ $data->first_name.' '.$data->last_name }} </td>
              <td>@if($data->vehicle) {{ $data->vehicle->vehicle_type }} @endif</td>
              <td>{{'+'.$data->country_code}}-{{$data->mobile}}</td>
              <td>{{$data->email}}</td>
              <td>@if($data->vehicle) {{ $data->vehicle->vehicle_num }} @endif</td>
              <td class="text-center">
                 @if($data->status==0)  <span class="label label-danger">Deactive</span> @else 
                 <span class="label label-success">Active</span>
                 @endif
              </td>
              <td>{{$data->created_at}}</td>
              <td class="res-dropdown" style="" align="center">
               @if(in_array('subAdmin.drivers.edit', $permissionData))  
                  <a data-toggle="tooltip" data-placement="top" title="" href="javascript:" class="btn btn-primary" data-original-title="Edit" onclick="edit_record('{{base64_encode($data->id)}}')" ><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
               @endif
               @if(in_array('subAdmin.drivers.show', $permissionData))
                 <a data-toggle="tooltip" data-placement="top" title="" href="javascript:" class="btn btn-warning" data-original-title="View" onclick="view_record('{{base64_encode($data->id)}}')"><i class="fa fa-eye" aria-hidden="true"></i></a>
               @endif
               @if(in_array('subAdmin.driver.status.update', $permissionData))
                 <?php if($data->status=="0"){?>
                 <a data-toggle="tooltip" data-placement="top" title="" href="javascript:" class="btn btn-success" data-original-title="Active" onclick="statusChange('{{base64_encode($data->id)}}')"><i class="fa fa-check" aria-hidden="true"></i></a>
                 <?php }else{?>
                 <a data-toggle="tooltip" data-placement="top" title="" href="javascript:" class="btn btn-danger" data-original-title="Deactive" onclick="statusChange('{{base64_encode($data->id)}}')"><i class="fa fa-close" aria-hidden="true"></i></a>
                 <?php }?>
                 @endif
                 @if(in_array('subAdmin.drivers.destroy', $permissionData))
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

	{!! $driverData->links() !!} 
<div class="row">
   <div class="col-md-12">
      Records {{ $driverData->firstItem() }} - {{ $driverData->lastItem() }} of {{ $driverData->total() }} (for page {{ $driverData->currentPage() }} )

   </div>
</div>