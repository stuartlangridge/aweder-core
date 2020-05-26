<?php

namespace App\Http\Requests\Registration;

use Illuminate\Foundation\Http\FormRequest;

class BusinessAddressRequest extends FormRequest
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
            'address-name-number' => ['required'],
            'address-street' => ['required'],
            'address-city' => ['required'],
            'address-county' => ['required'],
            'address-postcode' => ['required'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'address-name-number.required' => 'A number or name is required',
            'address-street.required' => 'A street is required',
            'address-city.required' => 'A city is required',
            'address-postcode.required' => 'A postcode is required',
        ];
    }
}
