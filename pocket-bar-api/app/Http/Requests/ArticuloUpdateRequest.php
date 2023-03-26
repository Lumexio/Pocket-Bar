<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArticuloUpdateRequest extends FormRequest
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
            'nombre_articulo' => 'string|max:255',
            'descripcion_articulo' => 'string',
            'precio_articulo' => 'numeric',
            'categoria_id' => 'integer',
            'marca_id' => 'integer',
            'foto_articulo' => 'mimes:png,jpg',
            'proveedor_id' => 'integer',
            'tipo_id' => 'integer',
            'status_id' => 'integer',
        ];
    }
}
