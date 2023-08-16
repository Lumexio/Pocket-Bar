<?php

namespace App\Http\Requests;

use App\Enums\Rol;
use Illuminate\Foundation\Http\FormRequest;

class TicketTipUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->rol_id == Rol::Bartender->value || auth()->user()->rol_id == Rol::Mesero->value;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'tip' => 'nullable|numeric|min:0',
            "id" => "required|exists:tickets,id",
            "specifictip" => "nullable|numeric|min:0",
        ];
    }
}
