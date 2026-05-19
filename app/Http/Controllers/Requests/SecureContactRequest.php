<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SecureContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|min:2',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20|min:10|regex:/^[0-9+\-\s\(\)]+$/'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Name is required',
            'name.min' => 'Name must be at least 2 characters',
            'email.required' => 'Email address is required',
            'email.email' => 'Please enter a valid email address',
            'phone.required' => 'Phone number is required',
            'phone.min' => 'Phone number must be at least 10 digits',
            'phone.regex' => 'Please enter a valid phone number'
        ];
    }
}