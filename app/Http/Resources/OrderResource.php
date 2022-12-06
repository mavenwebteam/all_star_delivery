<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Constants\Constant;
use App\Helpers\Helper;
use App;
class OrderResource extends JsonResource
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
        switch ($this->status) {
            case 'ORDERED':
                $status = 'Order Placed';
                break;
            case 'ACCEPTED':
                $status = 'You Order has been accepted by store';
                break;
            case 'READY_TO_SHIP':
                $status = 'Order in progress';
                break;
            case 'ON_THE_WAY':
                $status = 'Your order is on the way';
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

        $storeImg = object_get($this,'store.image', '');
        $driverProfile = object_get($this,'driver.profile', '');
        $orderItem = Helper::getOrderItem($this->id);
        $paymentMode = $this->payment_mode == 'COD' ? 'COD' : 'Prepaid';
        

        if (App::isLocale('my')) {
        return [
                'id'           => $this->id,
                'order_id'     => $this->order_id ?? '',
                'order_date'   => $this->order_date ?? '',
                'total_amount' => $this->grand_total ?? '',
                'status'       => $status,
                'is_rated'     => $this->store_rating ? 1 : 0,
                'payment_mode' => $paymentMode ?? '',
                'store_id'     => object_get($this,'store.id', 0),
                'store_name'   => object_get($this,'store.name_burmese', ''),
                'driver_id'    => object_get($this,'driver.id', 0),
                'driver_name'  => object_get($this,'driver.full_name', ''),
                'driver_profile'=> Helper::getImageUrl($driverProfile,Constant::USER_IMAGE_THUMB),
                'thumb_img'    => Helper::getImageUrl($storeImg,Constant::STORE_IMAGE_PATH),
                'image'        => Helper::getImageUrl($storeImg, Constant::STORE_THUMB_PATH),
                'store_address'=> object_get($this,'store.address', ''),
                'is_order_cancellable'=> ($this->status == 'ORDERED') ? true : false,
                'order_item'       => $orderItem
               
            ];
        }
        else{
            return [
                'id'           => $this->id,
                'order_id'     => $this->order_id ?? '',
                'order_date'   => $this->order_date ?? '',
                'total_amount' => $this->grand_total ?? '',
                'status'       => $status,
                'is_rated'     => $this->store_rating ? 1 : 0,
                'payment_mode' => $paymentMode ?? '',
                'store_id'     => object_get($this,'store.id', 0),
                'store_name'   => object_get($this,'store.name', ''),
                'driver_id'    => object_get($this,'driver.id', 0),
                'driver_name'  => object_get($this,'driver.full_name', ''),
                'driver_profile'=> Helper::getImageUrl($driverProfile,Constant::USER_IMAGE_THUMB),
                'thumb_img'    => Helper::getImageUrl($storeImg,Constant::STORE_IMAGE_PATH),
                'image'        => Helper::getImageUrl($storeImg, Constant::STORE_THUMB_PATH),
                'store_address'=> object_get($this,'store.address', ''),
                'is_order_cancellable'=> ($this->status == 'ORDERED') ? true : false,
                'order_item'   => $orderItem

            ];
        }
    }
}
