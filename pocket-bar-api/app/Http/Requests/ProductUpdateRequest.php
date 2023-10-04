<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductUpdateRequest extends FormRequest
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
            'name' => 'string|max:255',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric',
            'cetegory_id' => 'nullable|integer|exists:categories,id',
            'brand_id' => 'nullable|integer|exists:brands,id',
            'image' => 'nullable|mimes:png,jpg',
            'provider_id' => 'nullable|integer|exists:providers,id',
            'type_id' => 'nullable|integer|exists:types,id',
        ];
    }
}
