<style>.btn{margin-top:5px}</style>
<div class="table-responsive">
  <table class="table table-bordered table-hover">
    <thead>
      <tr>
        <th>{{ __('vendor.order_id') }}</th>
        <th>{{ __('vendor.item_qty') }}</th>
        <th>{{ __('vendor.amount') }}</th>
        <th>Order details</th>
        <th>{{ __('vendor.special_instructions') }}</th>
        <th class="text-center">{{ __('vendor.status') }}</th>
        <th>{{ __('vendor.date') }}</th>
        <th class="text-center">{{ __('vendor.action') }}</th>
      </tr>
    </thead>
    <tbody>
      @php $itemString = ''; @endphp
      @if(count($orders) > 0)
        @foreach($orders as $order)
          <tr>
            <td>{{ $order->order_id }}</td>
            <td>{{  count($order->orderItems) }}</td>
            <td>{{  $order->amount }}</td>
            <td>
              @php $orderItems = object_get($order, 'orderItems', []); @endphp
              @foreach($orderItems as $item)
              {{ $item->qty.'X'.object_get($item, 'product.name_en', '') }} {{ ',' }} 
              @endforeach
            </td>
            <td>{{ $order->instructions }}</td>          
            <td class="text-center"> 
              @switch($order->status)
                @case('ORDERED')
                  <span class="label label-warning">Ordered</span>
                  @break
                @case('ACCEPTED')
                  <span class="label label-success">Accepted</span>
                  @break
                @case('ON_THE_WAY')
                  <span class="label label-info">On The Way</span>
                  @break
                @default
                  <span class="label label-danger">Check Details</span>
              @endswitch
            </td>
            <td>{{$order->created_at}}</td>
           
            <td class="res-dropdown" style="" align="center">
              @if($order->status == 'ORDERED')
                <button data-toggle="tooltip" data-placement="top" orderId="{{ $order->id }}" state="Accept" title="" class="orderStatusBtn btn btn-success" data-original-title="{{ __('vendor.accept_order') }}"><i class="fa fa-check" aria-hidden="true"></i></button>
                <button data-toggle="tooltip" data-placement="top" orderId="{{ $order->id }}" state="Cancel" title="" class="orderStatusBtn btn btn-danger" data-original-title="{{ __('vendor.cancel_order') }}"><i class="fa fa-times" aria-hidden="true"></i></button>
              @endif
              <button data-toggle="tooltip" data-placement="top" title="" class="viewOrderBtn btn btn-info" data-original-title="{{ __('vendor.view_order') }}" orderId="{{ $order->id }}"><i class="fa fa-eye" aria-hidden="true"></i></button>
            </td>
          </tr>
        @endforeach
      @else  
        <tr>
          <td colspan="13">{{ __('vendor.data_not_found') }}</td>
        </tr>
      @endif    
    </tbody> 
  </table> 
</div>
	{!! $orders->links() !!} 

	Records {{ $orders->firstItem() }} - {{ $orders->lastItem() }} of {{ $orders->total() }} (for page {{ $orders->currentPage() }} )