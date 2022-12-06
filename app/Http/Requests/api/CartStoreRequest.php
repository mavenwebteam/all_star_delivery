<?php

namespace App\Http\Requests\api;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ProductAvailability;
use App\Rules\CheckSameStoreInCart;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request;
use App\Helpers\Helper;
use Auth;

class CartStoreRequest extends FormRequest
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
            'qty'        => 'required',
            'price'      => 'required',
            'product_id' =>  ['required', 'exists:products,id', new ProductAvailability($this->product_id, $this->qty)],
            'store_id'   => ['nullable','exists:stores,id', new CheckSameStoreInCart($this->store_id) ]
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
