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
            "tableId" => "required|exists:tables,id",
            "items" => "required|array|min:1",
            "items.*.id" => "required|integer|exists:articulos_tbl,id",
            "items.*.quantity" => "required|integer|min:1",
            "items.*.price" => "required|numeric|min:0",
            "items.*.discount" => "required|numeric|min:0",
            "items.*.tax" => "required|numeric|min:0",
            "items.*.image" => "required|string|max:300",
        ];
    }
}
