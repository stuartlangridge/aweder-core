<?php

namespace App\Rules;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class OrderIsInFuture implements Rule
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
        if (empty($value['hour']) || empty($value['minute'])) {
            return false;
        }

        if ($this->isTimeAheadOfNow($value)) {
            return true;
        }

        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The time set is in the past.';
    }

    /**
     * method that takes the value array applied and checks that its ahead of now
     * @param array $value
     * @return bool
     */
    protected function isTimeAheadOfNow(array $value = []): bool
    {
        $submittedTime = Carbon::createFromTime($value['hour'], $value['minute']);

        return Carbon::now()->lte($submittedTime);
    }
}
