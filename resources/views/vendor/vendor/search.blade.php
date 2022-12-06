<table class="table table-bordered table-hover">

                <thead>

                <tr>

                <th>SR.NO.</th>
                <th>Image</th>
                <th>Unique Id</th>
                 <th>Email</th>
				  <th>Rating</th>
              
                  <th>Status</th>
                  <th>Created</th>
                   <th style="text-align:center;">Action</th>

                </tr>

                </thead>

                <tbody>

                <?php $i = 1;?>

                  @foreach($userdata as $data)

                <tr>

                  <td>{{$i}}</td>
                  <td>
                     @if($data->profile_pic!="")
                        <img src="{{asset('/media/users').'/'.$data->profile_pic}}" width="55px" height="55px">
                    @else
                        <img src="{{asset('/media/no-image.png')}}" width="55px" height="55px"> 
                   @endif
                  </td>
                  <td>{{$data->uniq_id}}</td>


                  <td>{{$data->email}}</td>
				   <td>{{$data->rating}}</td>
                
                  

                  <td><?php $helper=new App\Helpers;?>{{$helper->GetUserStatus($data->status)}}</td>

                  <td>{{$data->created_at}}</td>

                  <td class="res-dropdown" style="" align="center">

                  <a data-toggle="tooltip" data-placement="top" title="" href="javascript:" class="btn btn-primary" data-original-title="Edit Vendor" onclick="edit_record('{{base64_encode($data->id)}}')" ><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a></a>

                  <a data-toggle="tooltip" data-placement="top" title="" href="javascript:" class="btn btn-warning" data-original-title="View Vendor" onclick="view_record('{{base64_encode($data->id)}}')"><i class="fa fa-eye" aria-hidden="true"></i></a></a>

                <?php if($data->status=="0"){?>

                <a data-toggle="tooltip" data-placement="top" title="" href="javascript:" class="btn btn-success" data-original-title="Active Vendor" onclick="statusChange('{{base64_encode($data->id)}}')"><i class="fa fa-check" aria-hidden="true"></i></a>

              <?php }else{?>

              <a data-toggle="tooltip" data-placement="top" title="" href="javascript:" class="btn btn-danger" data-original-title="Deactive Vendor" onclick="statusChange('{{base64_encode($data->id)}}')"><i class="fa fa-close" aria-hidden="true"></i></a>

            <?php }?>

                </td>

                </tr>  

                <?php $i++;?>

                @endforeach



                @if($i<2)

                <tr>

                <td>No Vendor Data</td>

                </tr>

                @endif    

                </tbody> 

                

    </table>{!! $userdata->links() !!} Records {{ $userdata->firstItem() }} - {{ $userdata->lastItem() }} of {{ $userdata->total() }} (for page {{ $userdata->currentPage() }} )