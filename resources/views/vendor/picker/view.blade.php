<script type="text/javascript">

    $(document).ready(function () 

    {  $.LoadingOverlay("hide");

    });

    </script>

<table class="table table-bordered table-hover">

<tbody><?php $helper=new App\Helpers;?>

        
		<tr> <th>Image</th><td> @if($userdata->profile_pic!="")
                        <img src="{{asset('/media/users').'/'.$userdata->profile_pic}}" width="55px" height="55px">
                    @else
                        <img src="{{asset('/media/no-image.png')}}" width="55px" height="55px"> 
                   @endif</td>
		</tr>
        <tr> <th>First Name</th><td>{{$userdata->first_name}}</td></tr>
		 <tr> <th>Last Name</th><td>{{$userdata->last_name}}</td></tr>

        <tr> <th>Email</th><td>{{$userdata->email}}</td></tr>

        <tr> <th>Type</th><td> @if(!empty($userdata->type)){{$helper->GetUserType($userdata->type)}} @endif</td></tr>
        <tr> <th>Mobile</th><td>{{$userdata->country_code}}-{{$userdata->mobile}}</td></tr>
<tr> <th>Status</th><td>@if($userdata->status==0)  <span class="label label-danger">Deactive</span> @else 
				<span class="label label-success">Active</span>
			@endif </td></tr>
<tr> <th>Notification Status</th><td>@if($userdata->is_notification == 1) {{'Yes'}} @else {{"No"}} @endif </td></tr>
<tr> <th>Online Status</th><td>@if($userdata->is_online == 1) {{'Yes'}} @else {{"no"}} @endif </td></tr>
        <tr> <th>Created</th><td>{{$userdata->created_at}}</td></tr>

       



        

    </tbody>

               

  </table>

