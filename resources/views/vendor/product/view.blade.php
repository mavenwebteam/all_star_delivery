<script type="text/javascript">

$(document).ready(function () 

{  $.LoadingOverlay("hide");

});

</script>
@if(isset($productData) && !empty($productData->images))
                    
<div class="slider-container">   
   <div id="myCarousel" class="carousel slide" data-ride="carousel">
     <!-- Indicators -->
 
 
     <!-- Wrapper for slides -->
     <div class="carousel-inner">
      @php $i = 1; @endphp
      @foreach($productData->images as $image)
      @php 
         if($i == 1){ $className = 'active';} else{ $className = ''; } 
         $image = $image->image;
      @endphp 
      <div class="item {{ $className }}">
         @if($image &&  file_exists(public_path('/media/products/'.$image)))
            <img src="{{asset('media/products/'.$image)}}" alt="Los Angeles">
         @else
            <img src="{{ asset('img/dummy.jpg') }}"  class='imgClass' width='55px',height='55px'>
         @endif
      </div>
      @php $i++; @endphp
      @endforeach
     </div>
 
     <!-- Left and right controls -->
     <a class="left carousel-control" href="#myCarousel" data-slide="prev">
       <span class="glyphicon glyphicon-chevron-left"></span>
       <span class="sr-only">Previous</span>
     </a>
     <a class="right carousel-control" href="#myCarousel" data-slide="next">
       <span class="glyphicon glyphicon-chevron-right"></span>
       <span class="sr-only">Next</span>
     </a>
   </div>
 </div>
 @endif
<table class="table table-bordered table-hover">
    <tbody>
       <tr>
          <th>{{ trans('vendor.product_name') }}</th>
          <td>{{$productData->name_en.'('.$productData->name_br.')'}}</td>
       </tr>
       <tr>
          <th>{{ trans('vendor.item_id') }}</th>
          <td>{{$productData->uuid}}</td>
       </tr>
       <tr>
         <th>Ref ID</th>
         <td>{{$productData->ref_id}}</td>
       </tr>
       <tr>
          <th>{{ trans('vendor.categories') }}</th>
          <td>@if($productData->itemCategory) {{ $productData->itemCategory->name_en.'('.$productData->itemCategory->name_burmese.')' }} @endif</td>
       </tr>
       
       <tr>
          <th>{{ trans('vendor.quantity') }}</th>
          <td> {{$productData->total_qty}}</td>
       </tr>
       <tr>
        <th>{{ trans('vendor.available_qty') }}</th>
        <td> {{$productData->available_qty}}</td>
     </tr>
      
       <tr>
          <th>{{ trans('vendor.stock_status') }}</th>
          <td>@if($productData->in_stock == '0') {{ trans('vendor.out_of_stock') }} @else {{ trans('vendor.in_stock') }} @endif</td>
       </tr>
       <tr>
          <th> {{ trans('vendor.price') }} </th>
          <td>{{$productData->price}}</td>
       </tr>
       <tr>
          <th>{{ trans('vendor.offer_price') }}</th>
          <td>{{ $productData->discounted_price }}</td>
       </tr>
       <tr>
          <th>{{ trans('vendor.size') }}</th>
          <td>{{$productData->size}} @if($productData->unit) {{ $productData->unit->name.'('.$productData->unit->code.')' }} @endif</td>
       </tr>
       <tr>
          <th>{{ trans('vendor.product_description') }}(en)</th>
          <td>{!! $productData->description_en !!}</td>
       </tr>
       <tr>
        <th>{{ trans('vendor.product_description') }}(br)</th>
        <td>{!! $productData->description_br !!}</td>
     </tr>
       <tr>
          <th>{{ trans('vendor.status') }}</th>
          <td>@if($productData->status == '0') <span class="label label-danger">{{ trans('vendor.inactive') }}</span> @else 
             <span class="label label-success">{{ trans('vendor.active') }}</span> @endif 
          </td>
       </tr>
       <tr>
          <th>{{ trans('vendor.created_at') }}</th>
          <td><?php echo  date('Y-m-d', strtotime($productData->created_at)); ?></td>
       </tr>
       <tr>
          <th>{{ trans('vendor.updated_at') }}</th>
          <td><?php echo  date('Y-m-d', strtotime($productData->updated_at)); ?></td>
       </tr>
    </tbody>
 </table>

