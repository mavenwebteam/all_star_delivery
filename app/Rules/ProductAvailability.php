<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Product;

class ProductAvailability implements Rule
{

    /**
    * @param productId int 
    * @param qty int product qty
    */
    private $productId;
    private $qty;



    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($productId, $qty)
    {
        $this->productId = $productId;
        $this->qty = $qty;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if(empty($this->productId) || $this->qty == '') return false;
        $product = Product::where('id',$this->productId)
        ->where('available_qty','>=', $this->qty)
        ->where('in_stock',1)
        ->where('status',1)
        ->count();

        if($product > 0){
            return true;
        }else{
            return false;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('validation.product_qty_exceed');
    }
}
