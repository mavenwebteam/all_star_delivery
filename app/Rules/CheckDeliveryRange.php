<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Settings;

class CheckDeliveryRange implements Rule
{
    /**
     * Create a new rule instance.
    * @param distance int
    */
    private $distance;

    public function __construct($distance)
    {
        $this->distance = $distance;
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
        /**
         * Return false delivery radius is greater than delivery_max_radius
        */
        $adminSetting = Settings::first();
        if($this->distance > $adminSetting->delivery_max_radius){
            return false;
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('message.DELIVERY_RADIUS_EXCEED');
    }
}
