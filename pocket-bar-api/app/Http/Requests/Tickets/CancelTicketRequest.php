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
        return in_array(auth()->user()->rol_id, [Rol::Administrativo, Rol::Cajero, Rol::Gerencia]);
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
            "confirm_ticket" => "nullable|boolean"
        ];
    }
}
