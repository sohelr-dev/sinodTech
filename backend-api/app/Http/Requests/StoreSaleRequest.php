<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSaleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_id'          => ['nullable', 'exists:customers,id'],
            'branch_id'            => ['required', 'exists:branches,id'],
            'employee_id'          => ['nullable', 'exists:users,id'],
            'items'                => ['required', 'array', 'min:1'],
            'items.*.product_id'   => ['required', 'exists:products,id'],
            'items.*.quantity'     => ['required', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'items.required'              => 'At least one item is required.',
            'items.*.product_id.required' => 'Each item must have a product.',
            'items.*.quantity.min'        => 'Quantity must be at least 1.',
        ];
    }
}
