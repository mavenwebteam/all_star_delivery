<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App;
use App\Constants\Constant;
use App\Helpers\Helper;


class CheckoutResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if (App::isLocale('my')) {
            return [
                'product_id' => $this->product->id ?? 0,
                'product_name' => $this->product->name_br ?? '',
                'product_img_thumb' => $this->productImg[0] ? Helper::getImageUrl($this->productImg[0]->image, Constant::PRODUCT_THUMB_PATH) : '',
                'price' => number_format($this->price, 2),
                'qty' =>  $this->qty ?? 0,
                'amount' => number_format(($this->qty * $this->price), 2),
            ];
        }else{
            return [
                'product_id' => $this->product->id ?? 0,
                'product_name' => $this->product->name_en ?? '',
                'product_img_thumb' => $this->productImg[0] ? Helper::getImageUrl($this->productImg[0]->image, Constant::PRODUCT_THUMB_PATH) : '',
                 'price' =>  number_format($this->price, 2),
                'qty' =>  $this->qty ?? 0,
                'amount' => number_format(($this->qty * $this->price), 2),
            ];
        }
    }


}
