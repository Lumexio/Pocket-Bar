<?php

namespace App\Http\Requests\Tickets;

use App\Enums\Rol;
use Illuminate\Foundation\Http\FormRequest;

class CancelTicketRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return in_array(auth()->user()->rol_id, [Rol::Administrativo->value, Rol::Cajero->value, Rol::Gerencia->value]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "id" => "required|exists:tickets,id",
        ];
    }
}
