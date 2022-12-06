<?php

namespace App\Http\Requests\api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request;
use App\Helpers\Helper;
use App\Rules\CheckDeliveryRange;


class PlaceOrderRequest extends FormRequest
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
            'user_id'    => 'required|exists:users,id',
            'address_id' => 'required|exists:addresses,id',
            'store_id'   => 'required|exists:stores,id',
            'distance'   => ['required', 'numeric', new CheckDeliveryRange($this->distance)],
            'promocode_id' => 'nullable|exists:promocodes,id',
            'payment_mode' => 'required|in:COD,CARD,UPI,NET_BANKING',
            'instructions' => 'nullable|string|max:500',
            'delivery_fee' => 'required|between:0,9999',
            'tax' => 'required|between:0,100',
            'cart_amount' => 'required',
            'discounted_amount' => 'nullable',
            'customer_payable_amount' => 'required'
        ];
    }

     /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator){
        Helper::__failedValidation($validator->errors()->first());
    }
}
