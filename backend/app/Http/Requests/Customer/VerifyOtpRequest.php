<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

class VerifyOtpRequest extends FormRequest
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
            'phone' => ['required', 'string', 'regex:/^\+92[0-9]{10}$/'],
            'otp' => ['required', 'string', 'size:6', 'regex:/^[0-9]{6}$/'],
            'purpose' => ['nullable', 'in:login,reset_pin']
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
            'phone.required' => 'Phone number is required',
            'phone.regex' => 'Phone number must be in format +92XXXXXXXXXX (e.g., +923001234567)',
            'otp.required' => 'OTP code is required',
            'otp.size' => 'OTP code must be exactly 6 digits',
            'otp.regex' => 'OTP code must contain only numbers',
            'purpose.in' => 'Purpose must be either login or reset_pin'
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
            'phone' => 'phone number',
            'otp' => 'OTP code',
            'purpose' => 'OTP purpose'
        ];
    }
}
