<table class="table table-bordered table-hover">
                <thead>
                <tr>
                <th>SR.NO.</th>
                  <th>Name</th>
                  <th>Status</th>
                  <th>Created</th>
                  <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php $i = 1;?>
                  @foreach($country_data as $data)
                <tr>
                  <td>{{$i}}</td>
                  <td>{{$data->name}}</td>
                  <td>@if($data->status==0) Deactive @else Active @endif</td>
                  <td>{{$data->created_at}}</td>
                  <td class="res-dropdown" style="" align="center">
                  <a data-toggle="tooltip" data-placement="top" title="" href="javascript:" class="btn btn-primary" data-original-title="Edit Country" onclick="edit_record('{{base64_encode($data->id)}}')" ><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a></a>
                  <?php if($data->status=="0"){?>
                <a data-toggle="tooltip" data-placement="top" title="" href="javascript:" class="btn btn-success" data-original-title="Active Country" onclick="statusChange('{{base64_encode($data->id)}}')"><i class="fa fa-check" aria-hidden="true"></i></a>
              <?php }else{?>
              <a data-toggle="tooltip" data-placement="top" title="" href="javascript:" class="btn btn-danger" data-original-title="Deactive Country" onclick="statusChange('{{base64_encode($data->id)}}')"><i class="fa fa-close" aria-hidden="true"></i></a>
            <?php }?>                
                </td>
                </tr>  
                <?php $i++;?>
                @endforeach

                @if($i<2)
                <tr>
                <td>No Country Data</td>
                </tr>
                @endif    
                </tbody> 
                
    </table>
    {!! $country_data->links() !!}    