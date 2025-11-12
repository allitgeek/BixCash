<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BankDetailsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // User must be authenticated (role is already verified by customer.role middleware)
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'bank_name' => [
                'required',
                'string',
                'max:100',
                'regex:/^[a-zA-Z\s\-&]+$/', // Allow letters, spaces, hyphens, ampersands
            ],
            'account_number' => [
                'required',
                'string',
                'max:30',
                'regex:/^[0-9]+$/', // Numeric only
            ],
            'account_title' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-zA-Z\s\-\.]+$/', // Letters, spaces, hyphens, periods
            ],
            'iban' => [
                'nullable',
                'string',
                'size:24',
                'regex:/^PK[0-9]{2}[A-Z0-9]{4}[0-9]{16}$/', // Pakistani IBAN format: PK99AAAA9999999999999999
            ],
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
            'bank_name.required' => 'Bank name is required.',
            'bank_name.regex' => 'Bank name can only contain letters, spaces, hyphens, and ampersands.',
            'bank_name.max' => 'Bank name must not exceed 100 characters.',

            'account_number.required' => 'Account number is required.',
            'account_number.regex' => 'Account number must contain only digits.',
            'account_number.max' => 'Account number must not exceed 30 digits.',

            'account_title.required' => 'Account title (account holder name) is required.',
            'account_title.regex' => 'Account title can only contain letters, spaces, hyphens, and periods.',
            'account_title.max' => 'Account title must not exceed 255 characters.',

            'iban.size' => 'IBAN must be exactly 24 characters long.',
            'iban.regex' => 'IBAN must be in Pakistani format (e.g., PK36SCBL0000001123456702).',
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
            'bank_name' => 'bank name',
            'account_number' => 'account number',
            'account_title' => 'account title',
            'iban' => 'IBAN',
        ];
    }
}
