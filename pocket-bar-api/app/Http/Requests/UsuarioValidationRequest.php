<?php

namespace App\Http\Requests;

use App\Enums\Rol;
use Illuminate\Foundation\Http\FormRequest;

class UsuarioValidationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->rol_id == Rol::Administrativo->value || auth()->user()->rol_id == Rol::Gerencia->value;;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|regex:/(^[A-Za-z0-9 ]+$)+/',
            'email' => 'nullable|email',
            'password' => 'required',
            'rol_id' => 'required'
        ];
    }
}
