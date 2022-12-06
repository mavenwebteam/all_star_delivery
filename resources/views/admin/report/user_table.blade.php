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
                  <td>{{'+'. $data->country_code}}-{{$data->mobile}}</td>
                  <td class="text-center">
                     @if($data->status==0)  <span class="label label-danger">Deactive</span> @else 
                     <span class="label label-success">Active</span>
                     @endif
                  </td>
                  <td>{{$data->created_at}}</td>
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