<?php
namespace App\Http\Resources;
use App\Constants\Constant;
use App\Helpers\Helper;
use Illuminate\Http\Resources\Json\JsonResource;

class DriverResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
    */
    public function toArray($request)
    {
        $profile_pic = object_get($this,'profile_pic', '');
        $vehicle = object_get($this,'vehicle', []);
        
        return [
            'id' => $this->id ?? 0,
            'first_name' => $this->first_name ?? '',
            'last_name' => $this->last_name ?? '',
            'email'  => $this->email ?? '',
            'mobile' => $this->mobile ?? '',
            'is_online' => $this->is_online ?? '',
            'otp' => $this->otp ?? '',
            'token'   => object_get($this,'token','' ),
            'country_code' => $this->country_code ?? 0,
            'latitude' => $this->latitude ?? '',
            'longitude' => $this->longitude ?? '',
            'profile_thumb' => Helper::getImageUrlUser($profile_pic, Constant::USER_IMAGE_THUMB),
            'profile_img'   => Helper::getImageUrlUser($profile_pic, Constant::USER_IMAGE),
            'vehicle'    =>  new VehicleResource($vehicle),
        ];
    }
}
