<?php

namespace App\Http\Requests\api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request;
use App\Helpers\Helper;
use Auth;
use App\Rules\UserRoleAlreadyExist;

class UpdateProfileRequest extends FormRequest
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
            'email'      => ['required','email','max:30', new UserRoleAlreadyExist(1, $this->email, NULL, 'email', Auth::id())],
            'first_name' => 'required|max:15|min:2',
            'last_name'  => 'required|max:15|min:2',
            'profile'    => 'nullable|max:2048|mimes:jpg,jpeg,gif,png',
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
