<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'exists:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'device_name' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Email field is required',
            'email.email' => 'Enter a valid email address',
            'email.exists' => 'No account found with this email address.',

            'password.required' => 'Password field is required',
            'password.min' => 'The password must be at least 6 characters.',

        ];
    }
}
