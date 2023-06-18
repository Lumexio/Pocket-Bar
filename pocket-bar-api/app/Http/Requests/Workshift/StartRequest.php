<?php

namespace App\Http\Requests\Workshift;

use App\Enums\Rol;
use Illuminate\Foundation\Http\FormRequest;

class StartRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {

        return auth()->user()->rol_id===Rol::Cajero->value;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "start_money" => "required|numeric|min:0",
        ];
    }
}
