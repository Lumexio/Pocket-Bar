<?php

namespace App\Http\Requests\Caja;

use App\Enums\Rol;
use Illuminate\Foundation\Http\FormRequest;

class MovementRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check() and (auth()->user()->rol_id == Rol::Cajero->value or auth()->user()->rol_id == Rol::Guardia->value);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "amount" => "required|numeric|min:1",
            "description" => "nullable|string|max:255",
        ];
    }
}
