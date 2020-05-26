<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RejectOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        //TODO discuss this, currently we have a middleware in place protected the routes and i have a middleware
        //on the route that makes sure the merchant can reject the order as its their own.
        //Should we be using the authorize ehre as well and check that the user is logged in
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
            'merchant_rejection_reason' => ['required', 'max:255', 'profanity']
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
            'merchant_rejection_reason.required' => 'You must enter a reason to reject an order',
        ];
    }
}
