<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Allow all authenticated users to make this request
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'company_id' => 'nullable|exists:companies,id', // Must exist in companies table
            'email' => [
                'nullable',
                'email',
                'max:255',
                // Unique email per employee, ignoring the current employee's ID on update
                Rule::unique('employees')->ignore($this->route('employee')),
            ],
            'phone' => 'nullable|string|max:20',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'first_name.required' => 'The first name is required.',
            'last_name.required' => 'The last name is required.',
            'company_id.exists' => 'The selected company does not exist.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email address is already taken by another employee.',
        ];
    }
}