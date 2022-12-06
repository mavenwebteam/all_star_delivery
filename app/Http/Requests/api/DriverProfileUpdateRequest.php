<?php

namespace App\Http\Requests\api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request;
use App\Helpers\Helper;
use App\Rules\UserRoleAlreadyExist;
use Auth;

class DriverProfileUpdateRequest extends FormRequest
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
     * * @param int role_id = 2 for driver
     * * @function custome rule UserRoleAlreadyExist(roleId, email, mobile,'mobile|email')
     * @return array
     */
    public function rules()
    {
        return [
            'first_name'      => 'string|required|max:50',
            'last_name'       => 'string|required|max:50',
            'email'           => ['nullable','email', new UserRoleAlreadyExist(2, $this->email, NULL, 'email', Auth::id())],
            'mobile'          => ['nullable','max:14', new UserRoleAlreadyExist(2, NULL, $this->mobile, 'mobile', Auth::id())],
            'profile_pic'     => 'nullable|mimes:jpg,png,jpeg|max:1024',
            'brand_name'      => 'nullable|string|max:50',
            'year'            => 'nullable|digits:4|integer|min:1900|max:'.(date('Y')),
            'vehicle_num'     => 'nullable|string|max:20',
            'vehicle_num_img' => 'nullable|mimes:jpg,png,jpeg|max:1024',
            'licence_num'     => 'nullable|string|max:20',
            'licence_img'     => 'nullable|mimes:jpg,png,jpeg|max:1024',
            'vehicle_type'    => 'nullable|in:Motorbike,Bicycle',
            'model'           => 'nullable|string:100',
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
