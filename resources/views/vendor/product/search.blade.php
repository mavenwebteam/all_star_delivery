
<style>.btn{margin-top:5px}</style>
<div class="table-responsive">
  <table class="table table-bordered table-hover">
    <thead>
    <tr>
      <th>{{ __('vendor.item_id') }}</th>
      <th>{{ __('vendor.image') }}</th>
      <th>{{ __('vendor.title') }}</th>
      <th>{{ __('vendor.summary') }}</th>
      <th>{{ __('vendor.total_qty') }}</th>
      <th>{{ __('vendor.available_qty') }}</th>
      <th>{{ __('vendor.price') }}</th>
      <th>{{ __('vendor.offer_price') }}</th>
      <th>{{ __('vendor.item_menu_category') }}</th>
      <th class="text-center">{{ __('vendor.status') }}</th>
      <th>{{ __('vendor.created_at') }}</th>
      <th>{{ __('vendor.stock') }}</th>
      <th class="text-center">{{ __('vendor.action') }}</th>
    </tr>
    </thead>
    <tbody>
      {{-- @php
      dd($products)
      @endphp --}}
      @if(count($products) > 0)
        @foreach($products as $data)
          <tr>
            <td>{{ $data->uuid }}</td>
            <td>
              @php $image = object_get($data,'images',[]); @endphp
             
              @if(count($image))
                <img src="{{ asset('media/products/thumb/'.$data->images[0]->image) }}" alt="" height="50" width="50"/> 
              @else
                <img src="{{ asset('img/dummy.jpg') }}" alt="" height="50" width="50"/> 
              @endif
            </td>
            <td>{{ $data->name_en."(".$data->name_br.")" }}</td>
            <td>{!! substr($data->description_en,0, 100) !!}</td>
            <td>{{$data->total_qty}}</td>
            <td>{{$data->available_qty}}</td>
            <td>{{$data->price}}</td>
            <td>{{$data->discounted_price}}</td>
            <td>{{ object_get($data,'itemCategory.name_en', '') }}</td>
            <td class="text-center"> 
              @if($data->status=='0')  <span class="label label-danger">Deactive</span>
              @else 
                <span class="label label-success">Active</span>
              @endif
            </td>
            <td>{{$data->created_at}}</td>
            <td>
              <div class="material-switch pull-right pt">
                <input @if($data->in_stock == 1) checked @endif id="switchOptionSuccess @php echo $data->id @endphp" name="someSwitchOption001" type="checkbox" onclick="changeStockStates('{{base64_encode($data->id)}}')"/>
                <label for="switchOptionSuccess @php echo $data->id @endphp" class="label-success"></label>
              </div>
            </td>
            <td class="res-dropdown" style="" align="center">
              <a data-toggle="tooltip" data-placement="top" title="" href="javascript:" class="btn btn-primary" data-original-title="{{ trans('vendor.edit_product') }}" onclick="edit_record('{{$data->id}}')" ><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
              <a data-toggle="tooltip" data-placement="top" title="" href="javascript:" class="btn btn-warning" data-original-title="{{ trans('vendor.view_product') }}" onclick="view_record('{{base64_encode($data->id)}}')"><i class="fa fa-eye" aria-hidden="true"></i></a>
              @if($data->status=="0")
                <a data-toggle="tooltip" data-placement="top" title="" href="javascript:" class="btn btn-success" data-original-title="{{ trans('vendor.active_product') }}" onclick="statusChange('{{base64_encode($data->id)}}')"><i class="fa fa-check" aria-hidden="true"></i></a>
              @else
                <a data-toggle="tooltip" data-placement="top" title="" href="javascript:" class="btn btn-danger" data-original-title="{{ trans('vendor.inactive_product') }}" onclick="statusChange('{{base64_encode($data->id)}}')"><i class="fa fa-close" aria-hidden="true"></i></a>
              @endif
              <a data-toggle="tooltip" data-placement="top" title="" href="javascript:" class="btn btn-danger" data-original-title="{{ trans('vendor.remove_product') }}" onclick="remove_record('{{base64_encode($data->id)}}')"><i class="fa fa-trash" aria-hidden="true"></i></a>
            </td>
          </tr>
        @endforeach
      @else  
        <tr>
          <td colspan="13">Data Not Found</td>
        </tr>
      @endif    
    </tbody> 
  </table> 
</div>

	{!! $products->links() !!} 

	Records {{ $products->firstItem() }} - {{ $products->lastItem() }} of {{ $products->total() }} (for page {{ $products->currentPage() }} )