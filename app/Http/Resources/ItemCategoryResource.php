<?php
namespace App\Http\Resources;
use App\Constants\Constant;
use App\Helpers\Helper;
use Illuminate\Http\Resources\Json\JsonResource;
use App;

class ItemCategoryResource extends JsonResource
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
                'item_category_id'   => $this->id ?? 0,
                'item_category_name' => $this->name_burmese ?? '',
                'image'                  => Helper::getImageUrl($this->image, Constant::ITEM_CAT_IMAGE_PATH),
            ];
        }else{
            return [
                'item_category_id'    => $this->id ?? 0,
                'item_category_name'  => $this->name_en ?? '',
                'image'               => Helper::getImageUrl($this->image, Constant::ITEM_CAT_IMAGE_PATH),
            ];
        }
    }
}
