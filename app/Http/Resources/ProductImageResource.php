<?php
namespace App\Http\Resources;
use App\Constants\Constant;
use App\Helpers\Helper;
use Illuminate\Http\Resources\Json\JsonResource;
use App;

class ProductImageResource extends JsonResource
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
            'thumb_image'  => Helper::getImageUrl($this->image, Constant::PRODUCT_THUMB_PATH),
            'image'        => Helper::getImageUrl($this->image, Constant::PRODUCT_IMAGE_PATH),   
        ];
    }
      
}
