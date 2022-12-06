<script type="text/javascript">
    $(document).ready(function ()
    {  
        $.LoadingOverlay("hide");
    });
 </script>
 <table class="table table-bordered table-hover">
    <tbody>
       <?php $helper=new App\Helpers;?>
       <tr>
          <th>Image</th>
          <td> @if($businessCategoryData->image!="")
             <img src="{{asset('/media/business_category').'/'.$businessCategoryData->image}}" width="55px" height="55px">
             @else
             <img src="{{asset('/media/no-image.png')}}" width="55px" height="55px"> 
             @endif
          </td>
       </tr>
       <tr>
          <th>Name (en)</th>
          <td>{{$businessCategoryData->name_en}}</td>
       </tr>
       <tr>
          <th>Name (Burmese)</th>
          <td>{{$businessCategoryData->name_burmese}}</td>
       </tr>
       <tr>
          <th>Status</th>
          <td>@if($businessCategoryData->status == 1) <span class="label label-success">Active</span> @else <span class="label label-danger">Deactive</span> @endif </td>
       </tr>
       <tr>
          <th>Created At</th>
          <td><?php echo  date('Y-m-d', strtotime($businessCategoryData->created_at)); ?></td>
       </tr>
       <tr>
          <th>Updated At</th>
          <td><?php echo  date('Y-m-d', strtotime($businessCategoryData->updated_at)); ?></td>
       </tr>
    </tbody>
 </table>