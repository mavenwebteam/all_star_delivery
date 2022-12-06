<div class="table-responsive">
<table class="table table-bordered table-hover">

                <thead>

                <tr>

                <th>SR.NO.</th>
                
			
                <th>Outlet Name</th>
				  <th>Delivery Start Time</th>
				  <th>Delivery End Time</th>
                <!-- <th>Delivery Slots</th>-->
              
			  <th>Delivery slot duration</th>
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
                  
               
					<td>{{$data->outletname}} </td>
					<td>{{$data->delivery_start_time}} </td>
                  <td>{{$data->delivery_end_time}}</td>
                
                <!--  <td> {{$data->delivery_slots}}</td>-->
				  <td> {{$data->delivery_slot_duration}}</td>
   <td>@if($data->status==0)  <span class="label label-danger">Deactive</span> @else 
				<span class="label label-success">Active</span>
			@endif</td>
                  <td>{{$data->created_at}}</td>

                  <td class="res-dropdown" style="" align="center">

                  <a data-toggle="tooltip" data-placement="top" title="" href="javascript:" class="btn btn-primary" data-original-title="Edit Delivery Slot" onclick="edit_record('{{base64_encode($data->id)}}')" ><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a></a>

                  <a data-toggle="tooltip" data-placement="top" title="" href="javascript:" class="btn btn-warning" data-original-title="View Delivery Slot" onclick="view_record('{{base64_encode($data->id)}}')"><i class="fa fa-eye" aria-hidden="true"></i></a></a>
				
				  
				  

                <?php if($data->status=="0"){?>

                <a data-toggle="tooltip" data-placement="top" title="" href="javascript:" class="btn btn-success" data-original-title="Active Delivery Slot" onclick="statusChange('{{base64_encode($data->id)}}')"><i class="fa fa-check" aria-hidden="true"></i></a>

              <?php }else{?>

              <a data-toggle="tooltip" data-placement="top" title="" href="javascript:" class="btn btn-danger" data-original-title="Deactive Delivery Slot" onclick="statusChange('{{base64_encode($data->id)}}')"><i class="fa fa-close" aria-hidden="true"></i></a>

            <?php }?>

                </td>

                </tr>  

                <?php $i++;?>

                @endforeach



                @if($i<2)

                <tr>

                <td>No Delivery Slot Data</td>

                </tr>

                @endif    

                </tbody> 

                

    </table></div>{!! $userdata->links() !!} Records {{ $userdata->firstItem() }} - {{ $userdata->lastItem() }} of {{ $userdata->total() }} (for page {{ $userdata->currentPage() }} )