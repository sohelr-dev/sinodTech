<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInventoryRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'adjustment' => ['required', 'integer', 'not_in:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'adjustment.not_in' => 'Adjustment cannot be zero.',
        ];
    }
}
