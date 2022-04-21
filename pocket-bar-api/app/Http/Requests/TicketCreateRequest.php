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
            "items.*.id" => "required|integer|exists:articulos_tbl,id",
            "items.*.nombre_articulo" => "required|string",
            "items.*.precio_articulo" => "required|numeric|min:0",
            "items.*.foto_articulo" => "nullable|string",
            "items.*.descuento" => "nullable|numeric|min:0",
            "items.*.tax" => "nullable|numeric|min:0",
            "items.*.piezas" => "required|numeric|min:1",
        ];
    }
}
