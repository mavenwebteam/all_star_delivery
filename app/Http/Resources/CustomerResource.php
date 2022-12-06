<?php
namespace App\Http\Resources;
use App\Constants\Constant;
use App\Helpers\Helper;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
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
            'id' => $this->id ?? 0,
            'first_name' => $this->first_name ?? '',
            'last_name' => $this->last_name ?? '',
            'email' => $this->email ?? '',
            'mobile' => $this->mobile ?? '',
            'token'   => $this->token ?? '',
            'country_code' => $this->country_code ?? 0,
            'latitude' => $this->latitude ?? '',
            'longitude' => $this->longitude ?? '',
            'otp'        => $this->otp ?? '',
            'is_notification' => $this->is_notification ?? 0,
            'notification_count' => 0,
            'profile_thumb' => Helper::getImageUrlUser($this->profile_pic,Constant::USER_IMAGE_THUMB),
            'profile_img'   => Helper::getImageUrlUser($this->profile_pic,Constant::USER_IMAGE),
        ];
    }
}
