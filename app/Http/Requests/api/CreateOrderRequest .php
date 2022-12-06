<?php

namespace App\Http\Requests\api;

use Illuminate\Foundation\Http\FormRequest;

class AddAddressRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user_id'      => 'required|exists:users,id',
            'store_id'     => 'required|exists:stores,id',
            'address'      => 'required|exists:address,id',
            'payment_mode' => 'required|in:COD,CARD,UPI,NET_BANKING,WALLET',
            'instructions' => 'nullable|string|max:500',
            'delivery_fee' => 'required|numeric',
            'amount'       => 'required|numeric',
            'discounted_amount' => 'nullable|numeric'
        ];
    }
}
