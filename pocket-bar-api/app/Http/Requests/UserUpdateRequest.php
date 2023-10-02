<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\Rol;

class UserUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
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
            'name' => 'nullable|regex:/(^[A-Za-z0-9 ]+$)+/',
            'password' => 'nullable',
        ];
    }
}
