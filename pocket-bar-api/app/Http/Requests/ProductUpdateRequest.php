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
            'price' => 'numeric',
            'cetegory_id' => 'integer|exists:categories,id',
            'brand_id' => 'integer|exists:brands,id',
            'image' => 'nullable|mimes:png,jpg',
            'provider_id' => 'integer|exists:providers,id',
            'type_id' => 'integer|exists:types,id',
        ];
    }
}
