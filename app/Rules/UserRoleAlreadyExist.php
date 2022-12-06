<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\User;

class UserRoleAlreadyExist implements Rule
{
    /**
    * @param roleId int - user role id
    */
    private $roleId;
    /** 
    * @param email string - user email id
    */
    private $email;
    /**
    * @param mobile string - user mobile id
    */
    private $mobile;
    /**
    * @param testType string - user test with mobile|email
    */
    private $testType; 

    /**
    * @param userId is string - use in case of recorde is update
    */
    private $userId; 

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($role, $email=NULL, $mobile=NULL, $testType, $userId = NULL)
    {
        $this->roleId = $role;
        $this->email = $email;
        $this->mobile = $mobile;
        $this->testType = $testType;
        $this->userId = $userId;
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
        $field = $this->testType == 'email' ? 'email' : 'mobile';
        if($field == 'email'){
            $isExists = User::where($field, $this->email)
            ->where('role_id',$this->roleId);
            if(!empty($this->userId)){
                $isExists = $isExists->where('id', '!=', $this->userId);
            }
            $isExists = $isExists->where('is_deleted','0')->count();
            if($isExists)
            return false;
            else
            return true;
        }else{
            $isExists = User::where($field, $this->mobile)
            ->where('role_id',$this->roleId);
            if(!empty($this->userId)){
                $isExists = $isExists->where('id', '!=', $this->userId);
            }
            $isExists = $isExists->where('is_deleted','0')->count();
            if($isExists)
            return false;
            else
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
        return trans('validation.user_already_exists');
    }
}
