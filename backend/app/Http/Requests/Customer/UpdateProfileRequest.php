<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
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
        $userId = $this->user()->id ?? null;

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255', 'unique:users,email,' . $userId],
            'full_name' => ['nullable', 'string', 'max:255'],
            'date_of_birth' => ['nullable', 'date', 'before:today'],
            'gender' => ['nullable', 'in:male,female,other'],
            'address' => ['nullable', 'string', 'max:500'],
            'city' => ['nullable', 'string', 'max:100'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'cnic' => ['nullable', 'string', 'regex:/^[0-9]{5}-[0-9]{7}-[0-9]{1}$/'],
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
            'name.required' => 'Name is required',
            'name.max' => 'Name cannot exceed 255 characters',
            'email.email' => 'Please enter a valid email address',
            'email.unique' => 'This email is already registered',
            'date_of_birth.date' => 'Please enter a valid date',
            'date_of_birth.before' => 'Date of birth must be in the past',
            'gender.in' => 'Gender must be male, female, or other',
            'cnic.regex' => 'CNIC must be in format XXXXX-XXXXXXX-X (e.g., 12345-1234567-1)'
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => 'name',
            'email' => 'email address',
            'full_name' => 'full name',
            'date_of_birth' => 'date of birth',
            'gender' => 'gender',
            'address' => 'address',
            'city' => 'city',
            'postal_code' => 'postal code',
            'cnic' => 'CNIC'
        ];
    }
}
