<?php

namespace App\Http\Requests\Nominas;

use App\Enums\Rol;
use Illuminate\Foundation\Http\FormRequest;

class ToPay extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->rol_id == Rol::Administrativo->value || auth()->user()->rol_id == Rol::Gerencia->value || auth()->user()->rol_id == Rol::Cajero->value;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "payroll" => "required|array|min:1",
            "total_nominas" => "required|numeric",
            "payroll.*.id" => "required|integer|exists:users,id",
            "payroll.*.nominas" => "required|numeric",
            "payroll.*.name" => "required|string|max:255",
        ];
    }
}
