<?php

namespace App\Http\Requests;

use App\Enums\Rol;
use Illuminate\Foundation\Http\FormRequest;

class ArticuleValidationRequest extends FormRequest
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
            'nombre_articulo' => 'required|regex:/(^[A-Za-z0-9 ]+$)+/|max:50',
            'cantidad_articulo' => 'required|integer|min:0',
            'descripcion_articulo' => 'nullable|regex:/(^[A-Za-z0-9 ]+$)+/|max:155',
            'foto_articulo' => 'nullable|mimes:png,jpg'
        ];
    }
}
