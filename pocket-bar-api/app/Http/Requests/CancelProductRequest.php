<?php

namespace App\Http\Requests;

use App\Enums\Rol;
use Illuminate\Foundation\Http\FormRequest;

class CancelProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check() and in_array(auth()->user()->rol_id, [Rol::Administrativo->value, Rol::Cajero->value, Rol::Gerencia->value]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "ticket_id" => "required|exists:tickets,id",
            "products" => "required|array",
            "products.*.id" => "required",
            "products.*.units" => "required|integer|min:1",
        ];
    }
}
