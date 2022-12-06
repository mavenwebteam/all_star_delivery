<?php
namespace App\Http\Resources;
use App\Constants\Constant;
use App\Helpers\Helper;
use Illuminate\Http\Resources\Json\JsonResource;
use App;

class ChatResource extends JsonResource
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
                'id' => (int) $this->id ?? 0,
                'order_id' => (int) $this->order_id ?? 0,
                'sender_id' => (int) $this->sender_id ?? 0,
                'receiver_id' => (int) $this->receiver_id ?? 0,
                'message' => (string) base64_decode($this->message) ?? '',
            ];
    }
}
