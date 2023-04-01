<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Slug implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($this->hasUnderscore($value))
        {
            $fail(trans('validation.no_underscore'));
        }
        if ($this->startsWithDashes($value))
        {
            $fail(trans('validation.no_starting_dashes'));
        }
        if ($this->endsWithDashes($value))
        {
            $fail(trans('validation.no_ending_dashes'));
        }
    }
    /**
     * @param [type] $value
     * @return boolean
     */
    public function hasUnderscore($value) : bool
    {
        return preg_match('/_/', $value);
    }
    /**
     * @param [type] $value
     * @return boolean
     */
    public function startsWithDashes($value) : bool
    {
        return preg_match('/^-/', $value);
    }
    /**
     * @param [type] $value
     * @return boolean
     */
    public function endsWithDashes($value) : bool
    {
        return preg_match('/-$/', $value);
    }
}
