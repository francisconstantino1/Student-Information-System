<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'student_id' => [
                'required',
                'string',
                'max:255',
                'unique:users,student_id',
                function ($attribute, $value, $fail) {
                    $studentId = \App\Models\StudentId::where('student_id', $value)->first();

                    if (! $studentId) {
                        $fail('The provided Institutional ID is invalid or not assigned. Please contact the administrator.');

                        return;
                    }

                    if ($studentId->status !== 'available') {
                        $fail('This Institutional ID has already been used. Please contact the administrator for a new Institutional ID.');

                        return;
                    }
                },
            ],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Please enter your full name.',
            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email is already registered.',
            'student_id.required' => 'Institutional ID is required. Please contact the administrator to obtain your assigned Institutional ID.',
            'student_id.exists' => 'The provided Institutional ID is invalid or not assigned. Please contact the administrator.',
            'student_id.unique' => 'This student ID is already registered.',
            'password.required' => 'Please create a password.',
            'password.min' => 'Your password must be at least :min characters.',
            'password.confirmed' => 'The password confirmation does not match.',
        ];
    }
}
