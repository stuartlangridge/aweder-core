<?php

namespace App\Rules;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class AfterCurrentTime implements Rule
{
    /**
     * @var Carbon $time
     */
    protected $time;

    /**
     * Create a new rule instance.
     *
     * @param Carbon $time
     */
    public function __construct($time)
    {
        $this->time = $time;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        return Carbon::now()->lessThan($this->time);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'The available time cannot be before the current time';
    }
}
