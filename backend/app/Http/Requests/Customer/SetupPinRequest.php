<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

class SetupPinRequest extends FormRequest
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
            'pin' => ['required', 'string', 'regex:/^[0-9]{4}$/'],
            'pin_confirmation' => ['required', 'same:pin']
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
            'pin.required' => 'PIN is required',
            'pin.regex' => 'PIN must be exactly 4 digits',
            'pin_confirmation.required' => 'PIN confirmation is required',
            'pin_confirmation.same' => 'PIN confirmation does not match'
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
            'pin' => 'PIN',
            'pin_confirmation' => 'PIN confirmation'
        ];
    }
}
