
<style>.btn{margin-top:5px}</style>
<div class="table-responsive">
  <table class="table table-bordered table-hover">
    <thead>
       <tr>
          <th>Image</th>
          <th>Title</th>
          <th>Business Category</th>
          <th>Promocode</th>
          <th>Start Date</th>
          <th>End Date</th>
          <th>Discount</th>
          <th class="text-center">Status</th>
          <th style="text-align:center;">Action</th>
       </tr>
    </thead>
    <tbody>
       @if(count($promocodes) > 0)
       @foreach($promocodes as $value)
           <tr>
              <td>
                  @if($value->image &&  file_exists(public_path('/media/banners/thumb/'.$value->banner)))
                  <span class="image_inner" style="margin:0px 5px 5px 0px;">
                        <img src="{{asset('media/promocode/thumb/'.$value->image)}}"  class='imgClass' width='55px',height='55px'>
                  </span>
                  @else
                        <img src="{{ asset('img/dummy.jpg') }}"  class='imgClass' width='55px',height='55px'>
                  @endif
              </td>
              <td>{{ $value->title }}</td>
              <td>
                 {{ object_get($value, 'businessCategory.name_en', '') }}
               </td>
               <td>{{ $value->code }}</td>
               <td>{{ date("d-M-Y", strtotime($value->start_date)) }}</td>
               <td>{{ date("d-M-Y", strtotime($value->end_date)) }}</td>
               <td>{{ object_get($value, 'discount_present','') .'% off upto '.object_get($value, 'cap_limit','') }}</td>
               <td class="text-center">
                 @if($value->status==0)
                 <span class="label label-danger">Deactive</span>
                 @else 
                 <span class="label label-success">Active</span>
                 @endif
              </td>
           
              <td class="res-dropdown" style="" align="center">
               @if(in_array('subAdmin.promocode.edit', $permissionData))
               <a data-toggle="tooltip" data-placement="top" title="Edit Promocode" href="javascript:" class="btn btn-primary" onclick="edit_record('{{base64_encode($value->id)}}')" ><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
               @endif
               @if(in_array('subAdmin.promocode.status', $permissionData))
                 <?php if($value->status=="0"){?>
                 <a data-toggle="tooltip" data-placement="top" href="javascript:" class="btn btn-success" data-original-title="Active Promocode" onclick="statusChange('{{base64_encode($value->id)}}')"><i class="fa fa-check" aria-hidden="true"></i></a>
                 <?php }else{ ?>
                 <a data-toggle="tooltip" data-placement="top" href="javascript:" class="btn btn-danger" data-original-title="Deactive Promocode" onclick="statusChange('{{base64_encode($value->id)}}')"><i class="fa fa-close" aria-hidden="true"></i></a>
                 <?php }?>
               @endif 
               @if(in_array('subAdmin.promocode.destroy', $permissionData))
                 <a data-toggle="tooltip" data-placement="top" href="javascript:" class="btn btn-danger" data-original-title="Remove Promocode" onclick="remove_record('{{base64_encode($value->id)}}')"><i class="fa fa-trash" aria-hidden="true"></i></a>
               @endif
              </td>
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

	{!! $promocodes->links() !!} 

	Records {{ $promocodes->firstItem() }} - {{ $promocodes->lastItem() }} of {{ $promocodes->total() }} (for page {{ $promocodes->currentPage() }} )