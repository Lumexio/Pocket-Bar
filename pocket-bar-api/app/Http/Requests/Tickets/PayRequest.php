<?php

namespace App\Http\Requests\Tickets;

use App\Enums\Rol;
use Illuminate\Foundation\Http\FormRequest;

class PayRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // return auth()->check() and auth()->user()->rol_id == 3;
        return auth()->user()->rol_id == Rol::Cajero->value;
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
            "payments" => "required|array",
            "payments.*.payment_type" => "required|in:cash,card,usd",
            "payments.*.amount" => "required|numeric",
            "payments.*.voucher" => "required_if:payments.*.payment_type,card|string|max:255",
            "payments.*.tip" => "nullable|numeric|min:0",
        ];
    }
}
