<table class="table table-bordered table-hover">
   <thead>
      <tr>
           <th>Ieam Category</th>
           <th>Image</th>
           <th>Name</th>
           <th class="text-center">Size</th>
           <th class="text-right">Price</th>
           <th class="text-right">Discount(in %)</th>
           <th class="text-right">Total Qty</th>
           <th class="text-right">Available Qty</th>
           <th class="text-right">Stock Status</th>
      </tr>
   </thead>
   <tbody>
      @if(count($products) > 0)
      @foreach($products as $product)
       <tr>
           <td>
               {{ $product->itemCategory->name_en.'('.$product->itemCategory->name_burmese.')' }}
           </td>
           <td>
               @if(isset($product->images[0]->image) && file_exists(public_path('/media/products/thumb/'.$product->images[0]->image)) )
                  <img src="{{ asset('media/products/thumb/'.$product->images[0]->image) }}" alt="img" height="80" width="70">
               @endif
           </td>
           <td>{{ $product->name_en.'('.$product->name_br.')' }}</td>
           <td class="text-center">{{ $product->size }} {{ $product->unit->code }}</td>
           <td class="text-right">{{ $product->price }}</td>
           <td class="text-right">{{ $product->discount_present }}</td>
           <td class="text-right">{{ $product->total_qty }}</td>
           <td class="text-right">{{ $product->available_qty }}</td>
           <td class="text-center">
               @if($product->in_stock==0)
               <span class="label label-danger">Out of stock</span> 
               @else 
               <span class="label label-success">In Stock</span>
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
{!! $products->links() !!}   