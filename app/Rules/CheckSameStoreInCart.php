<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Cart;
use Auth;
class CheckSameStoreInCart implements Rule
{
    /**
     * Create a new rule instance.
    * @param userId int 
    * @param storeId int product qty
    */
    private $userId;
    private $storeId;

    public function __construct($storeId)
    {
        $this->userId = Auth::id();    
        $this->storeId = $storeId;    
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
        // for now return true
        if(empty($this->userId) || empty($this->storeId)) return true;
        
        $isDiffrentStore = Cart::where('user_id', $this->userId)
        ->where('store_id','!=', $this->storeId)
        ->count();

        if($isDiffrentStore > 0){
            return false;
        }else{
            return true;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('validation.cart_another_store');
    }
}
