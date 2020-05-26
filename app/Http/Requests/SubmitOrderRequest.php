<?php

namespace App\Http\Requests;

use App\Rules\DoesMerchantSupportCollectionType;
use Illuminate\Foundation\Http\FormRequest;

class SubmitOrderRequest extends FormRequest
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
            'order_no' => ['required', 'exists:orders,url_slug'],
            'collection_type' => ['required', new DoesMerchantSupportCollectionType(request()->get('order_no'))],
            'order_time' => ['sometimes', 'required'],
            'customer_note' => ['sometimes'],
        ];
    }
}
