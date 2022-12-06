<script type="text/javascript">
    $(document).ready(function () 
    {  $.LoadingOverlay("hide");
    });
</script>

<table class="table table-bordered table-hover">
    <tbody>
        <?php $helper=new App\Helpers;?>
        <tr> <th>Image</th><td>@if($storedata->image!="") <img src="{{asset('media/store/'.$storedata->image)}}" style="width:50px;"> @else No Image @endif</td></tr>
        <tr> <th>Logo</th><td>@if($storedata->store_logo!="") <img src="{{asset('media/store/'.$storedata->store_logo)}}" style="width:50px;"> @else No Image @endif</td></tr>
     
        <tr> <th>Name</th><td>{{$storedata->name}} ({{$storedata->name_burmese}})</td></tr>
        <tr> <th>Email</th><td>{{$storedata->email}}</td></tr>
        <tr> <th>Contact No</th><td>{{$storedata->country_code.$storedata->mobile}}</td></tr>
        <tr> <th>Vendor Name</th><td> {{$storedata->username}}</td></tr>
        <tr> <th>Business Category</th><td>{{$helper->GetBusinessCategory($storedata->business_category_id)}} </td></tr>
       
        <tr> <th>Address</th><td> {{$storedata->address}}</td></tr>
        <tr> <th>Description</th><td> {{$storedata->description}}( {{$storedata->description_burmese}})</td></tr>
        <tr> <th>Status</th><td> @if($storedata->status==0) <span class="label label-danger">Deactive</span> @else <span class="label label-success">Active</span> @endif</td></tr>
		<tr> <th>Open Time</th><td> {{$storedata->open_at}}</td></tr>
		<tr> <th>Close Time</th><td> {{$storedata->close_at}}</td></tr>
		<tr> <th>Closing Day</th><td>
		@if(!empty($storedata->closing_day)) {{$helper->getDay($storedata->closing_day)}} @endif
        </td></tr>
        <tr> <th>Comission</th><td>{{$storedata->comission}}%</td>
        <tr> <th>Created</th><td>{{$storedata->created_at}}</td></tr>
		<tr> <th>Updated</th><td>{{$storedata->updated_at}}</td></tr>
    </tbody>
</table>

