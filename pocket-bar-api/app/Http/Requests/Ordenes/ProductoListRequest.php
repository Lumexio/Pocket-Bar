<?php

namespace App\Http\Requests\Ordenes;

use Illuminate\Foundation\Http\FormRequest;

class ProductoListRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->authorize();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "status" => "nullable|string|in:En espera,En preparacion,Preparado,Recibido",
        ];
    }
}
