<table class="table table-bordered table-hover">
  <thead>
     <tr>
        <th>Vendor Name</th>
        <th>Businee Category Name</th>
        <th>Store Name</th>
        <th>Image</th>
        <th>Total Order</th>
        <th>Total Amount</th>
        <th>Total Commission</th>
        <th class="text-center">Status</th>
        <th>Created</th>
        <th style="text-align:center;">Action</th>
     </tr>
  </thead>
  <tbody>
     @if(count($storedata) > 0)
     @foreach($storedata as $data)
      <tr>
         <td>
            <a  href="javascript:"onclick="view_user_record('{{base64_encode($data->user_id)}}')" >{{$data->vendor->first_name.' '.$data->vendor->last_name}}</a>
         </td>
         <?php $helper=new App\Helpers;?>
         <td >{{ $data->businessCategory->name_en }} ({{ $data->businessCategory->name_burmese }})</td>
         <td>{{$data->name}}( {{$data->name_burmese}} )</td>
         <td>@if($data->image!="") <img src="{{asset('media/store/'.$data->image)}}" style="width:50px;"> @else No Image @endif</td>
         <?php $store_data=$helper->GetStoreOrdersDetails($data->id); ?>
         <td>{{$store_data['total_order']}}</td>
         <td>{{$store_data['total_amount']}}</td>
         <td>{{$store_data['total_commission']}}</td>
         <td class="text-center">@if($data->status==0)  <span class="label label-danger">Deactive</span> @else 
            <span class="label label-success">Active</span>
            @endif
         </td>
         <td>{{$data->created_at}}</td>
         <td class="res-dropdown" style="" align="center">
            @if(in_array('subAdmin.edit-store.edit', $permissionData))
            <a data-toggle="tooltip" data-placement="top" title="" href="{{ URL::to('/sub-admin/store/edit-store') }}/{{base64_encode($data->id)}}" class="btn btn-primary" data-original-title="Edit Store"  ><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
            @endif
            @if(in_array('subAdmin.view-store.show', $permissionData))
            <a data-toggle="tooltip" data-placement="top" title="" href="javascript:" class="btn btn-warning" data-original-title="View Store" onclick="view_record('{{base64_encode($data->id)}}')"><i class="fa fa-eye" aria-hidden="true"></i></a>
            @endif
            {{-- view store menu items --}}
            @if(in_array('subAdmin.storeItem', $permissionData))
            <a data-toggle="tooltip" data-placement="top" title="" href="{{ route('subAdmin.storeItem', $data->id) }}" target="_blank" class="btn btn-info" data-original-title="Store Menu Items"><i class="fa fa-list-alt" aria-hidden="true"></i></a>
            @endif 
            @if(in_array('subAdmin.store.status', $permissionData))
            <?php if($data->status=="0"){?><a data-toggle="tooltip" data-placement="top" title="" href="javascript:" class="btn btn-success" data-original-title="Active Store" onclick="statusChange('{{base64_encode($data->id)}}')"><i class="fa fa-check" aria-hidden="true"></i></a>
            <?php }else{?><a data-toggle="tooltip" data-placement="top" title="" href="javascript:" class="btn btn-danger" data-original-title="Deactive Store" onclick="statusChange('{{base64_encode($data->id)}}')"><i class="fa fa-close" aria-hidden="true"></i></a><?php }?>
            @endif
            @if(in_array('subAdmin.edit-store-post.store', $permissionData))
            <?php if($data->is_approved == 0){?>
            <a data-toggle="tooltip" data-placement="top" title="" href="javascript:" class="btn btn-info" data-original-title="Aporve Store" onclick="aproveStore('{{base64_encode($data->id)}}')"><i class="fa fa-thumbs-o-up" aria-hidden="true"></i></a>
            <?php } ?>
            @endif
         </td>
      </tr>
     @endforeach
     @else
     <tr>
        <td>Data not found</td>
     </tr>
     @endif    
  </tbody>
</table>
{!! $storedata->links() !!}   
<style>	span.pagecounts {    display: inline-block;    width: 100%;			}	</style>
<span class="pagecounts"> Records {{ $storedata->firstItem() }} - {{ $storedata->lastItem() }} of {{ $storedata->total() }} (for page {{ $storedata->currentPage() }} )  </span>