<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EnrollmentRequest extends FormRequest
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
            'full_name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'birthday' => ['required', 'date', 'before:today'],
            'gender' => ['required', 'string', 'in:Male,Female,Other'],
            'previous_school' => ['nullable', 'string', 'max:255'],
            'course_selected' => ['required', 'string', 'max:255'],
            'year_level' => ['required', 'string', 'in:1st Year,2nd Year,3rd Year,4th Year'],
            'guardian_name' => ['required', 'string', 'max:255'],
            'guardian_contact' => ['required', 'string', 'max:255'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'full_name.required' => 'Please enter your full name.',
            'address.required' => 'Please enter your address.',
            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please enter a valid email address.',
            'birthday.required' => 'Please enter your birthday.',
            'birthday.before' => 'Birthday must be in the past.',
            'gender.required' => 'Please select your gender.',
            'course_selected.required' => 'Please select a course.',
            'year_level.required' => 'Please select your year level.',
            'guardian_name.required' => 'Please enter guardian name.',
            'guardian_contact.required' => 'Please enter guardian contact.',
        ];
    }
}

