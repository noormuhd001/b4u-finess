<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProgressStoreRequest extends FormRequest
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
            'workouts_completed'   => 'required|array|min:1',
            'workouts_completed.*' => 'integer|exists:workouts,id',
            'weight'               => 'required|numeric|min:1|max:500',
            'workout_on'           => 'required|date',
            
        ];
    }

    /**
     * Custom messages for validation errors.
     */
    public function messages(): array
    {
        return [
            'workouts_completed.required'   => 'Please select at least one workout.',
            'workouts_completed.array'      => 'Invalid format for workouts completed.',
            'workouts_completed.*.integer'  => 'Each workout ID must be a valid number.',
            'workouts_completed.*.exists'   => 'One or more selected workouts are invalid.',
            'weight.required'               => 'Please enter your current weight.',
            'weight.numeric'                => 'Weight must be a number.',
            'weight.min'                    => 'Weight must be at least 1 kg.',
            'weight.max'                    => 'Weight cannot exceed 500 kg.',
            'workout_on.required'           => 'Please select the workout date.',
            'workout_on.date'               => 'The workout date must be a valid date.',
        ];
    }
}
