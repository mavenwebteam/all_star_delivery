<script type="text/javascript">

$(document).ready(function () 

{  $.LoadingOverlay("hide");

});

</script>

<table class="table table-bordered table-hover">
    <tbody>
       <tr>
         <th>Image</th>
         <td>
            @if(isset($driverData))
               @php
               $image= $driverData->profile_pic;
               @endphp                    
               @if($image &&  file_exists(public_path('/media/users/thumb/'.$image)))
               <span class="image_inner" style="margin:0px 5px 5px 0px;">
                     <img src="{{asset('media/users/thumb/'.$image)}}"  class='imgClass' width='55px',height='55px'>
               </span>
               @else
                     <img src="{{ asset('img/avatar.png') }}"  class='imgClass' width='55px',height='55px'>
               @endif
            @endif
         </td>
       </tr>
      <tr>
         <th>Name</th>
         <td>{{ $driverData->first_name.' '.$driverData->last_name }}</td>
      </tr>
      <tr>
         <th>Driver Id</th>
         <td>{{ $driverData->uu_id }}</td>
      </tr>
      <tr>
        <th>Email</th>
        <td>{{ $driverData->email }} @if($driverData->email_verify == 'yes') <b>(verified)</b> @endif</td>
      </tr>
      <tr>
         <th>Mobile</th>
         <td>{{ $driverData->mobile }} @if($driverData->is_mobile_verify == '1') <b>(verified)</b> @endif</td>
      </tr>
      
      @if($driverData->vehicle)
         <tr>
            <th>Vehicle Type</th>
            <td>{{ $driverData->vehicle->vehicle_type }}</td>
         </tr>
         @if($driverData->vehicle->vehicle_type == 'Motorbike')
            <tr>
               <th>Vehicle Model</th>
               <td>{{ $driverData->vehicle->model }}</td>
            </tr>
            <tr>
               <th>Vehicle Number</th>
               <td>
                  {{ $driverData->vehicle->vehicle_num }}  
                  @php
                  $numImage= $driverData->vehicle->vehicle_num_img;
                  @endphp                    
                  @if($numImage &&  file_exists(public_path('/media/vehicle/'.$numImage)))
                     <span class="image_inner" style="margin:0px 5px 5px 0px;">
                     <img src="{{asset('media/vehicle/'.$numImage)}}"  class='imgClass' width='55px',height='55px'>
                  </span>
                  @endif
               </td>
            </tr>
            <tr>
               <th>Licence Number</th>
               <td>
                  {{ $driverData->vehicle->licence_num }}
                  @php
                  $licenceImage= $driverData->vehicle->licence_img;
                  @endphp                    
                  @if($licenceImage &&  file_exists(public_path('/media/vehicle/'.$licenceImage)))
                     <span class="image_inner" style="margin:0px 5px 5px 0px;">
                     <img src="{{asset('media/vehicle/'.$licenceImage)}}"  class='imgClass' width='55px',height='55px'>
                  </span>
                  @endif
               </td>
            </tr>
         @endif
      @endif
       <tr>
          <th>Status</th>
          <td>@if($driverData->status == '0') <span class="label label-danger">Deactive</span> @else 
             <span class="label label-success">Active</span> @endif 
          </td>
       </tr>
       <tr>
          <th>Created At</th>
          <td><?php echo  date('Y-m-d', strtotime($driverData->created_at)); ?></td>
       </tr>
       <tr>
          <th>Updated At</th>
          <td><?php echo  date('Y-m-d', strtotime($driverData->updated_at)); ?></td>
       </tr>
    </tbody>
 </table>

