<?php

namespace App\Http\Requests\Tickets;

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
        return auth()->check() and auth()->user()->rol_id == 3;
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
            "tip" => "required|numeric|min:0",
            "payments.*.payment_type" => "required|in:cash,card,usd",
            "payments.*.amount" => "required|numeric",
            "payments.*.voucher" => "required_if:payments.*.payment_type,card|string|max:255",
        ];
    }
}
