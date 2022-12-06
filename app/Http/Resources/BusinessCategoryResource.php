<?php
namespace App\Http\Resources;
use App\Constants\Constant;
use App\Helpers\Helper;
use Illuminate\Http\Resources\Json\JsonResource;
use App;

class BusinessCategoryResource extends JsonResource
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
                'business_category_id'   => $this->id ?? 0,
                'business_category_name' => $this->name_burmese ?? '',
                'thumb_img'              => Helper::getImageUrl($this->image,Constant::BUSINESS_CAT_THUMB_PATH),
                'image'                  => Helper::getImageUrl($this->image, Constant::BUSINESS_CAT_IMAGE_PATH),
            ];
        }else{
            return [
                'business_category_id'   => $this->id ?? 0,
                'business_category_name' => $this->name_en ?? '',
                'thumb_img'              => Helper::getImageUrl($this->image,Constant::BUSINESS_CAT_THUMB_PATH),
                'image'                  => Helper::getImageUrl($this->image, Constant::BUSINESS_CAT_IMAGE_PATH),
            ];
        }
    }
}
