<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class PhoneValidator implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
        return preg_match('/^([6]([7]))([0-9]{7})|^([6]([8]))([0-9]{7})|^([6]([5]))([0-4]{1})([0-9]{6})|^([6]([9]))([0-9]{7})|^([6]([5]))([5-8]{1})([0-9]{6})|^([6]([5])([9])([0-4]{1})([0-9]{5}))/', $value) && strlen($value) == 9;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'the number you telephone number you entered is not correct.';
    }
}
