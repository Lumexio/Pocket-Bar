<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TableValidationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'nombre_mesa' => 'required',
            'descripcion_mesa' => 'nullable|regex:/(^[A-Za-z0-9 ]+$)+/',
            'branch_id' => 'nullable|exists:branches,id'
        ];
    }
}
