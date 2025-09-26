<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class SendOtpRequest extends FormRequest
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
           'email' => ['required','email','min:5','max:55']
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Please provide your email address.',
            'email.email'    => 'Please provide a valid email address.',
            'email.min'      => 'Email must be at least 5 characters.',
            'email.max'      => 'Email may not be longer than 55 characters.',
        ];
    }
}

