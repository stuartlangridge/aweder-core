<?php

namespace App\Http\Requests\Registration;

use App\Rules\MaxWordsRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BusinessDetailsRequest extends FormRequest
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
            'logo' => ['image'],
            'name' => ['required'],
            'description' => ['required', new MaxWordsRule(100)],
            'url-slug' => ['required', 'alpha_dash', 'unique:merchants,url_slug'],
            'collection_type' => ['required', Rule::in(['collection', 'both', 'delivery'])],
            'delivery_cost' => [
                Rule::requiredIf(function () {
                    return (request()->get('collection_type') === 'both'
                        || request()->get('collection_type') === 'delivery');
                })
            ],
            'delivery_radius' => [
                Rule::requiredIf(function () {
                    return (request()->get('collection_type') === 'both'
                        || request()->get('collection_type') === 'delivery');
                })
            ],
        ];
    }
}
