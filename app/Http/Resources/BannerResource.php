<?php
namespace App\Http\Resources;
use App\Constants\Constant;
use App\Helpers\Helper;
use Illuminate\Http\Resources\Json\JsonResource;
use App;

class BannerResource extends JsonResource
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
                'banner_id'              => $this->id ?? 0,
                'business_category_id'   => $this->business_category_id ?? 0,
                'store_id'               => $this->store_id ?? 0,
                'thumb_img'              => Helper::getImageUrl($this->banner,Constant::BANNER_THUMB_PATH),
                'image'                  => Helper::getImageUrl($this->banner, Constant::BANNER_IMAGE_PATH),
            ];
       
    }
}
