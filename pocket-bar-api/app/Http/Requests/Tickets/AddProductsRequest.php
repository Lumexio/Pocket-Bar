<?php

namespace App\Http\Requests\Tickets;

use Illuminate\Foundation\Http\FormRequest;

class AddProductsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check() and (auth()->user()->rol_id == 4 or auth()->user()->rol_id == 5);
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
