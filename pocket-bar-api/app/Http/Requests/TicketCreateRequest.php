<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TicketCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        return [
            "mesa" => "required|exists:tables,id",
            "titular" => "required|string|max:255",
            "productos" => "required|array|min:1",
            "productos.*.id" => "required|integer|exists:articulos_tbl,id",
            "productos.*.nombre_articulo" => "required|string",
            "productos.*.precio_articulo" => "required|numeric|min:0",
            "productos.*.foto_articulo" => "nullable|string",
            "productos.*.descuento" => "nullable|numeric|min:0",
            "productos.*.tax" => "nullable|numeric|min:0",
            "productos.*.piezas" => "required|numeric|min:1",
        ];
    }
}
