{{-- <th>Image</th>
          <td>
            @if (!empty($userData->profile_pic) && file_exists(public_path('media/users/'.$userData->profile_pic)))
             <img src="{{asset('/media/users').'/'.$userData->profile_pic}}" width="55px" height="55px">
             @else
             <img src="{{asset('img/avatar.png')}}" width="55px" height="55px"> 
             @endif
          </td> --}}
 <table class="table table-bordered table-hover">
    <tbody>
        <tr>
            <th>{{ __('vendor.order_id') }}</th>
            <td colspan="3">{{ $order->order_id }}</td>
        </tr>
        <tr>
            <th>{{ __('vendor.item_qty') }}</th>
            <td colspan="3">{{  count($order->orderItems) }}</td>
        </tr>
        <tr>
            <th>{{ __('vendor.amount') }}</th>
            <td colspan="3">{{ $order->amount }}</td>
        </tr>
        <tr>
            <th>{{ __('vendor.special_instructions') }}</th>
            <td colspan="3">{{ $order->instructions }}</td>
        </tr>
        <tr>
            <th>{{ __('vendor.order_date') }}</th>
            <td colspan="3">{{$order->created_at}}</td>
        </tr>
        <tr>
            <th>{{ __('vendor.status') }}</th>
            <td colspan="3">{{$order->status}}</td>
        </tr> 
        @if($order->status == "CANCELLED")
        <tr>
            <th>{{ __('vendor.cancel_reson') }}</th>
            <td colspan="3">{{ $order->reason_of_cancel }}</td>
        </tr>
        @endif
        <tr>
            <td colspan="4" class="text-center bg-info"><b>{{ __('vendor.order_items') }}</b></td>
        </tr>
        @php $i = 1; @endphp
        @if(isset($order->orderItems))
            <tr>
                <th>{{ __('vendor.serial_number') }}</th>
                <th>{{ __('vendor.item_name') }}</th>
                <th>{{ __('vendor.quantity') }}</th>
                <th>{{ __('vendor.unit_price') }}</th>
            </tr>
            @foreach($order->orderItems as $item)
            <tr>
                <td>{{ $i++ }}.</td>
                <td>
                    {{  ($item->product && $item->product->name_en) ? $item->product->name_en  
                .'('. $item->product->name_br .')' : '' }}
                </td>
                <td>{{ $item->qty }}</td>
                <td>{{ $item->price }}</td>
            </tr>        
            @endforeach
        @endif
            
    </tbody> 
  </table> 