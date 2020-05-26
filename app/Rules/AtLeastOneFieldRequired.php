<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class AtLeastOneFieldRequired implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        $notEmpty = false;

        foreach ($value as $key => $title) {
            if (!empty($title)) {
                $notEmpty = true;
            }
        }
        return $notEmpty;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'At least one field must be entered';
    }
}
