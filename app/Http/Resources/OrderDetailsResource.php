<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Constants\Constant;
use App\Helpers\Helper;
use App;
use App\Http\Resources\StoreResource;
use App\Http\Resources\DriverResource;
use App\Http\Resources\OrderItemsResource;
class OrderDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $status = '';
        $orderStatus = array();
        $orderState = ['ORDERED', 'ACCEPTED', 'READY_TO_SHIP', 'ON_THE_WAY', 'ARRIVED_AT_DELIVERY_LOCATION', 'DELIVERED'];
        
        $orderStateDesc = [
            trans('message.ORDER_DESC_1'),
            trans('message.ORDER_DESC_2'),
            trans('message.ORDER_DESC_3'),
            trans('message.ORDER_DESC_4'),
            trans('message.ORDER_DESC_5'),
            trans('message.ORDER_DESC_6'),
        ];
        $orderStateTitle = [
            trans('message.ORDER_TITLE_1'),
            trans('message.ORDER_TITLE_2'),
            trans('message.ORDER_TITLE_3'),
            trans('message.ORDER_TITLE_4'),
            trans('message.ORDER_TITLE_5'),
            trans('message.ORDER_TITLE_6'),
        ];

        if($this->status == 'CANCELLED'){
            $orderStatus[] = array(
                'status'=> 'CANCELLED',
                'state'=> 1,
                'status_title' => 'Cancelled',
                'desc' => $this->reason_of_cancel,
            );
        } else{
            $arrayKey = array_search($this->status,$orderState);
            foreach($orderState as $key => $status) {
                $orderStatus[] = array(
                    'status' => $status,
                    'state' => ($key <= $arrayKey) ? 1 : 0,
                    'status_title' => $orderStateTitle[$key],
                    'desc' => $orderStateDesc[$key],
                );
            }
        }
        

        switch ($this->status) {
            case 'ORDERED':
                $status = 'Order Placed';
                break;
            case 'ACCEPTED':
                $status = 'Accepted by store';
                break;
            case 'READY_TO_SHIP':
                $status = 'Order in progress';
                break;
            case 'ON_THE_WAY':
                $status = 'On the way';
                break;
            case 'ARRIVED_AT_DELIVERY_LOCATION':
                $status = 'Arrived';
                break;
            case 'DELIVERED':
                $status = 'Delivered';
                break;
            case 'CANCELLED':
                    $status = 'Cancelled';
                    break;
            default:
                $status = 'Order in progress';
                break;
        };

        $store = object_get($this,'store', []);
        $driver = object_get($this,'driver', "");
        $address = object_get($this,'address', '');
        $orderItem = object_get($this,'orderItems', []);
        if(is_object($driver)) $driver->token = '';

            return [
                'id'           => $this->id,
                'order_id'     => $this->order_id ?? '',
                'order_date'   => $this->order_date ?? '',
                'instructions'   => $this->instructions ?? '',
                'order_item_sum'   => $this->order_item_sum ?? '',
                'total_amount' => $this->grand_total ?? '',
                'discounted_amount' => $this->discounted_amount ?? '',
                'tax'          => $this->tax ?? '',
                'delivery_fee' => (string) $this->delivery_fee ?? '',
                'delivery_estimate_time' => $this->delivery_estimate_time ?? '', //in min
                'status'       => $status,
                'order_state'  => $orderStatus,
                'store'        => new StoreResource($store),
                'driver'       => new DriverResource($driver),
                'order_item'   => OrderItemsResource::collection($orderItem),
                'address'      => new AddressResource($address),
            ];
      
    }
}
