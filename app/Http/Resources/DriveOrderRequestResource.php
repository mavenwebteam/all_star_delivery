<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Constants\Constant;
use App\Helpers\Helper;
use App;
use App\Http\Resources\StoreResource;


class DriveOrderRequestResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
      
        

        $storeImg = object_get($this,'order.store.image', '');
        $orderItem = Helper::getOrderItem($this->order->id);
        $paymentMode = object_get($this,'order.payment_mode', 'COD');
        $paymentMode = $paymentMode == 'COD' ? 'COD' : 'Prepaid';

        if (App::isLocale('my')) {
        return [
                'id'           => $this->order->id,
                'order_id'     => $this->order->order_id ?? '',
                'order_date'   => $this->order->order_date ?? '',
                'total_amount' => $this->order->grand_total ?? '',
                'payment_mode' => $paymentMode ?? '',
                'store_name'   => object_get($this,'order.store.name_burmese', ''),
                'thumb_img'    => Helper::getImageUrl($storeImg,Constant::STORE_IMAGE_PATH),
                'image'        => Helper::getImageUrl($storeImg, Constant::STORE_THUMB_PATH),
                'store_address'=> object_get($this,'order.store.address', ''),
                'order_item'   => $orderItem,
            ];
        }
        else{
            return [
                'id'           => $this->order->id,
                'order_id'     => $this->order->order_id ?? '',
                'order_date'   => $this->order->order_date ?? '',
                'total_amount' => $this->order->grand_total ?? '',
                'payment_mode' => $paymentMode ?? '',
                'store_name'   => object_get($this,'order.store.name', ''),
                'thumb_img'    => Helper::getImageUrl($storeImg,Constant::STORE_IMAGE_PATH),
                'image'        => Helper::getImageUrl($storeImg, Constant::STORE_THUMB_PATH),
                'store_address'=> object_get($this,'order.store.address', ''),
                'order_item'    => $orderItem
            ];
        }
    }
}
