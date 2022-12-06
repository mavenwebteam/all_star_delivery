<table class="table table-bordered table-hover">
  <thead>
  <tr>
  <th>SR.NO.</th>
    <th>Notification Type</th>
    <th>Notification</th>
    <th>Created</th>
    <th style="text-align:center;">Action</th>
  </tr>
  </thead>
  <tbody>
  <?php $i = 1;?>
    @foreach($noti_data as $data)
  <tr>
    <td>{{$i}}</td>
    <td>{{$data->noti_type}}</td>
    <td>{{$data->notification}}</td>
    <td>{{$data->created_at}}</td>
    <td class="res-dropdown" style="" align="center">
    <a data-toggle="tooltip" data-placement="top" title="" href="javascript:" class="btn btn-warning" data-original-title="View Detail" onclick="view_record('{{base64_encode($data->id)}}')"><i class="fa fa-eye" aria-hidden="true"></i></a></a>
    <!-- <a data-toggle="tooltip" data-placement="top" title="" href="javascript:" class="btn btn-primary" data-original-title="Edit Content" onclick="edit_record('{{base64_encode($data->id)}}')" ><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a></a> -->
    <!-- <a data-toggle="tooltip" data-placement="top" title="" href="javascript:" class="btn btn-danger" data-original-title="Remove Content" onclick="remove_record('{{base64_encode($data->id)}}')"><i class="fa fa-trash" aria-hidden="true"></i></a></a> -->
  </td>
  </tr>  
  <?php $i++;?>
  @endforeach
  @if($i<2)
  <tr>
<td colspan="5">No Notification Data</td>
  </tr>
  @endif    
  </tbody> 
    </table>{!! $noti_data->links() !!} 