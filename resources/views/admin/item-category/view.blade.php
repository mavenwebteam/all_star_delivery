<script type="text/javascript">



    $(document).ready(function () 



    {  $.LoadingOverlay("hide");



    });



    </script>



<table class="table table-bordered table-hover">



<tbody><?php $helper=new App\Helpers;?>



        

		<tr> <th>Image</th><td> @if($catData->image!="")

                        <img src="{{asset('/media/item_category').'/'.$catData->image}}" width="55px" height="55px">

                    @else

                        <img src="{{asset('/media/no-image.png')}}" width="55px" height="55px"> 

                   @endif</td>

		</tr>

        <tr> <th>Business Category Name</th><td><?php $helper=new App\Helpers;?>

 

 {{$helper->GetBusinessCategory($catData->category_id)}}</td></tr>

		  <tr> <th>Item Category Name(en)</th><td>{{$catData->name_en}}</td></tr>
      <tr> <th>Item Category Name(burmese)</th><td>{{$catData->name_burmese}}</td></tr>
		

		<tr> <th>Status</th><td>@if($catData->status == 1) <span class="label label-success">Active</span> @else <span class="label label-danger">Deactive</span> @endif </td></tr>

		<tr> <th>Created At</th><td><?php echo  date('Y-m-d', strtotime($catData->created_at)); ?></td></tr>

		<tr> <th>Updated At</th><td><?php echo  date('Y-m-d', strtotime($catData->updated_at)); ?></td></tr>



    </tbody>



               



  </table>



