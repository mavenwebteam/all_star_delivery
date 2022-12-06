<?php
namespace App\Http\Resources;
use App\Constants\Constant;
use App\Helpers\Helper;
use Illuminate\Http\Resources\Json\JsonResource;
use App;

class OrderItemsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $userId = object_get($request, 'user_id', 0);
        if (App::isLocale('my')) {
            return [
                'product_id'  => $this->product->id ?? 0,
                'product_name'=> $this->product->name_br ?? '',
                'description' => $this->product->description_br ?? '',
                'price'       => $this->price ? (string)$this->price : number_format(0,2),
                'item_qty'    => (string) $this->qty ?? (string) 0,
                'item_size'   => (string) $this->product->size ?? (string) 0,
                'unit_code'   => $this->product->unit->code ?? '',
                'unit_name'   => $this->product->unit->name ?? '',
                'thumb_image' => Helper::getImageUrl($this->product->images[0]->image, Constant::PRODUCT_THUMB_PATH),
                'image'       => Helper::getImageUrl($this->product->images[0]->image, Constant::PRODUCT_IMAGE_PATH),
            ];
        }else{
            return [
                'product_id'  => $this->product->id ?? 0,
                'product_name'=> $this->product->name_en ?? '',
                'description' => $this->product->description_en ?? '',
                'price'       => $this->price ? (string)$this->price : number_format(0,2),
                'item_qty'    => (string) $this->qty ?? (string) 0,
                'item_size'   => (string) $this->product->size ?? (string) 0,
                'unit_code'   => $this->product->unit->code ?? '',
                'unit_name'   => $this->product->unit->name ?? '',
                'thumb_image' => Helper::getImageUrl($this->product->images[0]->image, Constant::PRODUCT_THUMB_PATH),
                'image'       => Helper::getImageUrl($this->product->images[0]->image, Constant::PRODUCT_IMAGE_PATH),
            ];
        }
    }
}
