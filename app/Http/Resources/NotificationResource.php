<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class NotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $date = Carbon::parse($this->created_at);
        return [
            'id' => $this->id,
            'type' => $this->type ?? '',
            'target_id' => $this->target_id ?? '',
            'title' => $this->title ?? '',
            'description' => $this->description ?? '',
            'is_read' => $this->is_read ?? '',
            'created_at' => $date->format('d-m-Y H:i:s')
        ];
    }
}
