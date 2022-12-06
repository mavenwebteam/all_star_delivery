<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Constants\Constant;
use App\Helpers\Helper;

class PromocodeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title ?? '',
            'description' => $this->description ?? '',
            'code' => $this->code ?? '',
            'business_category_id' => $this->business_category_id ?? '',
            'discount_present' => (string) $this->discount_present ?? '',
            'cap_limit' => $this->cap_limit ?? '',
            'thumb_img' => Helper::getImageUrl($this->image,Constant::PROMOCODE_THUMB_PATH),
            'image' => Helper::getImageUrl($this->image, Constant::PROMOCODE_IMAGE_PATH),
        ];
    }
}
