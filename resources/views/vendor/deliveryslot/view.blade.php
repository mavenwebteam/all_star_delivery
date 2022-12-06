<script type="text/javascript">

    $(document).ready(function () 

    {  $.LoadingOverlay("hide");

    });

    </script>

<table class="table table-bordered table-hover">

<tbody><?php $helper=new App\Helpers;?>

        
		
        
		 <tr> <th>Outlet Name</th><td>{{$userdata->outletname}}</td></tr>

        <tr> <th>Delivery Start Time</th><td>{{$userdata->delivery_start_time}}</td></tr>

        <tr> <th>Delivery End Time</th><td> {{$userdata->delivery_end_time}}</td></tr>
        <tr> <th>Delivery Slots</th><td>{{$userdata->delivery_slots}}</td></tr>
		   <tr> <th>Delivery slot duration</th><td>{{$userdata->delivery_slot_duration}}</td></tr>
<tr> <th>Status</th><td>@if($userdata->status == 1) {{'Active'}} @else {{"Deactive"}} @endif </td></tr>

        <tr> <th>Created</th><td>{{$userdata->created_at}}</td></tr>
 <tr> <th>Updated</th><td>{{$userdata->updated_at}}</td></tr>
       



        

    </tbody>

               

  </table>

