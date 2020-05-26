<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class AcceptOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $this->merge([
            'available_time' => Carbon::parse($this->input('time_hours') . ':' . $this->input('time_minutes'))
        ]);
        return [
            'time_hours' => ['required'],
            'time_minutes' => ['required'],
        ];
    }
}
