<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => 'required|integer|exists:users,id',
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($this->id),
            ],
            'gender' => [
                'required',
                // Rule::in(array_keys(config('constant.gender'))), // integer keys from config
            ],
            'goal' => [
                'required',
                // Rule::in(array_keys(config('constant.goal'))), // integer keys from config
            ],
            'height' => 'required|numeric|min:50|max:300',
            'weight' => 'required|numeric|min:20|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'id.required' => 'User ID is missing.',
            'id.integer' => 'Invalid user ID.',
            'id.exists' => 'User does not exist.',
            'name.required' => 'Please enter your name.',
            'email.required' => 'Please enter your email.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email is already taken.',
            'gender.required' => 'Please select your gender.',
            'gender.in' => 'Invalid gender selected.',
            'goal.required' => 'Please select your goal.',
            'goal.in' => 'Invalid goal selected.',
            'height.required' => 'Please enter your height in cm.',
            'height.numeric' => 'Height must be a number.',
            'weight.required' => 'Please enter your weight in kg.',
            'weight.numeric' => 'Weight must be a number.',
        ];
    }
}
