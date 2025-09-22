<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'username'     => ['required', 'string', 'max:100'],
            'email'    => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:6'],
            'confirm_password' => ['required', 'same:password'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'username.required'     => 'Your username is required.',
            'username.string'       => 'Your username must be a valid string.',
            'username.max'          => 'Your username may not be longer than 100 characters.',

            'email.required'    => 'We need your email address.',
            'email.email'       => 'Please provide a valid email address.',
            'email.max'         => 'Email may not be longer than 255 characters.',
            'email.unique'      => 'This email is already taken.',

            'password.required' => 'A password is required.',
            'password.min'      => 'Password must be at least 6 characters.',
            'password.confirmed' => 'Passwords do not match. Please confirm correctly.',
        ];
    }
}
