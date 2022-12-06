<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Constants\Constant;
use App\Helpers\Helper;
use App;
use App\Http\Resources\StoreResource;
use App\Http\Resources\CustomerResource;
use App\Http\Resources\OrderItemsResource;
class DriverOrderDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $paymentStatus = $this->payment_mode == 'COD' ? 'COD' : 'Prepaid';
        $data = array();
        switch ($this->delivery_boy_status) {
            case 'ASSIGNED':
                $data = [
                    'next_status' => 'ARRIVED_AT_STORE',
                    'order_next_status' =>  trans('message.ARRIVED_AT_STORE'),
                    'order_current_msg' =>  trans('message.DRIVER_ASSIGNED')
                ];
                break;
            case 'ARRIVED_AT_STORE':
                $data = [
                    'next_status' => 'PICKEDUP',
                    'order_next_status' =>  trans('message.PICKEDUP'),
                    'order_current_msg' =>  trans('message.DRIVER_ARRIVED_AT_STORE')
                ];
                break;
            case 'PICKEDUP':
                $data = [
                    'next_status' => 'ARRIVED_AT_DELIVERY_LOCATION',
                    'order_next_status' =>  trans('message.ARRIVED_AT_DELIVERY_LOCATION'),
                    'order_current_msg' =>  trans('message.ORDER_PICKEDUP')
                ];
                break;
            case 'ARRIVED_AT_DELIVERY_LOCATION':
                $data = [
                    'next_status' => 'DELIVERED',
                    'order_next_status' =>  trans('message.DELIVERED'),
                    'order_current_msg' =>  trans('message.ORDER_ARRIVED')
                ];
                break;
            case 'DELIVERED':
                $data = [
                    'next_status' => '',
                    'order_next_status' =>  '',
                    'order_current_msg' =>  trans('message.ORDER_DELIVERED')
                ];
                break;
            default:
                $data = [
                    'next_status' => '',
                    'order_next_status' =>  '',
                    'order_current_msg' =>  'Something went wrong!'
                ];
            break;
        };

        $store = object_get($this,'store', []);
        $customer = object_get($this,'user', "");
        $address = object_get($this,'address', '');
        $orderItem = object_get($this,'orderItems', []);
        if(is_object($customer)) $customer->token = '';

        return [
            'id'           => $this->id,
            'order_id'     => $this->order_id ?? '',
            'order_date'   => $this->order_date ?? '',
            'total_amount' => $this->grand_total ?? '',
            'delivery_estimate_time' => $this->delivery_estimate_time ?? '', //in min
            'instructions' => $this->instructions ?? '',
            'status'       => $this->status,
            'order_update' => $data,
            'payment_status' => $paymentStatus,
            'store'        => new StoreResource($store),
            'customer'     => new CustomerResource($customer),
            'order_item'   => OrderItemsResource::collection($orderItem),
            'address'      => new AddressResource($address),
        ];
      
    }
}
