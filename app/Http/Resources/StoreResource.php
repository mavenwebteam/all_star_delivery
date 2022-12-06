<?php
namespace App\Http\Resources;
use App\Constants\Constant;
use App\Helpers\Helper;
use Illuminate\Http\Resources\Json\JsonResource;
use App;
class StoreResource extends JsonResource
{
    // protected $foo;

    // public function foo($value){
    //     $this->foo = $value;
    //     return $this;
    // }
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
            $userId = $request->user_id ?? 0;
            $rating = $this->rating ? (string)$this->rating : "0";
            if (App::isLocale('my')) {
                return [
                    'store_id'   => $this->id ?? 0,
                    'store_name' => $this->name_burmese ?? '',
                    'description'      => $this->description_burmese ?? '',
                    'storevendor_id'  => $this->user_id ?? 0,
                    'business_category_id' => $this->business_category_id ?? 0,
                    'open_at'  => $this->open_at ?? '',
                    'close_at' => $this->close_at ?? '',
                    'address'       => $this->address ?? '',
                    'zipcode' => $this->zipcode ?? '',
                    'lat' => $this->lat ?? '',
                    'lng' => $this->lng ?? '',
                    'rating' => $rating,
                    'is_open' => !empty($this->is_open) ? 1 : 0,
                    'is_favorite' => Helper::isStoreFavourite($userId, $this->id),
                    'closing_day' => $this->closing_day ?? '',
                    'thumb_img' => Helper::getImageUrl($this->image,Constant::STORE_IMAGE_PATH),
                    'image'   => Helper::getImageUrl($this->image, Constant::STORE_THUMB_PATH),
                    'distance'   => number_format((float)$this->distance, 2, '.', '') ?? number_format(0,2)
                ];
            } else {
                return [
                    'store_id'   => $this->id ?? 0,
                    'store_name' => $this->name ?? '',
                    'description'      => $this->description ?? '',
                    'storevendor_id'  => $this->user_id ?? 0,
                    'business_category_id' => $this->business_category_id ?? 0,
                    'open_at'  => $this->open_at ?? '',
                    'close_at' => $this->close_at ?? '',
                    'address'       => $this->address ?? '',
                    'zipcode' => $this->zipcode ?? '',
                    'lat' => $this->lat ?? '',
                    'lng' => $this->lng ?? '',
                    'rating' => $rating,
                    'is_open' => !empty($this->is_open) ? 1 : 0,
                    'is_favorite' => Helper::isStoreFavourite($userId, $this->id),
                    'closing_day' => $this->closing_day ?? '',
                    'thumb_img' => Helper::getImageUrl($this->image,Constant::STORE_IMAGE_PATH),
                    'image'   => Helper::getImageUrl($this->image, Constant::STORE_THUMB_PATH),
                    'distance'   =>  number_format((float)$this->distance, 2, '.', '') ?? number_format(0,2)
                ];
            }
       
    }
}
