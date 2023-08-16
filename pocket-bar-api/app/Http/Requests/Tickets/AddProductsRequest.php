<?php

namespace App\Http\Requests\Tickets;

use App\Enums\Rol;
use Illuminate\Foundation\Http\FormRequest;

class AddProductsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check() and (auth()->user()->rol_id == Rol::Bartender->value or auth()->user()->rol_id == Rol::Mesero->value);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "id" => "required|exists:tickets,id",
            "products" => "required|array|min:1",
            "products.*.id" => "required|integer|exists:products,id",
            "products.*.price" => "required|numeric|min:0",
            "products.*.discount" => "nullable|numeric|min:0",
            "products.*.tax" => "nullable|numeric|min:0",
            "products.*.units" => "required|numeric|min:1",
        ];
    }
}
