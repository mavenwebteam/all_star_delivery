<table class="table table-bordered table-hover">

                <thead>

                <tr>

                <th>SR.NO.</th>

                 <th>Image</th>

                  <th>Name</th>
 <th>Brand Name</th>

                  

                  <th>Status</th>

                  <th>Created</th>

                  <th style="text-align:center;">Action</th>

                </tr>

                </thead>

                <tbody>

                <?php $i = 1;?>

                  @foreach($catedata as $data)

                <tr>

                  <td>{{$i}}</td>

                  <td>@if($data->image!="") <img src="{{asset('media/category/'.$data->image)}}" style="width:50px;"> @else   <img src="{{asset('/media/no-image.png')}}" width="70px" height="70px"> @endif</td>

                  <td>{{$data->name}}</td>
 <td>
  <?php $helper=new App\Helpers;?>
 
 {{$helper->GetBrand(explode(',',$data->brand_id))}}
 </td>


                  <td>@if($data->status==0)  <span class="label label-danger">Deactive</span> @else 
				<span class="label label-success">Active</span>
			@endif</td>

                  <td>{{$data->created_at}}</td>

                  <td class="res-dropdown" style="" align="center">

                  <a data-toggle="tooltip" data-placement="top" title="" href="javascript:" class="btn btn-primary" data-original-title="Edit Category" onclick="edit_record('{{base64_encode($data->id)}}')" ><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a></a>
				<a data-toggle="tooltip" data-placement="top" title="" href="javascript:" class="btn btn-warning" data-original-title="View Category" onclick="view_record('{{base64_encode($data->id)}}')"><i class="fa fa-eye" aria-hidden="true"></i></a>
                  <?php if($data->status=="0"){?>

                <a data-toggle="tooltip" data-placement="top" title="" href="javascript:" class="btn btn-success" data-original-title="Active Category" onclick="statusChange('{{base64_encode($data->id)}}')"><i class="fa fa-check" aria-hidden="true"></i></a>

              <?php }else{?>

              <a data-toggle="tooltip" data-placement="top" title="" href="javascript:" class="btn btn-danger" data-original-title="Deactive Category" onclick="statusChange('{{base64_encode($data->id)}}')"><i class="fa fa-close" aria-hidden="true"></i></a>

            <?php }?>
<a data-toggle="tooltip" data-placement="top" title="" href="javascript:" class="btn btn-danger" data-original-title="Remove Category" onclick="remove_record('{{base64_encode($data->id)}}')"><i class="fa fa-trash" aria-hidden="true"></i></a>
                

                </td>

                </tr>  

                <?php $i++;?>

                @endforeach



                @if($i<2)

                <tr>

                <td>No Category Data</td>

                </tr>

                @endif    

                </tbody> 

               

    </table>

    </table>{!! $catedata->links() !!} <style>	span.pagecounts {    display: inline-block;    width: 100%;			}	</style>	<span class="pagecounts">Records {{ $catedata->firstItem() }} - {{ $catedata->lastItem() }} of {{ $catedata->total() }} (for page {{ $catedata->currentPage() }} ) </span>