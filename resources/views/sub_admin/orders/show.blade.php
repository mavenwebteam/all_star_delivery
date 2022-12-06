{{-- initilizwe helpers --}}
@php $helper = new App\Helpers\Helper; @endphp

 <table class="table table-bordered table-hover">
    <tbody>
        <tr>
            <th>Order Id</th>
            <td colspan="4">{{ $order->id }}</td>
        </tr>
        <tr>
            <th>Item Qty</th>
            <td colspan="4">{{  count(object_get($order,'orderItems', [])) }}</td>
        </tr>
        <tr>
            <th>Promocode</th>
            <td colspan="4">{{ $order->promocode }}</td>
        </tr>
        <tr>
            <th>Order Amount</th>
            <td colspan="4">{{ $helper->currencyFormat($order->amount) }}</td>
        </tr>
        <tr>
            <th>Delivery Person</th>
            <td colspan="4">{{ object_get($order, 'driver.first_name', '').' '.object_get($order, 'driver->last_name', '') }}</td>
        </tr>
        <tr>
            <th>Delivery Person Contact</th>
            <td colspan="4">
                {{ object_get($order, 'driver.mobile', '') }}<br/>
                {{ object_get($order, 'driver.email', '') }}
            </td>
        </tr>
        <tr>
            <th>Delivery Fee</th>
            <td colspan="4"> {{ $helper->currencyFormat($order->delivery_fee) }} </td>
        </tr>
        <tr>
            <th>Status</th>
            <td colspan="4">{{$order->status}}</td>
        </tr>
        @if($order->status == 'CANCELLED')
        <tr>
            <th>Reason of cancellation</th>
            <td colspan="4">{{ $order->reason_of_cancel }}</td>
        </tr>
        @endif
        <tr>
            <th>Order Date</th>
            <td colspan="4">{{$order->created_at}}</td>
        </tr>
        <tr>
            <th>Store Name</th>
            <td colspan="4">
                {{ object_get($order, 'store.name', '')." " }}
            </td>
        </tr>
        <tr>
            <th>Order Rating</th>
            <td colspan="4">
                @php  $storeRating = object_get($order, 'store_rating', 0); @endphp
                @for($i = 0; $i < $storeRating ; $i++)
                    <i class="fa fa-star text-success" aria-hidden="true"></i>
                @endfor

                @for($i = 0; $i < (5-$storeRating); $i++)
                    <i class="fa fa-star-o" aria-hidden="true"></i>
                @endfor
            </td>
        <tr>
            <td colspan="5" class="text-center bg-info"><b>Order Items</b></td>
        </tr>
        @php $i = 1; @endphp
        @if(isset($order->orderItems))
            <tr>
                <th>S.No</th>
                <th>Item Name</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                {{-- <th>Rating</th> --}}
            </tr>
            @foreach( object_get($order, 'orderItems', []) as $item)
            <tr>
                <td>{{ $i++ }}.</td>
                <td>
                    {{ object_get($item, 'product.name_en', '') }}
                </td>
                <td>{{ $item->qty }}</td>
                <td>{{ $helper->currencyFormat($item->price) }}</td>
                {{-- <td>
                    @if(!empty($item->product_rating))
                        @for($i = 0; $i < $item->product_rating; $i++)
                            <i class="fa fa-star text-success" aria-hidden="true"></i>
                        @endfor

                        @for($i = 0; $i < (5-$item->product_rating); $i++)
                            <i class="fa fa-star-o" aria-hidden="true"></i>
                        @endfor
                    @endif
                </td> --}}
            </tr>        
            @endforeach
        @endif
            
    </tbody> 
  </table> 