<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
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
            'type' => $this->type ?? '',
            'address' => $this->address ?? '',
            'latitude' => $this->latitude ?? '',
            'longitude' => $this->longitude ?? '',
        ];
    }
}
