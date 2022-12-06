<?php
namespace App\Http\Resources;
use App\Constants\Constant;
use App\Helpers\Helper;
use Illuminate\Http\Resources\Json\JsonResource;

class VehicleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
    */
    public function toArray($request)
    {
        $vehicle_num_img = object_get($this,'vehicle_num_img', '');
        $licence_img = object_get($this,'licence_img', '');
        return [
            'brand_name'      => $this->brand_name ?? '',
            'year'            => $this->year ?? '',
            'vehicle_num'     => $this->vehicle_num ?? '',
            'licence_num'     => $this->licence_num ?? '',
            'vehicle_num_img' => Helper::getImageUrl($vehicle_num_img, 'media/vehicle/'),
            'licence_img'     => Helper::getImageUrl($licence_img, 'media/vehicle/'),
            'vehicle_type'    => $this->vehicle_type ?? '',
            'model'           => $this->model ?? '',
        ];
    }
}
