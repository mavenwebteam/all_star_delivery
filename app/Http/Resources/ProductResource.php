<?php
namespace App\Http\Resources;
use App\Constants\Constant;
use App\Helpers\Helper;
use Illuminate\Http\Resources\Json\JsonResource;
use App;

class ProductResource extends JsonResource
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
                'product_id'  => $this->id ?? 0,
                'store_id '   => $this->store_id ?? 0,
                'product_name'=> $this->name_br ?? '',
                'description' => $this->description_br ?? '',
                'price'       => $this->price ? (string)$this->price : number_format(0,2),
                'discount_present' => $this->discount_present ?? "",
                'discount_price' => $this->discounted_price ?? number_format(0,2),
                'total_qty'   => $this->total_qty ?? 0,
                'available_qty' => $this->available_qty ?? 0,
                'cart_qty'      => (string) Helper::cartItemForUser($userId, $this->id),
                'size'        => (string) $this->size ?? (string) 0,
                'unit_code'   => $this->unit->code ?? '',
                'unit_name'   => $this->unit->name ?? '',
                'in_stock'    => $this->in_stock ?? 0,
                'thumb_image' => Helper::getImageUrl($this->images[0]->image, Constant::PRODUCT_THUMB_PATH),
                'image'       => Helper::getImageUrl($this->images[0]->image, Constant::PRODUCT_IMAGE_PATH),
            ];
        }else{
            return [
                'product_id'  => $this->id ?? 0,
                'store_id '   => $this->store_id ?? 0,
                'product_name'=> $this->name_en ?? '',
                'description' => $this->description_en ?? '',
                'price'       =>  $this->price ? (string)$this->price : number_format(0,2),
                'discount_present' => $this->discount_present ?? "",
                'discount_price' => $this->discounted_price ?? number_format(0,2),
                'total_qty'   => $this->total_qty ?? 0,
                'available_qty' => $this->available_qty ?? 0,
                'cart_qty'      => (string) Helper::cartItemForUser($userId,$this->id),
                'size'        => (string) $this->size ?? (string) 0,
                'unit_code'   => $this->unit->code ?? '',
                'unit_name'   => $this->unit->name ?? '',
                'in_stock'    => $this->in_stock ?? 0,
                'thumb_image' => Helper::getImageUrl($this->images[0]->image, Constant::PRODUCT_THUMB_PATH),
                'image'       => Helper::getImageUrl($this->images[0]->image, Constant::PRODUCT_IMAGE_PATH),
            ];
        }
    }
}
