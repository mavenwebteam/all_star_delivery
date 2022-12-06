<style>.btn{margin-top:5px}</style>

{{-- initilizwe helpers --}}
@php $helper = new App\Helpers\Helper; @endphp


<div class="table-responsive">
  <table class="table table-bordered table-hover">
    <thead>
    <tr>
      <th>Order Id</th>
      <th>Store Id</th>
      <th>Store Name</th>
      <th>Item Qty</th>
      <th>Order Amount</th>
      <th>Delivery Person</th>
      <th>Delivery fee</th>
      <th class="text-center">Order Status</th>
      <th>Order Date(d-m-y)</th>
      <th class="text-center">Action</th>
    </tr>
    </thead>
    <tbody>
      @if(count($orders) > 0)
        @foreach($orders as $order)
          <tr>
            <td>{{ $order->order_id }}</td>
            <td>{{ object_get($order, 'store.id', 'Not found') }}</td>
            <td>{{ object_get($order, 'store.name', 'Not found') }}</td>
            <td>{{ count(object_get($order, 'orderItems', [])) }}</td>
            <td>{{  $helper->currencyFormat($order->amount) }}</td>
            <td>
              {{ object_get($order, 'driver.first_name', 'Not Found') }} <br/>
              {{ object_get($order, 'driver.mobile', '') }}
            </td>
            <td>{{  $helper->currencyFormat($order->delivery_fee) }}</td>
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
                @case('DELIVERED')
                  <span class="label label-success">Delivered</span>
                  @break
                @default
                  <span class="label label-warning">{{ str_replace("_", " ", $order->status) }}</span>
              @endswitch
            </td>
            <td>{{ date('d-M-Y h:i a', strtotime($order->created_at))}}</td>           
            <td class="res-dropdown" style="" align="center">
              @if($order->status != 'CANCELLED')            
                <button data-toggle="tooltip" data-placement="top" orderId="{{ $order->id }}" state="Cancel" title="" class="orderStatusBtn btn btn-danger" data-original-title="{{ __('vendor.cancel_order') }}"><i class="fa fa-times" aria-hidden="true"></i></button>
              @endif
              <button data-toggle="tooltip" data-placement="top" title="" class="editOrderBtn btn btn-primary" data-original-title="Edit Order" orderId="{{ $order->id }}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>

              <button data-toggle="tooltip" data-placement="top" title="" class="viewOrderBtn btn btn-info" data-original-title="view order" orderId="{{ $order->id }}"><i class="fa fa-eye" aria-hidden="true"></i></button>

             
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
{!! $orders->links() !!} Records {{ $orders->firstItem() }} - {{ $orders->lastItem() }} of {{ $orders->total() }} (for page {{ $orders->currentPage() }} )