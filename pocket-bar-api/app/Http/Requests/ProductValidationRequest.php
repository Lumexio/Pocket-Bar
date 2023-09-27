<?php

namespace App\Http\Requests;

use App\Enums\Rol;
use Illuminate\Foundation\Http\FormRequest;

class ProductValidationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return auth()->user()->rol_id == Rol::Administrativo->value || auth()->user()->rol_id == Rol::Gerencia->value;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|regex:/(^[A-Za-z0-9 ]+$)+/|max:50',
            'units' => 'required|integer|min:0',
            'description' => 'nullable|regex:/(^[A-Za-z0-9 ]+$)+/|max:155',
            'image' => 'nullable|mimes:png,jpg',
            'price' => 'nullable|numeric|min:0',
            'category_id' => 'nullable|integer|exists:categories,id',
            'brand_id' => 'nullable|integer|exists:brands,id',
            'provider_id' => 'nullable|integer|exists:providers,id',
            'type_id' => 'nullable|integer|exists:types,id',
            'status_id' => 'nullable|integer|exists:types,id',
            "branch_id" => "nullable|integer|exists:branches,id",
        ];
    }
}
