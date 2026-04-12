<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreStudentRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nim' => 'required|unique:students,nim',
            'name' => 'required|min:3|max:100|regex:/^[a-zA-Z\s]+$/',
            'email' => 'required|email',
            'phone_number' => 'required|numeric',
            'study_program' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'name.regex' => 'The name field must only contain alphabetic characters and spaces.',
        ];
    }
}
