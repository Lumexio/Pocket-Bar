<?php

namespace App\Http\Requests\Tickets;

use App\Enums\Rol;
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
        return auth()->check() and (auth()->user()->rol_id == Rol::Bartender->value or auth()->user()->rol_id == Rol::Mesero->value);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "ticket_id" => "required|exists:tickets_tbl,id",
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
