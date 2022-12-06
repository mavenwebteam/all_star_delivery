<script type="text/javascript">
    $(document).ready(function () 
    {  $.LoadingOverlay("hide");
    });
</script>

<table class="table table-bordered table-hover">
    <thead>
    <tr>
    <th>SR.NO.</th>
    <th>OrderId</th>
    <th>Product Image</th>
    <th>Product Name</th>
    <th>Qty</th>
    <th>Price</th>
    <th>Subtotal</th>
   
    </tr>
    </thead>
    <tbody>
    <?php $i = 1;?>
      @foreach($orderitems as $data)
    <tr>
      <td>{{$i}}</td>
      <td>{{$data->order_id}}</td>
      <?php
      $pimg=new App\Models\Productimages;
      $prd_img_data = $pimg->where('product_id',$data->product_id)->first();
      $image="";
      if(isset($prd_img_data) && $prd_img_data->image!=""){
          $image=URL::to('/media/products').'/'.$prd_img_data->image;
      } 
      ?>
      <td>@if($image!="") <img src="{{ $image}}" style="width:50px;"> @else No Image @endif</td>

      <td>{{$data->name}}</td>
      <td>{{$data->quantity}}</td>
      <td>{{$data->price}}</td>
      <?php $subtotal=$data->quantity * $data->price; ?>
      <td>{{$subtotal}}</td>
      
    </tr>  
    <?php $i++;?>
    @endforeach
    @if($i<2)
    <tr>
    <td>No Product Data</td>
    </tr>
    @endif    
    </tbody> 
    </table>