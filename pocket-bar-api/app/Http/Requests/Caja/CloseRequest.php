<?php

namespace App\Http\Requests\Caja;

use App\Enums\Rol;
use Illuminate\Foundation\Http\FormRequest;

class CloseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check() and auth()->user()->rol_id == Rol::Cajero->value;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "data" => "required|array",
            "data.*.type" => "required|in:cash,card,usd",
            "data.*.amount" => "required|numeric",
            "data.*.tip" => "nullable|numeric",
            "data.*.vouchers" => "required_if:data.*.type,card|array",
            "data.*.vouchers.*" => "required|string|max:36",
        ];
    }
}
